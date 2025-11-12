<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spa;
use App\Models\Gym;
use App\Models\Yoga;

class SearchController extends Controller
{
    /**
     * Global search across spa, gym, and yoga
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query must be at least 2 characters',
                'results' => []
            ]);
        }

        $results = [];

        // Search Spas
        $spas = Spa::where('nama', 'like', '%' . $query . '%')
            ->orWhere('alamat', 'like', '%' . $query . '%')
            ->limit(5)
            ->get();

        foreach ($spas as $spa) {
            $results[] = [
                'type' => 'spa',
                'id' => $spa->id_spa,
                'name' => $spa->nama,
                'address' => $spa->alamat,
                'description' => 'Spa Center - Relaxation & Wellness',
                'image' => $spa->image ? asset('image/' . $spa->image) : asset('image/spa.png'),
                'url' => route('spa.show', $spa->id_spa),
                'rating' => $spa->average_rating ?? 0,
                'icon' => 'spa'
            ];
        }

        // Search Gyms
        $gyms = Gym::where('nama', 'like', '%' . $query . '%')
            ->orWhere('alamat', 'like', '%' . $query . '%')
            ->limit(5)
            ->get();

        foreach ($gyms as $gym) {
            $results[] = [
                'type' => 'gym',
                'id' => $gym->id_gym,
                'name' => $gym->nama,
                'address' => $gym->alamat,
                'description' => 'Fitness Gym - Modern Equipment & Trainers',
                'image' => $gym->image ? asset('image/' . $gym->image) : asset('image/run.png'),
                'url' => route('gym.detail', $gym->id_gym),
                'rating' => $gym->average_rating ?? 0,
                'is_open' => $gym->is_open ?? false,
                'icon' => 'gym'
            ];
        }

        // Search Yoga Studios
        $yogas = Yoga::where('nama', 'like', '%' . $query . '%')
            ->orWhere('alamat', 'like', '%' . $query . '%')
            ->limit(5)
            ->get();

        foreach ($yogas as $yoga) {
            $results[] = [
                'type' => 'yoga',
                'id' => $yoga->id_yoga,
                'name' => $yoga->nama,
                'address' => $yoga->alamat,
                'description' => 'Yoga Studio - Mind & Body Harmony',
                'image' => $yoga->image ? asset('image/' . $yoga->image) : asset('image/meditation.png'),
                'url' => route('yoga.detail', $yoga->id_yoga),
                'rating' => $yoga->average_rating ?? 0,
                'price' => $yoga->harga ?? null,
                'icon' => 'yoga'
            ];
        }

        return response()->json([
            'success' => true,
            'query' => $query,
            'total' => count($results),
            'results' => $results
        ]);
    }
}
