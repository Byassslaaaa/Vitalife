<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Spa;
use App\Models\Yoga;
use App\Models\Gym;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getWebsiteUsageData()
    {
        // Example static data, replace with actual database data
        $data = [
            ['date' => '2023-01-01', 'visits' => 100],
            ['date' => '2023-02-01', 'visits' => 150],
            ['date' => '2023-03-01', 'visits' => 200],
            ['date' => '2023-04-01', 'visits' => 180],
            ['date' => '2023-05-01', 'visits' => 220],
        ];

        return response()->json($data);
    }

    public function index()
    {
        // Get active vouchers for authenticated users
        $vouchers = collect();
        if (auth()->check()) {
            $vouchers = Voucher::where(function($query) {
                    // Check if voucher is not expired
                    $query->whereNull('expired_at')
                        ->orWhere('expired_at', '>', Carbon::now());
                })
                ->where(function($query) {
                    // Check if voucher hasn't reached usage limit
                    $query->whereNull('usage_limit')
                        ->orWhere('usage_count', '<', \DB::raw('usage_limit'));
                })
                ->where('is_used', false) // Only get unused vouchers
                ->orderBy('expired_at', 'asc')
                ->take(4) // Limit to 4 vouchers for display
                ->get();
        }
        
        // Get trending data from each category with proper relationships
        $trendingSpas = Spa::with(['spaDetail', 'spaServices'])->take(2)->get();
        $trendingYogas = Yoga::with('yogaDetail')->take(2)->get();
        $trendingGyms = Gym::with('gymDetail')->take(2)->get();
        
        // Combine all trending items
        $trendingItems = collect();
        
        // Add spas to trending
        foreach ($trendingSpas as $spa) {
            $services = collect();
            
            // FIXED: Check if spa has relationship services (spaServices) or JSON services
            if ($spa->spaServices && $spa->spaServices->count() > 0) {
                // Use relationship services
                $services = $spa->spaServices->take(3)->map(function($service) {
                    return [
                        'name' => $service->name,
                        'description' => $service->description ?? 'Professional spa service',
                        'image' => 'image/spa-service.png'
                    ];
                });
            } elseif ($spa->services && is_array($spa->services) && count($spa->services) > 0) {
                // Use JSON services - FIXED: Use count() function for arrays
                $services = collect(array_slice($spa->services, 0, 3))->map(function($service) {
                    return [
                        'name' => $service['name'] ?? 'Spa Service',
                        'description' => $service['description'] ?? 'Professional spa service',
                        'image' => isset($service['image']) ? asset('storage/' . $service['image']) : 'image/spa-service.png'
                    ];
                });
            } else {
                // Default spa services
                $services = collect([
                    ['name' => 'Body Massage', 'description' => 'Relaxing full body massage', 'image' => 'image/spa-service.png'],
                    ['name' => 'Facial Treatment', 'description' => 'Rejuvenating facial care', 'image' => 'image/spa-service.png'],
                    ['name' => 'Body Scrub', 'description' => 'Exfoliating body treatment', 'image' => 'image/spa-service.png']
                ]);
            }
            
            $trendingItems->push([
                'type' => 'spa',
                'id' => $spa->id_spa,
                'name' => $spa->nama,
                'location' => $spa->alamat,
                'image' => $spa->image ? asset('storage/' . $spa->image) : asset('image/spa-default.jpg'),
                'opening_hours' => $this->formatOpeningHours($spa->waktuBuka),
                'services' => $services,
                'phone' => $spa->noHP ?? '',
                'detail_url' => route('spa.detail', $spa->id_spa)
            ]);
        }
        
        // Add yogas to trending
        foreach ($trendingYogas as $yoga) {
            $trendingItems->push([
                'type' => 'yoga',
                'id' => $yoga->id_yoga,
                'name' => $yoga->nama,
                'location' => $yoga->alamat,
                'image' => $yoga->image ? asset('storage/' . $yoga->image) : asset('image/yoga-default.jpg'),
                'opening_hours' => $this->formatOpeningHours($yoga->waktuBuka),
                'price' => $yoga->harga ?? 0,
                'formatted_price' => $this->formatPrice($yoga->harga ?? 0),
                'phone' => $yoga->noHP ?? '',
                'class_type' => $yoga->class_type ?? 'General',
                'detail_url' => route('yoga.detail', $yoga->id_yoga)
            ]);
        }
        
        // Add gyms to trending
        foreach ($trendingGyms as $gym) {
            $services = collect();
            
            // FIXED: Properly handle gym services
            if ($gym->services && is_array($gym->services) && count($gym->services) > 0) {
                $services = collect(array_slice($gym->services, 0, 3))->map(function($service) {
                    return [
                        'name' => $service['name'] ?? 'Gym Service',
                        'description' => $service['description'] ?? 'Professional gym service',
                        'image' => isset($service['image']) ? asset('storage/' . $service['image']) : 'image/gym-service.png'
                    ];
                });
            } else {
                // Default gym services
                $services = collect([
                    ['name' => 'Weight Training', 'description' => 'Complete weight training equipment', 'image' => 'image/weight-icon.png'],
                    ['name' => 'Cardio', 'description' => 'Modern cardio machines available', 'image' => 'image/cardio-icon.png'],
                    ['name' => 'Personal Trainer', 'description' => 'Professional personal trainers', 'image' => 'image/trainer-icon.png']
                ]);
            }
            
            $trendingItems->push([
                'type' => 'gym',
                'id' => $gym->id_gym,
                'name' => $gym->nama,
                'location' => $gym->alamat,
                'image' => $gym->image ? asset('storage/' . $gym->image) : asset('image/gym-default.jpg'),
                'opening_hours' => $this->formatOpeningHours($gym->waktuBuka),
                'services' => $services,
                'is_open' => $gym->is_open ?? true,
                'open_status' => $gym->is_open ? 'Open' : 'Closed',
                'detail_url' => route('gym.detail', $gym->id_gym)
            ]);
        }
        
        // Shuffle and take first 6 items for display
        $trendingItems = $trendingItems->shuffle()->take(6);
        
        return view('dashboard', compact('vouchers', 'trendingItems'));
    }

    /**
     * Format opening hours for display
     */
    private function formatOpeningHours($waktuBuka)
    {
        if (!$waktuBuka) {
            return 'Contact for hours';
        }
        
        if (is_array($waktuBuka)) {
            // Get today's opening hours
            $today = now()->format('l'); // Full day name
            $dayMapping = [
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa', 
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu'
            ];
            
            $indonesianDay = $dayMapping[$today] ?? $today;
            
            if (isset($waktuBuka[$indonesianDay])) {
                return "Today: " . $waktuBuka[$indonesianDay];
            }
            
            // Return first available day
            return reset($waktuBuka);
        }
        
        return $waktuBuka;
    }

    /**
     * Format price for display
     */
    private function formatPrice($price)
    {
        if (!$price || $price == 0) {
            return 'Contact for price';
        }
        
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}
