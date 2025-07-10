<?php

namespace App\Http\Controllers;

use App\Models\event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventTotal = event::all();
        return view('fitur.event', compact('eventTotal'));
    }

    public function search(Request $request)
    {
        $query = event::query();

        if ($request->filled('nama_event')) {
            $query->where('nama', 'like', '%' . $request->nama_event . '%');
        }

        if ($request->filled('lokasi')) {
            $query->where('alamat', 'like', '%' . $request->lokasi . '%');
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('tanggal', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('jenis_event')) {
            $query->where('deskripsi', 'like', '%' . $request->jenis_event . '%');
        }

        $eventTotal = $query->get();

        return view('fitur.event', compact('eventTotal'));
    }

    /**
     * Display a listing of the resource for admin.
     */
    public function adminIndex()
    {
        $events = event::all();
        $spacount = $events->count();
        return view('admin.event.index', compact('events', 'spacount'));
    }

    public function dashboard()
    {
        $eventcount = event::count();
        return view('admin.dashboard', compact('eventcount'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.formevent');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'deskripsi' => 'required',
            'tanggal' => 'required|date',
            'alamat' => 'required',
            'harga' => 'required|numeric',
            'noHP' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = 'images/' . $imageName;
        }

        try {
            $event = Event::create($validatedData);
            return redirect()->route('admin.spaShow', $event)->with('success', 'Data Event berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data SPA. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_event)
    {
        $event = Event::findOrFail($id_event);
        return view('fitur.event-detail', compact('event'));
    }

    public function edit($id_event)
    {
        $event = Event::findOrFail($id_event);
        return view('admin.event.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_event)
    {
        try {
            $event = Event::findOrFail($id_event);

            $validatedData = $request->validate([
                'nama' => 'required|max:255',
                'deskripsi' => 'required',
                'tanggal' => 'required|date',
                'alamat' => 'required',
                'harga' => 'required|numeric',
                'noHP' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                // Delete old image
                if ($event->image) {
                    Storage::disk('public')->delete($event->image);
                }
                $imagePath = $request->file('image')->store('events', 'public');
                $validatedData['image'] = $imagePath;
            }

            $event->update($validatedData);

            return redirect()->route('admin.event.index')->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update event: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(event $event, $id_event)
    {
        $event = event::findOrFail($id_event);

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'Event deleted successfully.');
    }
}
