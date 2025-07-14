<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Yoga;
use App\Models\YogaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YogaServiceController extends Controller
{
    /**
     * Display a listing of yoga services.
     */
    public function index(Request $request)
    {
        $query = YogaService::with('yoga');

        // Apply filters
        if ($request->filled('yoga_id')) {
            $query->where('yoga_id', $request->yoga_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(15);
        $yogas = Yoga::all(); // For filter dropdown

        return view('admin.yoga-services.index', compact('services', 'yogas'));
    }

    /**
     * Show the form for creating a new yoga service.
     */
    public function create()
    {
        $yogas = Yoga::all();
        return view('admin.yoga-services.create', compact('yogas'));
    }

    /**
     * Store a newly created yoga service.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'yoga_id' => 'required|exists:yogas,id_yoga',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'difficulty_level' => 'nullable|string|max:100',
            'max_participants' => 'nullable|integer|min:1',
            'benefits' => 'nullable|string',
            'requirements' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/yoga_services'), $imageName);
            $validatedData['image'] = 'images/yoga_services/' . $imageName;
        }

        try {
            YogaService::create($validatedData);
            return redirect()->route('admin.yoga-services.index')
                           ->with('success', 'Service yoga berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menambahkan service: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified yoga service.
     */
    public function show($id)
    {
        $service = YogaService::with('yoga')->findOrFail($id);
        return view('admin.yoga-services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified yoga service.
     */
    public function edit($id)
    {
        $service = YogaService::findOrFail($id);
        $yogas = Yoga::all();
        return view('admin.yoga-services.edit', compact('service', 'yogas'));
    }

    /**
     * Update the specified yoga service.
     */
    public function update(Request $request, $id)
    {
        $service = YogaService::findOrFail($id);

        $validatedData = $request->validate([
            'yoga_id' => 'required|exists:yogas,id_yoga',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'difficulty_level' => 'nullable|string|max:100',
            'max_participants' => 'nullable|integer|min:1',
            'benefits' => 'nullable|string',
            'requirements' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/yoga_services'), $imageName);
            $validatedData['image'] = 'images/yoga_services/' . $imageName;
        }

        try {
            $service->update($validatedData);
            return redirect()->route('admin.yoga-services.index')
                           ->with('success', 'Service yoga berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal memperbarui service: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified yoga service.
     */
    public function destroy($id)
    {
        try {
            $service = YogaService::findOrFail($id);
            $service->delete();
            return redirect()->route('admin.yoga-services.index')
                           ->with('success', 'Service yoga berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus service: ' . $e->getMessage());
        }
    }

    /**
     * Toggle service active status
     */
    public function toggleStatus($id)
    {
        try {
            $service = YogaService::findOrFail($id);
            $service->update(['is_active' => !$service->is_active]);

            $status = $service->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()
                           ->with('success', "Service berhasil {$status}");
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal mengubah status service: ' . $e->getMessage());
        }
    }

    /**
     * Get services by yoga for AJAX
     */
    public function getByYoga($yogaId)
    {
        $services = YogaService::where('yoga_id', $yogaId)
                              ->where('is_active', true)
                              ->get();

        return response()->json($services);
    }
}
