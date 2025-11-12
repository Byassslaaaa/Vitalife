<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    /**
     * Store a new testimonial from user
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'testimonial_type' => 'required|in:spa,gym,yoga',
                'testimonial_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|min:10',
                'service' => 'nullable|string|max:255',
            ]);

            // Add user_id if logged in
            if (Auth::check()) {
                $validatedData['user_id'] = Auth::id();
            }

            // Set is_approved to false by default (requires admin approval)
            $validatedData['is_approved'] = false;

            Testimonial::create($validatedData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Terima kasih! Testimonial Anda akan ditampilkan setelah disetujui admin.'
                ]);
            }

            return redirect()->back()->with('success', 'Terima kasih! Testimonial Anda akan ditampilkan setelah disetujui admin.');
        } catch (\Exception $e) {
            Log::error('Error creating testimonial: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim testimonial: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim testimonial. Silakan coba lagi.');
        }
    }

    /**
     * Get testimonials for a specific entity (for AJAX requests)
     */
    public function getTestimonials(Request $request)
    {
        $request->validate([
            'type' => 'required|in:spa,gym,yoga',
            'id' => 'required|integer'
        ]);

        $testimonials = Testimonial::where('testimonial_type', $request->type)
            ->where('testimonial_id', $request->id)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'testimonials' => $testimonials
        ]);
    }

    /**
     * Get available services for a specific venue
     */
    public function getServices(Request $request)
    {
        $request->validate([
            'type' => 'required|in:spa,gym,yoga',
            'id' => 'required|integer'
        ]);

        $services = [];

        try {
            switch ($request->type) {
                case 'spa':
                    $spa = \App\Models\Spa::with('spaServices')->find($request->id);
                    if ($spa) {
                        if ($spa->spaServices && $spa->spaServices->count() > 0) {
                            $services = $spa->spaServices->pluck('name')->toArray();
                        } elseif ($spa->services && is_array($spa->services)) {
                            $services = array_column($spa->services, 'name');
                        }
                    }
                    break;

                case 'gym':
                    $gym = \App\Models\Gym::find($request->id);
                    if ($gym && $gym->services && is_array($gym->services)) {
                        $services = array_column($gym->services, 'name');
                    }
                    break;

                case 'yoga':
                    $yoga = \App\Models\Yoga::with('yogaServices')->find($request->id);
                    if ($yoga) {
                        if ($yoga->yogaServices && $yoga->yogaServices->count() > 0) {
                            $services = $yoga->yogaServices->pluck('name')->toArray();
                        } elseif ($yoga->services && is_array($yoga->services)) {
                            $services = array_column($yoga->services, 'name');
                        }
                    }
                    break;
            }

            return response()->json([
                'success' => true,
                'services' => array_values(array_filter($services))
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching services: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar layanan'
            ], 500);
        }
    }
}
