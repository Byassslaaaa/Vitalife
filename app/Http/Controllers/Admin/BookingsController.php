<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpaBooking;
use App\Models\Spa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingsController extends Controller
{
    /**
     * Display spa bookings
     */
    public function spaBookings(Request $request)
    {
        $query = SpaBooking::with(['spa', 'service']);

        // Apply filters
        if ($request->filled('spa_id')) {
            $query->where('spa_id', $request->spa_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%')
                  ->orWhere('booking_code', 'like', '%' . $request->search . '%');
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);
        $spas = Spa::orderBy('nama')->get();

        // Get statistics
        $stats = [
            'total' => SpaBooking::count(),
            'pending' => SpaBooking::where('status', 'pending')->count(),
            'confirmed' => SpaBooking::where('status', 'confirmed')->count(),
            'completed' => SpaBooking::where('status', 'completed')->count(),
            'cancelled' => SpaBooking::where('status', 'cancelled')->count(),
            'total_revenue' => SpaBooking::where('payment_status', 'paid')->sum('service_price'),
        ];

        return view('admin.spa-bookings.index', compact('bookings', 'spas', 'stats'));
    }

    /**
     * Display the specified spa booking
     */
    public function showSpaBooking(SpaBooking $booking)
    {
        $booking->load(['spa', 'service']);
        
        return view('admin.spa-bookings.show', compact('booking'));
    }

    /**
     * Update spa booking status
     */
    public function updateSpaBookingStatus(Request $request, SpaBooking $booking)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $booking->status;
        
        $booking->update([
            'status' => $validatedData['status'],
            'notes' => $booking->notes . "\n\nAdmin Update (" . now()->format('d/m/Y H:i') . "): Status changed from {$oldStatus} to {$validatedData['status']}" . 
                      ($validatedData['admin_notes'] ? "\nNotes: " . $validatedData['admin_notes'] : '')
        ]);

        // Send notification to customer (implement as needed)
        // $this->sendBookingStatusNotification($booking);

        return response()->json([
            'success' => true,
            'message' => 'Status booking berhasil diupdate!',
            'new_status' => $booking->status
        ]);
    }

    /**
     * Update spa booking payment status
     */
    public function updateSpaPaymentStatus(Request $request, SpaBooking $booking)
    {
        $validatedData = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        $oldPaymentStatus = $booking->payment_status;
        
        $booking->update([
            'payment_status' => $validatedData['payment_status'],
            'notes' => $booking->notes . "\n\nPayment Update (" . now()->format('d/m/Y H:i') . "): Payment status changed from {$oldPaymentStatus} to {$validatedData['payment_status']}" . 
                      ($validatedData['payment_notes'] ? "\nPayment Notes: " . $validatedData['payment_notes'] : '')
        ]);

        // If payment is confirmed and booking is still pending, confirm the booking
        if ($validatedData['payment_status'] === 'paid' && $booking->status === 'pending') {
            $booking->update(['status' => 'confirmed']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diupdate!',
            'new_payment_status' => $booking->payment_status
        ]);
    }

    /**
     * Delete spa booking
     */
    public function destroySpaBooking(SpaBooking $booking)
    {
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dihapus!'
        ]);
    }

    /**
     * Get bookings by spa
     */
    public function spaBookingsBySpa($spaId)
    {
        $spa = Spa::findOrFail($spaId);
        
        $bookings = SpaBooking::with('service')
                             ->where('spa_id', $spaId)
                             ->orderBy('booking_date', 'desc')
                             ->paginate(15);

        return view('admin.spa-bookings.by-spa', compact('spa', 'bookings'));
    }

    /**
     * Bulk operations for bookings
     */
    public function bulkOperation(Request $request)
    {
        $validatedData = $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:spa_bookings,id',
            'operation' => 'required|in:confirm,complete,cancel,delete',
        ]);

        $bookings = SpaBooking::whereIn('id', $validatedData['booking_ids']);

        switch ($validatedData['operation']) {
            case 'confirm':
                $bookings->update([
                    'status' => 'confirmed',
                    'updated_at' => now()
                ]);
                $message = 'Booking berhasil dikonfirmasi!';
                break;
            
            case 'complete':
                $bookings->update([
                    'status' => 'completed',
                    'updated_at' => now()
                ]);
                $message = 'Booking berhasil diselesaikan!';
                break;
            
            case 'cancel':
                $bookings->update([
                    'status' => 'cancelled',
                    'updated_at' => now()
                ]);
                $message = 'Booking berhasil dibatalkan!';
                break;
            
            case 'delete':
                $bookings->delete();
                $message = 'Booking berhasil dihapus!';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Export bookings to CSV
     */
    public function exportBookings(Request $request)
    {
        $query = SpaBooking::with(['spa', 'service']);

        // Apply same filters as index
        if ($request->filled('spa_id')) {
            $query->where('spa_id', $request->spa_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        $filename = 'spa_bookings_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Booking Code', 'Spa Name', 'Service Name', 'Customer Name', 
                'Customer Phone', 'Customer Email', 'Booking Date', 'Booking Time',
                'Service Price', 'Status', 'Payment Status', 'Created At'
            ]);

            // CSV data
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_code,
                    $booking->spa->nama,
                    $booking->service_name,
                    $booking->customer_name,
                    $booking->customer_phone,
                    $booking->customer_email,
                    $booking->booking_date->format('Y-m-d'),
                    $booking->booking_time->format('H:i'),
                    $booking->service_price,
                    $booking->status,
                    $booking->payment_status,
                    $booking->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}