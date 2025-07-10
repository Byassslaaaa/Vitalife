<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Yoga;
use App\Models\YogaDetailConfig;
use Illuminate\Http\Request;

class YogaDetailController extends Controller
{
    public function index()
    {
        $yogas = Yoga::with('detailConfig')->paginate(10);
        return view('admin.yoga-details.index', compact('yogas'));
    }

    public function show($id)
    {
        $yoga = Yoga::with('detailConfig')->findOrFail($id);
        return view('admin.yoga-details.show', compact('yoga'));
    }

    public function edit($id)
    {
        $yoga = Yoga::with('detailConfig')->findOrFail($id);
        return view('admin.yoga-details.edit', compact('yoga'));
    }

    public function update(Request $request, $id)
    {
        $yoga = Yoga::findOrFail($id);
        
        $validatedData = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*.title' => 'required_with:facilities|string|max:255',
            'facilities.*.description' => 'required_with:facilities|string|max:500',
            'facilities.*.icon' => 'required_with:facilities|string|max:100',
            'booking_policy_title' => 'nullable|string|max:255',
            'booking_policy_subtitle' => 'nullable|string|max:255',
            'show_opening_hours' => 'boolean',
            'show_location_map' => 'boolean',
            'custom_css' => 'nullable|string',
        ]);

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('images/gallery'), $imageName);
                $galleryImages[] = 'images/gallery/' . $imageName;
            }
            $validatedData['gallery_images'] = json_encode($galleryImages);
        }

        // Convert facilities array to JSON
        if (isset($validatedData['facilities'])) {
            $validatedData['facilities'] = json_encode($validatedData['facilities']);
        }

        // Update or create detail config
        $yoga->detailConfig()->updateOrCreate(
            ['yoga_id' => $yoga->id_yoga],
            $validatedData
        );

        return redirect()->route('admin.yoga-details.index')
            ->with('success', 'Yoga detail configuration updated successfully.');
    }

    public function preview($id)
    {
        $yoga = Yoga::with('detailConfig')->findOrFail($id);
        return view('fitur.yoga-detail', compact('yoga'));
    }
}
