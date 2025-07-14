<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Yoga;
use App\Models\YogaDetailConfig;
use App\Models\YogaService;
use App\Models\YogaBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class YogasController extends Controller
{
    /**
     * Display a listing of yogas with comprehensive data
     */
    public function index(Request $request)
    {
        $query = Yoga::with(['detailConfig', 'yogaServices']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('is_open', $request->status);
        }

        if ($request->filled('location')) {
            $query->where('alamat', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('class_type')) {
            $query->where('class_type', $request->class_type);
        }

        if ($request->filled('service')) {
            $query->whereHas('yogaServices', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->service . '%');
            });
        }

        $yogas = $query->withCount(['yogaServices'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('admin.yogas.index', compact('yogas'));
    }

    /**
     * Display yoga details management page
     */
    public function detailsIndex(Request $request)
    {
        // Load yogas with their details and services
        $query = Yoga::with(['detailConfig', 'yogaServices']);

        // Apply filters if needed
        if ($request->filled('status')) {
            $query->where('is_open', $request->status);
        }

        if ($request->filled('has_details')) {
            if ($request->has_details == '1') {
                $query->whereHas('detailConfig');
            } else {
                $query->whereDoesntHave('detailConfig');
            }
        }

        $yogas = $query->withCount(['yogaServices'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('admin.yoga-details.index', compact('yogas'));
    }

    /**
     * Show the form for creating a new yoga
     */
    public function create()
    {
        return view('admin.yogas.create');
    }

    /**
     * Store a newly created yoga with all related data
     */
    public function store(Request $request)
    {
        // Simplified validation to focus on core fields
        $validatedData = $request->validate([
            // Basic yoga information
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'required|string|max:20',
            'waktuBuka' => 'required|array',
            'waktuBuka.*' => 'required|string',
            'maps' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'harga' => 'required|numeric|min:0',
            'class_type' => 'required|string',
            'is_open' => 'nullable|boolean',

            // Services data - simplified
            'services' => 'nullable|array',
            'services.*.name' => 'required_with:services|string|max:255',
            'services.*.description' => 'required_with:services|string',
            'services.*.price' => 'required_with:services|numeric|min:0',
            'services.*.duration' => 'nullable|string',
            'services.*.category' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Set default values
            $validatedData['is_open'] = $request->has('is_open') ? true : false;

            // Ensure maps URL is properly formatted
            if ($validatedData['maps']) {
                $validatedData['maps'] = $this->sanitizeMapsUrl($request->maps);
            }

            // Handle main yoga image upload
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $validatedData['image'] = 'images/' . $imageName;
            }

            // Create the yoga
            $yoga = Yoga::create($validatedData);

            // Create services if provided
            if (!empty($validatedData['services'])) {
                foreach ($validatedData['services'] as $serviceData) {
                    YogaService::create([
                        'yoga_id' => $yoga->id_yoga,
                        'name' => $serviceData['name'],
                        'description' => $serviceData['description'],
                        'price' => $serviceData['price'],
                        'duration' => $serviceData['duration'] ?? null,
                        'category' => $serviceData['category'] ?? 'general',
                        'is_active' => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.yogas.index')->with('success', 'Data Yoga berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data Yoga: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified yoga entry.
     */
    public function show($id_yoga)
    {
        $yoga = Yoga::with(['detailConfig', 'yogaServices', 'bookings'])
                   ->findOrFail($id_yoga);

        // Get booking statistics
        $bookingStats = [
            'total' => $yoga->bookings->count(),
            'pending' => $yoga->bookings->where('status', 'pending')->count(),
            'confirmed' => $yoga->bookings->where('status', 'confirmed')->count(),
            'completed' => $yoga->bookings->where('status', 'completed')->count(),
            'cancelled' => $yoga->bookings->where('status', 'cancelled')->count(),
        ];

        return view('admin.yogas.show', compact('yoga', 'bookingStats'));
    }

    /**
     * Show the form for editing the specified yoga entry.
     */
    public function edit($id_yoga)
    {
        $yoga = Yoga::with(['detailConfig', 'yogaServices'])->findOrFail($id_yoga);
        return view('admin.yogas.edit', compact('yoga'));
    }

    /**
     * Update the specified yoga entry in storage.
     */
    public function update(Request $request, $id_yoga)
    {
        $yoga = Yoga::findOrFail($id_yoga);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'alamat' => 'required|string',
            'noHP' => 'required|string',
            'waktuBuka' => 'nullable|array',
            'waktuBuka.*' => 'nullable|string',
            'maps' => 'nullable|string',
            'class_type' => 'required|string',
            'is_open' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Services data
            'services' => 'nullable|array',
            'services.*.name' => 'required_with:services|string|max:255',
            'services.*.description' => 'required_with:services|string',
            'services.*.price' => 'required_with:services|numeric|min:0',
            'services.*.duration' => 'nullable|string',
            'services.*.category' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Set default values
            $validatedData['is_open'] = $request->has('is_open') ? true : false;

            // Ensure maps URL is properly formatted
            if ($validatedData['maps']) {
                $validatedData['maps'] = $this->sanitizeMapsUrl($request->maps);
            }

            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($yoga->image && file_exists(public_path($yoga->image))) {
                    unlink(public_path($yoga->image));
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $validatedData['image'] = 'images/' . $imageName;
            } else {
                // Keep the existing image
                unset($validatedData['image']);
            }

            $yoga->update($validatedData);

            // Update services if provided
            if (isset($validatedData['services'])) {
                // Delete existing services
                $yoga->yogaServices()->delete();

                // Create new services
                foreach ($validatedData['services'] as $serviceData) {
                    YogaService::create([
                        'yoga_id' => $yoga->id_yoga,
                        'name' => $serviceData['name'],
                        'description' => $serviceData['description'],
                        'price' => $serviceData['price'],
                        'duration' => $serviceData['duration'] ?? null,
                        'category' => $serviceData['category'] ?? 'general',
                        'is_active' => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.yogas.index')->with('success', 'Yoga berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data Yoga: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified yoga entry from storage.
     */
    public function destroy($id_yoga)
    {
        $yoga = Yoga::findOrFail($id_yoga);

        DB::beginTransaction();

        try {
            // Delete related services
            $yoga->yogaServices()->delete();

            // Delete related detail config
            if ($yoga->detailConfig) {
                $yoga->detailConfig->delete();
            }

            // Delete the associated image if it exists
            if ($yoga->image && file_exists(public_path($yoga->image))) {
                unlink(public_path($yoga->image));
            }

            $yoga->delete();

            DB::commit();

            return redirect()->route('admin.yogas.index')->with('success', 'Yoga berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data Yoga: ' . $e->getMessage());
        }
    }

    /**
     * Sanitize and format Google Maps embed URL to prevent 404 errors.
     */
    private function sanitizeMapsUrl($url)
    {
        if (empty($url)) {
            return '';
        }

        // If URL doesn't start with http:// or https://, add https://
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . $url;
        }

        // Check if it's a direct embed code pasted from Google Maps
        if (preg_match('/<iframe.*src=[\'"]([^\'"]+)[\'"].*><\/iframe>/i', $url, $matches)) {
            return $matches[1]; // Extract just the URL from the iframe tag
        }

        // If it's already a Google Maps embed URL, return it as is
        if (strpos($url, 'google.com/maps/embed') !== false) {
            return $url;
        }

        // If it's a standard Google Maps URL, try to convert it to an embed URL
        if (strpos($url, 'google.com/maps') !== false) {
            // For share URLs that contain a place ID
            if (strpos($url, 'maps/place') !== false && preg_match('/place\/[^\/]+\/([^\/]+)/', $url, $matches)) {
                $placeId = $matches[1];
                return "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.3!2d0!3d0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2s!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid&q=place_id:{$placeId}";
            }

            // Pattern 1: @lat,lng,zoom
            if (preg_match('/@([-\d.]+),([-\d.]+),([\d.]+)z/', $url, $matches)) {
                $lat = $matches[1];
                $lng = $matches[2];
                return "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.3!2d{$lng}!3d{$lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid";
            }

            // Pattern 2: /place/...
            if (strpos($url, '/place/') !== false) {
                // Extract the place name
                $placeParts = explode('/place/', $url);
                if (count($placeParts) > 1) {
                    $place = $placeParts[1];
                    // Remove any additional URL components
                    $place = strtok($place, '/');
                    $place = str_replace('+', ' ', $place);
                    // Create a clean embed URL for the place
                    $encodedPlace = urlencode($place);
                return "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.3!2d0!3d0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2s{$encodedPlace}!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid";
            }
        }

        // Pattern 3: URL with a query parameter
        if (strpos($url, '?q=') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $query);
            if (isset($query['q'])) {
                $encodedPlace = urlencode($query['q']);
                return "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.3!2d0!3d0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2s{$encodedPlace}!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid";
            }
        }
    }

    // If we can't convert it but it looks like a Google Maps URL, return a fallback embed URL
    if (strpos($url, 'google.com/maps') !== false) {
        // Extract any location information from the URL
        $location = '';
        if (preg_match('/\?q=([^&]+)/', $url, $matches)) {
            $location = urldecode($matches[1]);
        } elseif (preg_match('/\/place\/([^\/]+)/', $url, $matches)) {
            $location = str_replace('+', ' ', $matches[1]);
        }

        if (!empty($location)) {
            $encodedLocation = urlencode($location);
            return "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.3!2d0!3d0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2s!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid&q={$encodedLocation}";
        }
    }

    // If it's not a Google Maps URL but contains a location, create a search URL
    if (!strpos($url, 'google.com/maps') && !empty($url)) {
        $encodedLocation = urlencode($url);
        return "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.3!2d0!3d0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2s!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid&q={$encodedLocation}";
    }

    // If all else fails, return the original URL
    return $url;
}
}
