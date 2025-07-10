<?php

namespace App\Http\Controllers;

use App\Models\Spa;
use App\Models\SpaDetail;
use Illuminate\Http\Request;

class SpaDetailController extends Controller
{
    /**
     * Display the specified spa detail
     */
    public function show($id)
    {
        $spa = Spa::with(['spaServices' => function($query) {
            $query->where('is_active', true)->orderBy('name');
        }, 'spaDetail'])
        ->where('id_spa', $id)
        ->firstOrFail();

        // Create default spa detail if it doesn't exist
        if (!$spa->spaDetail) {
            $spa->spaDetail()->create([
                'hero_title' => $spa->nama,
                'description' => 'Experience ultimate relaxation and rejuvenation at our professional spa.',
                'show_facilities' => true,
                'show_opening_hours' => true,
                'show_booking_policy' => true,
                'show_location_map' => true,
                'booking_policy_title' => 'BOOKING POLICY',
                'booking_policy_subtitle' => 'YOUR WELLNESS PLANS',
                'contact_person_name' => 'Contact Person',
                'contact_person_phone' => $spa->noHP,
            ]);
            
            // Refresh the relationship
            $spa->load('spaDetail');
        }

        return view('fitur.spa-detail', compact('spa'));
    }

    /**
     * Show the form for editing spa detail configuration
     */
    public function edit($id)
    {
        $spa = Spa::with('spaDetail')->findOrFail($id);
        
        // Create default spa detail if it doesn't exist
        if (!$spa->spaDetail) {
            $spa->spaDetail()->create([
                'hero_title' => $spa->nama,
                'description' => 'Experience ultimate relaxation and rejuvenation at our professional spa.',
                'show_facilities' => true,
                'show_opening_hours' => true,
                'show_booking_policy' => true,
                'show_location_map' => true,
                'booking_policy_title' => 'BOOKING POLICY',
                'booking_policy_subtitle' => 'YOUR WELLNESS PLANS',
                'contact_person_name' => 'Contact Person',
                'contact_person_phone' => $spa->noHP,
            ]);
            
            $spa->load('spaDetail');
        }

        return view('admin.spa-details.edit', compact('spa'));
    }

    /**
     * Update spa detail configuration
     */
    public function update(Request $request, $id)
    {
        $spa = Spa::findOrFail($id);
        
        $validated = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'about_spa' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*' => 'nullable|string',
            'custom_css' => 'nullable|string',
            'show_facilities' => 'boolean',
            'show_opening_hours' => 'boolean',
            'show_booking_policy' => 'boolean',
            'show_location_map' => 'boolean',
            'booking_policy_title' => 'nullable|string|max:255',
            'booking_policy_subtitle' => 'nullable|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
        ]);

        // Handle gallery images upload - changed to mainvita/public/images
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $image) {
                if ($image) {
                    $filename = time() . '_gallery_' . $index . '_' . $image->getClientOriginalName();
                    
                    // Create directory if it doesn't exist
                    $uploadPath = public_path('mainvita/public/images');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    $image->move($uploadPath, $filename);
                    $galleryImages[] = 'mainvita/public/images/' . $filename;
                }
            }
        }

        // If new images uploaded, use them; otherwise keep existing
        if (!empty($galleryImages)) {
            $validated['gallery_images'] = $galleryImages;
        }

        // Convert checkboxes to boolean
        $validated['show_facilities'] = $request->has('show_facilities');
        $validated['show_opening_hours'] = $request->has('show_opening_hours');
        $validated['show_booking_policy'] = $request->has('show_booking_policy');
        $validated['show_location_map'] = $request->has('show_location_map');

        $spa->spaDetail()->updateOrCreate(
            ['spa_id' => $spa->id_spa],
            $validated
        );

        return redirect()
            ->route('admin.spas.show', $spa->id_spa)
            ->with('success', 'Spa detail configuration updated successfully!');
    }
}
