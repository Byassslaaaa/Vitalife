<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use App\Models\GymService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GymServiceController extends Controller
{
    public function index()
    {
        $services = GymService::with('gym')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $gyms = Gym::all();
        return view('admin.services.create', compact('gyms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id_gym',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/services'), $imageName);
            $data['image'] = 'images/services/' . $imageName;
        }

        GymService::create($data);

        return redirect()->route('admin.services.index')
                        ->with('success', 'Service berhasil ditambahkan');
    }

    public function show(GymService $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(GymService $service)
    {
        $gyms = Gym::all();
        return view('admin.services.edit', compact('service', 'gyms'));
    }

    public function update(Request $request, GymService $service)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id_gym',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }
            
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/services'), $imageName);
            $data['image'] = 'images/services/' . $imageName;
        }

        $service->update($data);

        return redirect()->route('admin.services.index')
                        ->with('success', 'Service berhasil diperbarui');
    }

    public function destroy(GymService $service)
    {
        // Delete image if exists
        if ($service->image && file_exists(public_path($service->image))) {
            unlink(public_path($service->image));
        }
        
        $service->delete();
        
        return redirect()->route('admin.services.index')
                        ->with('success', 'Service berhasil dihapus');
    }
}
