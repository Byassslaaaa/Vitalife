<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Spa;
use App\Models\SpaService;
use Illuminate\Support\Facades\Storage;

class SpaServicesController extends Controller
{
    /**
     * Display services for a specific spa
     */
    public function index($spaId)
    {
        $spa = Spa::findOrFail($spaId);
        $services = $spa->spaServices()->orderBy('name')->paginate(15);
        
        return view('admin.spa-services.index', compact('spa', 'services'));
    }

    /**
     * Display a listing of all spa services (global view)
     */
    public function globalIndex(Request $request)
    {
        $query = SpaService::with('spa');

        // Apply filters
        if ($request->filled('spa_id')) {
            $query->where('spa_id', $request->spa_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $services = $query->orderBy('name')->paginate(15);
        $spas = Spa::orderBy('nama')->get();
        $categories = SpaService::getCategories();

        return view('admin.spa-services.global-index', compact('services', 'spas', 'categories'));
    }

    /**
     * Show the form for creating a new service for a specific spa
     */
    public function create($spaId)
    {
        $spa = Spa::findOrFail($spaId);
        $categories = SpaService::getCategories();
        
        return view('admin.spa-services.create', compact('spa', 'categories'));
    }

    /**
     * Show the form for creating a new service (global)
     */
    public function globalCreate()
    {
        $spas = Spa::orderBy('nama')->get();
        $categories = SpaService::getCategories();
        
        return view('admin.spa-services.global-create', compact('spas', 'categories'));
    }

    /**
     * Store a newly created service for a specific spa
     */
    public function store(Request $request, $spaId)
    {
        $spa = Spa::findOrFail($spaId);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Handle image upload - changed to mainvita/public/images
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_service_' . $file->getClientOriginalName();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('mainvita/public/images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validatedData['image'] = 'mainvita/public/images/' . $filename;
        }

        $validatedData['spa_id'] = $spa->id_spa;
        $validatedData['is_active'] = $request->has('is_active');

        SpaService::create($validatedData);

        return redirect()->route('admin.spas.services.index', $spa->id_spa)
                        ->with('success', 'Layanan berhasil ditambahkan!');
    }

    /**
     * Store a newly created service (global)
     */
    public function globalStore(Request $request)
    {
        $validatedData = $request->validate([
            'spa_id' => 'required|exists:spas,id_spa',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Handle image upload - changed to mainvita/public/images
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_service_' . $file->getClientOriginalName();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('mainvita/public/images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validatedData['image'] = 'mainvita/public/images/' . $filename;
        }

        $validatedData['is_active'] = $request->has('is_active');

        SpaService::create($validatedData);

        return redirect()->route('admin.spa-services.index')
                        ->with('success', 'Layanan berhasil ditambahkan!');
    }

    /**
     * Display the specified service
     */
    public function show($spaId, $serviceId)
    {
        $spa = Spa::findOrFail($spaId);
        $service = SpaService::where('spa_id', $spaId)->findOrFail($serviceId);
        $service->load(['spaBookings' => function ($query) {
            $query->latest()->take(10);
        }]);

        // Get booking statistics for this service
        $bookingStats = [
            'total' => $service->spaBookings()->count(),
            'pending' => $service->spaBookings()->where('status', 'pending')->count(),
            'confirmed' => $service->spaBookings()->where('status', 'confirmed')->count(),
            'completed' => $service->spaBookings()->where('status', 'completed')->count(),
            'revenue' => $service->spaBookings()->where('payment_status', 'paid')->sum('service_price'),
        ];

        return view('admin.spa-services.show', compact('spa', 'service', 'bookingStats'));
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit($spaId, $serviceId)
    {
        $spa = Spa::findOrFail($spaId);
        $service = SpaService::where('spa_id', $spaId)->findOrFail($serviceId);
        $categories = SpaService::getCategories();
        
        return view('admin.spa-services.edit', compact('spa', 'service', 'categories'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, $spaId, $serviceId)
    {
        $spa = Spa::findOrFail($spaId);
        $service = SpaService::where('spa_id', $spaId)->findOrFail($serviceId);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Handle image upload - changed to mainvita/public/images
        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }
            
            $file = $request->file('image');
            $filename = time() . '_service_' . $file->getClientOriginalName();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('mainvita/public/images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validatedData['image'] = 'mainvita/public/images/' . $filename;
        }

        $validatedData['is_active'] = $request->has('is_active');

        $service->update($validatedData);

        return redirect()->route('admin.spas.services.index', $spa->id_spa)
                        ->with('success', 'Layanan berhasil diupdate!');
    }

    /**
     * Remove the specified service
     */
    public function destroy($spaId, $serviceId)
    {
        $spa = Spa::findOrFail($spaId);
        $service = SpaService::where('spa_id', $spaId)->findOrFail($serviceId);

        // Delete image if exists - updated path
        if ($service->image && file_exists(public_path($service->image))) {
            unlink(public_path($service->image));
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil dihapus!'
        ]);
    }

    /**
     * Toggle service status
     */
    public function toggleStatus($spaId, $serviceId)
    {
        $spa = Spa::findOrFail($spaId);
        $service = SpaService::where('spa_id', $spaId)->findOrFail($serviceId);
        
        $service->update(['is_active' => !$service->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status layanan berhasil diubah!',
            'new_status' => $service->is_active
        ]);
    }

    /**
     * Get services by spa (AJAX)
     */
    public function getServicesBySpa($spaId)
    {
        $services = SpaService::where('spa_id', $spaId)
                             ->where('is_active', true)
                             ->orderBy('name')
                             ->get();

        return response()->json($services);
    }

    /**
     * Bulk update services
     */
    public function bulkUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:spa_services,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $services = SpaService::whereIn('id', $validatedData['service_ids']);

        switch ($validatedData['action']) {
            case 'activate':
                $services->update(['is_active' => true]);
                $message = 'Layanan berhasil diaktifkan!';
                break;
            
            case 'deactivate':
                $services->update(['is_active' => false]);
                $message = 'Layanan berhasil dinonaktifkan!';
                break;
            
            case 'delete':
                // Delete images first - updated path
                foreach ($services->get() as $service) {
                    if ($service->image && file_exists(public_path($service->image))) {
                        unlink(public_path($service->image));
                    }
                }
                $services->delete();
                $message = 'Layanan berhasil dihapus!';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
