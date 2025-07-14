<?php

namespace App\Http\Controllers;

use App\Models\Yoga;
use Illuminate\Http\Request;

class YogaController extends Controller
{
    public function showYogas()
    {
        $yogaTotal = Yoga::all(); // Fetch all yoga data
        return view('fitur.yoga', compact('yogaTotal'));
    }

    public function yogaFilter(Request $request)
    {
        $yoga = $request->input('location');
        $yogaTotal = Yoga::where('alamat', 'like', "%$yoga%")->get();
        return view('fitur.yogaFilter', compact('yogaTotal'));
    }

    public function index(Request $request)
    {
        $query = Yoga::query();

        // Pencarian berdasarkan nama
        if ($request->has('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan lokasi
        if ($request->has('location') && $request->location != '') {
            $query->where('alamat', 'like', '%' . $request->location . '%');
        }

        // Filter berdasarkan rentang harga
        if ($request->filled('min_price') || $request->filled('max_price')) {
            if ($request->filled('min_price')) {
                $query->where('harga', '>=', $request->min_price);
                $searchCriteria[] = "harga minimal: Rp " . number_format($request->min_price, 0, ',', '.');
            }
            if ($request->filled('max_price')) {
                $query->where('harga', '<=', $request->max_price);
                $searchCriteria[] = "harga maksimal: Rp " . number_format($request->max_price, 0, ',', '.');
            }
            $searchPerformed = true;
        }

        $yogaTotal = $query->get();

        // Process maps URLs to ensure they work properly
        foreach ($yogaTotal as $yoga) {
            $yoga->maps = $this->sanitizeMapsUrl($yoga->maps);
        }

        return view('fitur.yoga', compact('yogaTotal'));
    }

    public function create()
    {
        return view('admin.formyoga');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'alamat' => 'required|string',
            'noHP' => 'required|string',
            'waktuBuka' => 'required|array',
            'waktuBuka.*' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'maps' => 'required|string'
        ]);

        // Ensure maps URL is properly formatted
        $validatedData['maps'] = $this->sanitizeMapsUrl($request->maps);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = 'images/' . $imageName;
        }

        try {
            $yoga = Yoga::create($validatedData);
            return redirect()->route('admin.yogas.index')->with('success', 'Data Yoga berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data Yoga. Silakan coba lagi.');
        }
    }

    public function show(Yoga $yoga)
    {
        // Ensure maps URL is properly formatted
        $yoga->maps = $this->sanitizeMapsUrl($yoga->maps);
        return view('admin.yogaShow', compact('yoga'));
    }

    public function edit(Yoga $yoga)
    {
        return view('admin.yogaEdit', compact('yoga'));
    }

    public function update(Request $request, Yoga $yoga)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'harga' => 'required|integer',
            'alamat' => 'required',
            'noHP' => 'required',
            'maps' => 'required|string'
        ]);

        // Ensure maps URL is properly formatted
        $validatedData['maps'] = $this->sanitizeMapsUrl($request->maps);

        $yoga->update($validatedData);

        return redirect()->route('admin.yogaIndex')->with('success', 'Data Yoga berhasil diperbarui');
    }

    public function destroy(Yoga $yoga)
    {
        $yoga->delete();
        return redirect()->route('admin.formyoga')->with('success', 'Data Yoga berhasil dihapus');
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

    /**
     * Display the specified yoga detail page.
     *
     * @param  int  $id_yoga
     * @return \Illuminate\Http\Response
     */
    public function detail($id_yoga)
    {
        $yoga = Yoga::with(['yogaServices', 'detailConfig'])->findOrFail($id_yoga);

        // Ensure maps URL is properly formatted
        if (method_exists($this, 'sanitizeMapsUrl')) {
            $yoga->maps = $this->sanitizeMapsUrl($yoga->maps);
        }

        return view('fitur.yoga-detail', compact('yoga'));
    }
}
