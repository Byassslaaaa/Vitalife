<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Spa;
use App\Models\Gym;
use App\Models\Yoga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    /**
     * Display a listing of all testimonials
     */
    public function index(Request $request)
    {
        $query = Testimonial::with('user');

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('testimonial_type', $request->type);
        }

        // Filter by approval status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_approved', $request->status == 'approved');
        }

        // Search by name or comment
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('comment', 'like', '%' . $request->search . '%');
            });
        }

        $testimonials = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial
     */
    public function create()
    {
        $spas = Spa::orderBy('nama')->get();
        $gyms = Gym::orderBy('nama')->get();
        $yogas = Yoga::orderBy('nama')->get();

        return view('admin.testimonials.create', compact('spas', 'gyms', 'yogas'));
    }

    /**
     * Store a newly created testimonial
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'testimonial_type' => 'required|in:spa,gym,yoga',
                'testimonial_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string',
                'service' => 'nullable|string|max:255',
                'is_approved' => 'boolean'
            ]);

            $validatedData['is_approved'] = $request->has('is_approved');

            Testimonial::create($validatedData);

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'Testimonial berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating testimonial: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan testimonial: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified testimonial
     */
    public function show(Testimonial $testimonial)
    {
        $testimonial->load('user');

        // Get the related entity
        $entity = null;
        switch ($testimonial->testimonial_type) {
            case 'spa':
                $entity = Spa::find($testimonial->testimonial_id);
                break;
            case 'gym':
                $entity = Gym::find($testimonial->testimonial_id);
                break;
            case 'yoga':
                $entity = Yoga::find($testimonial->testimonial_id);
                break;
        }

        return view('admin.testimonials.show', compact('testimonial', 'entity'));
    }

    /**
     * Show the form for editing the testimonial
     */
    public function edit(Testimonial $testimonial)
    {
        $spas = Spa::orderBy('nama')->get();
        $gyms = Gym::orderBy('nama')->get();
        $yogas = Yoga::orderBy('nama')->get();

        return view('admin.testimonials.edit', compact('testimonial', 'spas', 'gyms', 'yogas'));
    }

    /**
     * Update the specified testimonial
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        try {
            $validatedData = $request->validate([
                'testimonial_type' => 'required|in:spa,gym,yoga',
                'testimonial_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string',
                'service' => 'nullable|string|max:255',
                'is_approved' => 'boolean'
            ]);

            $validatedData['is_approved'] = $request->has('is_approved');

            $testimonial->update($validatedData);

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'Testimonial berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating testimonial: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui testimonial: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified testimonial
     */
    public function destroy(Testimonial $testimonial)
    {
        try {
            $testimonial->delete();
            return redirect()->route('admin.testimonials.index')
                ->with('success', 'Testimonial berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting testimonial: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus testimonial: ' . $e->getMessage());
        }
    }

    /**
     * Approve a testimonial
     */
    public function approve(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_approved' => true]);
            return redirect()->back()
                ->with('success', 'Testimonial berhasil disetujui');
        } catch (\Exception $e) {
            Log::error('Error approving testimonial: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menyetujui testimonial: ' . $e->getMessage());
        }
    }

    /**
     * Reject/unapprove a testimonial
     */
    public function reject(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_approved' => false]);
            return redirect()->back()
                ->with('success', 'Testimonial ditolak');
        } catch (\Exception $e) {
            Log::error('Error rejecting testimonial: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menolak testimonial: ' . $e->getMessage());
        }
    }
}
