<?php

namespace App\Http\Controllers;

use App\Models\Spa;
use App\Models\SpaBooking;
use App\Models\SpaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SpaAnalyticsExport;

class SpaAdminController extends Controller
{
    public function index()
    {
        return view('admin.spas.index');
    }

    public function create()
    {
        return view('admin.spas.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
        ]);

        Spa::create($validatedData);
        return redirect()->route('admin.spas.index')->with('success', 'Spa berhasil ditambahkan!');
    }

    public function show($id)
    {
        $spa = Spa::findOrFail($id);
        return view('admin.spas.show', compact('spa'));
    }

    public function edit($id)
    {
        $spa = Spa::findOrFail($id);
        return view('admin.spas.edit', compact('spa'));
    }

    public function update(Request $request, $id)
    {
        $spa = Spa::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
        ]);

        $spa->update($validatedData);
        return redirect()->route('admin.spas.index')->with('success', 'Spa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $spa = Spa::findOrFail($id);
        $spa->delete();
        return redirect()->route('admin.spas.index')->with('success', 'Spa berhasil dihapus!');
    }

    /**
     * Display spa analytics dashboard
     */
    public function analytics(Request $request)
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Convert to Carbon instances
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Total Revenue
        $totalRevenue = SpaBooking::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->sum('service_price');

        // Total Bookings
        $totalBookings = SpaBooking::whereBetween('created_at', [$start, $end])->count();

        // Completed Bookings
        $completedBookings = SpaBooking::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->count();

        // Cancelled Bookings
        $cancelledBookings = SpaBooking::whereBetween('created_at', [$start, $end])
            ->where('status', 'cancelled')
            ->count();

        // Pending Payments
        $pendingPayments = SpaBooking::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'pending')
            ->count();

        // Revenue by Day (for chart)
        $revenueByDay = SpaBooking::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(service_price) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Bookings by Status (for pie chart)
        $bookingsByStatus = SpaBooking::whereBetween('created_at', [$start, $end])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Top Services
        $topServices = SpaBooking::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->select('service_name', DB::raw('count(*) as bookings'), DB::raw('SUM(service_price) as revenue'))
            ->groupBy('service_name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // Recent Bookings
        $recentBookings = SpaBooking::whereBetween('created_at', [$start, $end])
            ->with('spa')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.spa-analytics.index', compact(
            'totalRevenue',
            'totalBookings',
            'completedBookings',
            'cancelledBookings',
            'pendingPayments',
            'revenueByDay',
            'bookingsByStatus',
            'topServices',
            'recentBookings',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display revenue report
     */
    public function revenueReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Revenue breakdown by spa location
        $revenueBySpa = SpaBooking::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->join('spas', 'spa_bookings.spa_id', '=', 'spas.id_spa')
            ->select('spas.nama', DB::raw('SUM(service_price) as total_revenue'), DB::raw('count(*) as total_bookings'))
            ->groupBy('spas.id_spa', 'spas.nama')
            ->orderByDesc('total_revenue')
            ->get();

        // Revenue by payment status
        $revenueByPaymentStatus = SpaBooking::whereBetween('created_at', [$start, $end])
            ->select('payment_status', DB::raw('SUM(service_price) as total'), DB::raw('count(*) as bookings'))
            ->groupBy('payment_status')
            ->get();

        // Monthly revenue trend
        $monthlyRevenue = SpaBooking::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(service_price) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.spa-analytics.revenue', compact(
            'revenueBySpa',
            'revenueByPaymentStatus',
            'monthlyRevenue',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display booking report
     */
    public function bookingReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Bookings by status
        $bookingsByStatus = SpaBooking::whereBetween('created_at', [$start, $end])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Bookings by spa location
        $bookingsBySpa = SpaBooking::whereBetween('created_at', [$start, $end])
            ->join('spas', 'spa_bookings.spa_id', '=', 'spas.id_spa')
            ->select('spas.nama', DB::raw('count(*) as total'))
            ->groupBy('spas.id_spa', 'spas.nama')
            ->orderByDesc('total')
            ->get();

        // Daily booking trend
        $dailyBookings = SpaBooking::whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Peak booking hours
        $peakHours = SpaBooking::whereBetween('created_at', [$start, $end])
            ->whereNotNull('booking_time')
            ->select(
                DB::raw('HOUR(booking_time) as hour'),
                DB::raw('count(*) as total')
            )
            ->groupBy('hour')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.spa-analytics.bookings', compact(
            'bookingsByStatus',
            'bookingsBySpa',
            'dailyBookings',
            'peakHours',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display service performance report
     */
    public function serviceReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Service performance metrics
        $servicePerformance = SpaBooking::whereBetween('created_at', [$start, $end])
            ->select(
                'service_name',
                DB::raw('count(*) as total_bookings'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN service_price ELSE 0 END) as total_revenue'),
                DB::raw('AVG(service_price) as avg_price'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled')
            )
            ->groupBy('service_name')
            ->orderByDesc('total_revenue')
            ->get();

        // Service popularity trend
        $serviceTrend = SpaBooking::whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                'service_name',
                DB::raw('count(*) as total')
            )
            ->groupBy('date', 'service_name')
            ->orderBy('date')
            ->get();

        return view('admin.spa-analytics.services', compact(
            'servicePerformance',
            'serviceTrend',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export analytics report
     */
    public function exportReport(Request $request)
    {
        $format = $request->input('format', 'pdf'); // pdf or excel
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Gather all analytics data
        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_revenue' => SpaBooking::whereBetween('created_at', [$start, $end])
                ->where('payment_status', 'paid')
                ->sum('service_price'),
            'total_bookings' => SpaBooking::whereBetween('created_at', [$start, $end])->count(),
            'completed_bookings' => SpaBooking::whereBetween('created_at', [$start, $end])
                ->where('status', 'completed')
                ->count(),
            'cancelled_bookings' => SpaBooking::whereBetween('created_at', [$start, $end])
                ->where('status', 'cancelled')
                ->count(),
            'bookings_by_status' => SpaBooking::whereBetween('created_at', [$start, $end])
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'top_services' => SpaBooking::whereBetween('created_at', [$start, $end])
                ->where('payment_status', 'paid')
                ->select('service_name', DB::raw('count(*) as bookings'), DB::raw('SUM(service_price) as revenue'))
                ->groupBy('service_name')
                ->orderByDesc('revenue')
                ->limit(10)
                ->get(),
            'revenue_by_spa' => SpaBooking::whereBetween('created_at', [$start, $end])
                ->where('payment_status', 'paid')
                ->join('spas', 'spa_bookings.spa_id', '=', 'spas.id_spa')
                ->select('spas.nama', DB::raw('SUM(service_price) as total_revenue'))
                ->groupBy('spas.id_spa', 'spas.nama')
                ->orderByDesc('total_revenue')
                ->get(),
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.spa-analytics.export-pdf', $data);
            return $pdf->download('spa-analytics-' . $startDate . '-to-' . $endDate . '.pdf');
        } else {
            // For Excel export, we'll return a simple CSV for now
            // You can implement a proper Excel export class later
            return Excel::download(new SpaAnalyticsExport($start, $end), 'spa-analytics-' . $startDate . '-to-' . $endDate . '.xlsx');
        }
    }
}
