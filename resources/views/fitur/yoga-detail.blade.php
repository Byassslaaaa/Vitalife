<x-app-layout>
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Prevent browser caching to ensure fresh authentication state -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <style>
        .yoga-gradient {
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 50%, #99f6e4 100%);
        }

        .thumbnail {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .thumbnail.active {
            border-color: #3b82f6 !important;
            opacity: 1 !important;
        }
    </style>

    {{-- Unified YOGA Detail Section --}}
    <div class="yoga-gradient pb-0">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Side - Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Hero Image Gallery -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <!-- Main Image -->
                        <div class="relative">
                            @php
                                $galleryImages = [];
                                if (
                                    isset($yoga->detailConfig) &&
                                    $yoga->detailConfig &&
                                    $yoga->detailConfig->gallery_images
                                ) {
                                    if (is_array($yoga->detailConfig->gallery_images)) {
                                        $galleryImages = $yoga->detailConfig->gallery_images;
                                    } elseif (is_string($yoga->detailConfig->gallery_images)) {
                                        $decoded = json_decode($yoga->detailConfig->gallery_images, true);
                                        if ($decoded) {
                                            $galleryImages = $decoded;
                                        }
                                    }
                                }

                                // Fallback images
                                if (empty($galleryImages)) {
                                    $galleryImages = [
                                        $yoga->image ?? 'images/default-yoga.jpg',
                                        'images/yoga-studio-1.jpg',
                                        'images/yoga-studio-2.jpg',
                                        'images/yoga-studio-3.jpg',
                                        'images/yoga-studio-4.jpg',
                                    ];
                                }

                                $mainImage = $galleryImages[0];
                            @endphp
                            <img id="mainImage" src="{{ asset($mainImage) }}" alt="{{ $yoga->nama ?? 'Yoga' }}"
                                class="w-full h-96 object-cover">
                        </div>

                        <!-- Thumbnail Images -->
                        <div class="p-4">
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($galleryImages as $index => $image)
                                    <img src="{{ asset($image) }}" alt="Image {{ $index + 1 }}"
                                        class="thumbnail w-full h-20 object-cover rounded-lg cursor-pointer border-2 {{ $index === 0 ? 'border-blue-500 opacity-100' : 'border-transparent opacity-70' }} hover:opacity-100"
                                        onclick="changeMainImage('{{ asset($image) }}', this)">
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Services Section -->
                    @if ($yoga->yogaServices && $yoga->yogaServices->count() > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Classes</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($yoga->yogaServices->where('is_active', true) as $service)
                                    <div
                                        class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-3">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h3>
                                            <span
                                                class="text-lg font-bold text-blue-600">{{ $service->formatted_price }}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-3">{{ $service->description }}</p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-500">{{ $service->duration }}</span>
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $service->category }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Yoga Information -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <!-- Title and Location -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $yoga->nama }}</h1>

                            <!-- Rating Stars -->
                            <div class="flex items-center mb-3">
                                <div class="flex items-center">
                                    @php
                                        $averageRating = $yoga->average_rating;
                                        $fullStars = floor($averageRating);
                                        $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                    @endphp

                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endfor

                                    @if ($hasHalfStar)
                                        <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="halfGradYoga">
                                                    <stop offset="50%" stop-color="#FBBF24"/>
                                                    <stop offset="50%" stop-color="#D1D5DB"/>
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#halfGradYoga)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endif

                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-2 text-gray-700 font-semibold">
                                    {{ number_format($averageRating, 1) }}
                                </span>
                                <span class="ml-1 text-gray-500">
                                    ({{ $yoga->testimonials_count }} {{ $yoga->testimonials_count == 1 ? 'review' : 'reviews' }})
                                </span>
                            </div>

                            <div class="flex items-center text-gray-600 text-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $yoga->alamat }}</span>
                            </div>
                        </div>

                        <!-- Services Section (from database/seeder) -->
                        @if ($yoga->services && count($yoga->services) > 0)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Classes</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($yoga->services as $service)
                                        <div
                                            class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                                            <div class="flex-shrink-0">
                                                @if(isset($service['image']) && $service['image'])
                                                    <img src="{{ asset($service['image']) }}"
                                                         alt="{{ $service['name'] }}"
                                                         class="w-16 h-16 rounded-full object-cover border-2 border-teal-200">
                                                @else
                                                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">{{ $service['name'] }}
                                                </h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $service['description'] }}</p>
                                                @if (isset($service['price']))
                                                    <p class="text-sm font-bold text-teal-600 mt-2">
                                                        Rp {{ number_format($service['price'], 0, ',', '.') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Facilities Section -->
                        @php
                            $facilities = [];
                            if (isset($yoga->detailConfig) && $yoga->detailConfig && $yoga->detailConfig->facilities) {
                                if (is_array($yoga->detailConfig->facilities)) {
                                    // Filter to ensure we only have string values
                                    $facilities = array_filter($yoga->detailConfig->facilities, function($item) {
                                        return is_string($item);
                                    });
                                } elseif (is_string($yoga->detailConfig->facilities)) {
                                    $decoded = json_decode($yoga->detailConfig->facilities, true);
                                    if ($decoded) {
                                        // Filter to ensure we only have string values
                                        $facilities = array_filter($decoded, function($item) {
                                            return is_string($item);
                                        });
                                    }
                                }
                            }

                            // Fallback facilities
                            if (empty($facilities)) {
                                $facilities = [
                                    'Yoga Mats & Props',
                                    'Professional Instructors',
                                    'Peaceful Environment',
                                    'Meditation Space',
                                    'Sound System',
                                    'Air Conditioning',
                                ];
                            }
                        @endphp
                        @if (!empty($facilities))
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                    <svg class="w-7 h-7 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                    Facilities & Amenities
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($facilities as $facility)
                                        @php
                                            $iconConfig = ['path' => 'M5 13l4 4L19 7', 'viewBox' => '0 0 24 24', 'gradient' => 'from-teal-500 to-cyan-500'];
                                            $lowerFacility = strtolower($facility);

                                            // Yoga-specific icon mappings
                                            if (str_contains($lowerFacility, 'mat') || str_contains($lowerFacility, 'yoga mat')) {
                                                $iconConfig = [
                                                    'path' => 'M4 4h16v16H4z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-purple-500 to-pink-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'meditation') || str_contains($lowerFacility, 'corner')) {
                                                $iconConfig = [
                                                    'path' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-indigo-500 to-purple-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'parking')) {
                                                $iconConfig = [
                                                    'path' => 'M5 11v8h14v-8H5zm7-6.5c-1.33 0-2.42.92-2.72 2.15h5.44c-.3-1.23-1.39-2.15-2.72-2.15zM12 3c2.21 0 4 1.79 4 4h2c0-3.31-2.69-6-6-6S6 3.69 6 7h2c0-2.21 1.79-4 4-4zM3 9h18v12H3z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-blue-500 to-indigo-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'changing') || str_contains($lowerFacility, 'locker')) {
                                                $iconConfig = [
                                                    'path' => 'M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-gray-600 to-gray-800'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'ac') || str_contains($lowerFacility, 'air')) {
                                                $iconConfig = [
                                                    'path' => 'M22 11h-4.17l3.24-3.24-1.41-1.42L15 11h-2V9l4.66-4.66-1.42-1.41L13 6.17V2h-2v4.17L7.76 2.93 6.34 4.34 11 9v2H9L4.34 6.34 2.93 7.76 6.17 11H2v2h4.17l-3.24 3.24 1.41 1.42L9 13h2v2l-4.66 4.66 1.42 1.41L11 17.83V22h2v-4.17l3.24 3.24 1.42-1.41L13 15v-2h2l4.66 4.66 1.41-1.42L17.83 13H22z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-cyan-500 to-blue-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'shower') || str_contains($lowerFacility, 'bathroom')) {
                                                $iconConfig = [
                                                    'path' => 'M9 17c0 .55-.45 1-1 1s-1-.45-1-1 .45-1 1-1 1 .45 1 1zm3-1c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm4 0c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm3-4v2H5v-2c0-.55.45-1 1-1h12c.55 0 1 .45 1 1zM9 3l.01 9h2V3.59C13.71 4.09 16 6.56 16 9.58V11h3V9.58C19 5.09 15.52 2 11 2c-.55 0-1 .45-1 1z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-blue-500 to-cyan-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'tea') || str_contains($lowerFacility, 'herbal') || str_contains($lowerFacility, 'drink')) {
                                                $iconConfig = [
                                                    'path' => 'M20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.11 0 2-.9 2-2V5c0-1.11-.89-2-2-2zm0 5h-2V5h2v3zM4 19h16v2H4z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-green-500 to-teal-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'props') || str_contains($lowerFacility, 'equipment')) {
                                                $iconConfig = [
                                                    'path' => 'M20 2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM8 20H4v-4h4v4zm0-6H4v-4h4v4zm0-6H4V4h4v4zm6 12h-4v-4h4v4zm0-6h-4v-4h4v4zm0-6h-4V4h4v4zm6 12h-4v-4h4v4zm0-6h-4v-4h4v4zm0-6h-4V4h4v4z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-orange-500 to-red-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'wifi') || str_contains($lowerFacility, 'internet')) {
                                                $iconConfig = [
                                                    'path' => 'M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-purple-500 to-pink-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'sound') || str_contains($lowerFacility, 'music')) {
                                                $iconConfig = [
                                                    'path' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-pink-500 to-rose-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'professional') || str_contains($lowerFacility, 'instructor')) {
                                                $iconConfig = [
                                                    'path' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-amber-500 to-orange-500'
                                                ];
                                            }
                                        @endphp
                                        <div class="group flex items-start space-x-4 p-5 rounded-2xl bg-white border-2 border-teal-100 hover:border-teal-300 hover:shadow-lg transition-all duration-300">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-gradient-to-br {{ $iconConfig['gradient'] }} rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="{{ $iconConfig['viewBox'] }}">
                                                        <path d="{{ $iconConfig['path'] }}"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">{{ is_array($facility) ? $facility['title'] : $facility }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">Available for all participants</p>
                                            </div>
                                            <svg class="w-5 h-5 text-teal-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Opening Hours -->
                        @if ($yoga->detailConfig && $yoga->detailConfig->show_opening_hours !== false)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Opening Hours</h2>

                                @php
                                    $scheduleArray = [];
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                    foreach ($days as $day) {
                                        $hours = 'Closed';
                                        if (isset($yoga->waktuBuka[$day]) && !empty($yoga->waktuBuka[$day])) {
                                            $hours = $yoga->waktuBuka[$day];
                                        }
                                        $scheduleArray[] = ['day' => $day, 'hours' => $hours];
                                    }
                                @endphp

                                <!-- First Row - 3 cards -->
                                <div class="grid grid-cols-3 gap-4 mb-4">
                                    @for ($i = 0; $i < 3; $i++)
                                        @if (isset($scheduleArray[$i]))
                                            <div class="bg-gray-100 rounded-lg p-4 hover:bg-gray-200 transition-colors">
                                                <div class="font-medium text-gray-800 mb-1">
                                                    {{ $scheduleArray[$i]['day'] }}</div>
                                                <div class="text-sm text-gray-600">{{ $scheduleArray[$i]['hours'] }}
                                                </div>
                                            </div>
                                        @endif
                                    @endfor
                                </div>

                                <!-- Second Row - 3 cards -->
                                <div class="grid grid-cols-3 gap-4 mb-4">
                                    @for ($i = 3; $i < 6; $i++)
                                        @if (isset($scheduleArray[$i]))
                                            <div class="bg-gray-100 rounded-lg p-4 hover:bg-gray-200 transition-colors">
                                                <div class="font-medium text-gray-800 mb-1">
                                                    {{ $scheduleArray[$i]['day'] }}</div>
                                                <div class="text-sm text-gray-600">{{ $scheduleArray[$i]['hours'] }}
                                                </div>
                                            </div>
                                        @endif
                                    @endfor
                                </div>

                                <!-- Third Row - 1 card -->
                                <div class="grid grid-cols-3 gap-4">
                                    @if (isset($scheduleArray[6]))
                                        <div class="bg-gray-100 rounded-lg p-4 hover:bg-gray-200 transition-colors">
                                            <div class="font-medium text-gray-800 mb-1">{{ $scheduleArray[6]['day'] }}
                                            </div>
                                            <div class="text-sm text-gray-600">{{ $scheduleArray[6]['hours'] }}</div>
                                        </div>
                                    @endif
                                    <!-- Empty spaces to maintain grid alignment -->
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Side - Booking Card -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">

                        <!-- Booking Policy Card -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <!-- Policy Header -->
                            <div class="text-center mb-6">
                                <div class="bg-blue-50 rounded-2xl p-6 mb-6">
                                    <div class="flex items-center justify-center mb-4">
                                        <div class="bg-white p-3 rounded-xl shadow-sm">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-xl mb-2">BOOKING POLICY</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $yoga->detailConfig->booking_policy_subtitle ?? 'FIND YOUR INNER PEACE' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Booking Button -->
                            <button
                                class="bookingBtn w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-4 px-6 rounded-xl transition duration-300 transform hover:scale-105 text-lg"
                                data-yoga-id="{{ $yoga->id_yoga }}">
                                Booking Online
                            </button>
                        </div>

                        <!-- Contact Person Card -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Contact Person</h3>
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-lg">
                                        {{ $yoga->detailConfig->contact_person_name ?? 'Customer Service' }}
                                    </p>
                                    <p class="text-gray-600">
                                        {{ $yoga->noHP ?? ($yoga->detailConfig->contact_person_phone ?? 'N/A') }}
                                    </p>
                                </div>
                            </div>
                            @php
                                $contactPhone = $yoga->noHP ?? ($yoga->detailConfig->contact_person_phone ?? null);
                            @endphp
                            @if ($contactPhone)
                                <a href="tel:{{ $contactPhone }}"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-xl transition duration-300 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    Call Now
                                </a>
                            @endif
                        </div>

                        <!-- Location Card -->
                        @if ($yoga->detailConfig && $yoga->detailConfig->show_location_map !== false)
                            <div class="bg-white rounded-2xl shadow-lg p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Location</h3>
                                @if ($yoga->maps)
                                    <div class="rounded-xl overflow-hidden h-48">
                                        <div id="map-{{ $yoga->id_yoga }}"
                                            class="w-full h-full bg-gray-100 rounded-lg"></div>
                                    </div>
                                @else
                                    <div class="bg-gray-100 rounded-xl h-48 flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <p class="text-gray-600 text-sm">
                                                {{ $yoga->alamat ?? 'Location not available' }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Customer Testimonials Section --}}
            <div class="mt-16 mb-0 pb-20">
                <!-- Section Header -->
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Customer Testimonials</h2>
                    <p class="text-gray-600 text-lg">See what our yoga students have to say about their experience</p>
                </div>

                <!-- Testimonials Grid -->
                @if ($testimonials && $testimonials->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($testimonials as $testimonial)
                        <div
                            class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300 border border-teal-100">
                            <!-- Rating Stars -->
                            <div class="flex items-center mb-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $testimonial->rating)
                                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    @endif
                                @endfor
                            </div>

                            <!-- Comment -->
                            <p class="text-gray-700 mb-4 line-clamp-4">"{{ $testimonial->comment }}"</p>

                            <!-- Service Badge (if applicable) -->
                            @if ($testimonial->service)
                                <div class="mb-4">
                                    <span
                                        class="inline-block bg-gradient-to-r from-purple-100 to-orange-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-om mr-1"></i> {{ $testimonial->service }}
                                    </span>
                                </div>
                            @endif

                            <!-- Customer Info -->
                            <div class="flex items-center pt-4 border-t border-gray-100">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-purple-400 to-orange-400 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $testimonial->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $testimonial->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-2xl shadow-lg">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <p class="text-gray-500 text-lg">No testimonials yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="yogaBookingModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Booking Yoga Class</h3>
                    <button onclick="closeYogaBookingModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Booking Form -->
                <form id="yogaBookingForm" class="space-y-6">
                    <input type="hidden" name="yoga_id" value="{{ $yoga->id_yoga }}">

                    <!-- Booking Type Selection (Venue only for Yoga) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Booking Type</label>
                        <div class="grid grid-cols-1 gap-3">
                            <div class="border-2 border-blue-500 rounded-lg p-4 bg-blue-50">
                                <input type="radio" name="booking_type" value="venue" checked class="hidden">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 mr-3">
                                        <div
                                            class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-blue-700">Visit Yoga Studio</h4>
                                        <p class="text-sm text-blue-600">Join class at our yoga location</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Choose Class</label>
                        <select name="service_id" id="serviceSelect" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a class...</option>
                            @php
                                $bookableServices = [];
                                if ($yoga->yogaServices && $yoga->yogaServices->count() > 0) {
                                    foreach ($yoga->yogaServices->where('is_active', true) as $service) {
                                        $bookableServices[] = [
                                            'id' => $service->id,
                                            'title' => $service->name,
                                            'price' => $service->price,
                                            'duration' => $service->duration,
                                            'category' => $service->category,
                                        ];
                                    }
                                } else {
                                    // Fallback services
                                    $bookableServices = [
                                        [
                                            'id' => null,
                                            'title' => 'Hatha Yoga Basic',
                                            'price' => 150000,
                                            'duration' => '60 minutes',
                                            'category' => 'Beginner',
                                        ],
                                        [
                                            'id' => null,
                                            'title' => 'Vinyasa Flow',
                                            'price' => 200000,
                                            'duration' => '75 minutes',
                                            'category' => 'Intermediate',
                                        ],
                                        [
                                            'id' => null,
                                            'title' => 'Yin Yoga Restorative',
                                            'price' => 175000,
                                            'duration' => '90 minutes',
                                            'category' => 'Relaxation',
                                        ],
                                    ];
                                }
                            @endphp
                            @foreach ($bookableServices as $service)
                                <option value="{{ $service['id'] }}" data-price="{{ $service['price'] }}"
                                    data-title="{{ $service['title'] }}"
                                    data-category="{{ $service['category'] ?? 'General' }}">
                                    {{ $service['title'] }} - Rp {{ number_format($service['price'], 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Class Type (hidden field based on selected service) -->
                    <input type="hidden" name="class_type" id="classTypeBooking" value="General">

                    <!-- Total Amount (hidden field calculated from selected service) -->
                    <input type="hidden" name="total_amount" id="totalAmount" value="0">

                    <!-- Date and Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" name="booking_date" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                            <input type="time" name="booking_time" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="customer_name" required placeholder="Enter your full name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="customer_phone" required
                                placeholder="Enter your phone number"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="customer_email" required placeholder="Enter your email address"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Special Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Special Notes (Optional)</label>
                        <textarea name="notes" rows="3" placeholder="Any special requests or notes..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeYogaBookingModal()"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Book & Pay Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Check if user is authenticated
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

        document.addEventListener('DOMContentLoaded', function() {
            // Image gallery functionality
            const thumbnails = document.querySelectorAll('.thumbnail');
            const mainImage = document.getElementById('mainImage');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Remove active state from all thumbnails
                    thumbnails.forEach(thumb => {
                        thumb.classList.remove('border-blue-500', 'opacity-100');
                        thumb.classList.add('border-gray-200', 'opacity-70');
                    });

                    // Add active state to clicked thumbnail
                    this.classList.remove('border-gray-200', 'opacity-70');
                    this.classList.add('border-blue-500', 'opacity-100');

                    // Update main image
                    mainImage.src = this.src;
                });
            });

            // Maps functionality for yoga
            @if (isset($yoga))
                try {
                    const mapContainer = document.getElementById('map-{{ $yoga->id_yoga }}');
                    if (mapContainer) {
                        const iframe = document.createElement('iframe');
                        iframe.src = "{{ $yoga->maps }}";
                        iframe.width = "100%";
                        iframe.height = "100%";
                        iframe.style.border = "none";
                        iframe.allowFullscreen = true;
                        iframe.loading = "lazy";
                        iframe.referrerPolicy = "no-referrer-when-downgrade";

                        iframe.onerror = function() {
                            createFallbackLink(mapContainer, "{{ $yoga->maps }}");
                        };

                        mapContainer.appendChild(iframe);
                    }
                } catch (e) {
                    console.error("Error creating map:", e);
                }
            @endif

            function createFallbackLink(container, mapUrl) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full">
                        <p class="text-gray-600 mb-3">Peta tidak dapat dimuat secara langsung</p>
                        <a href="${mapUrl}" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buka di Google Maps
                        </a>
                    </div>
                `;
            }

            // Booking modal functionality
            const bookingBtn = document.querySelector('.bookingBtn');
            const yogaBookingModal = document.getElementById('yogaBookingModal');
            const yogaBookingForm = document.getElementById('yogaBookingForm');
            const serviceSelect = document.getElementById('serviceSelect');
            const classTypeBooking = document.getElementById('classTypeBooking');
            const totalAmountField = document.getElementById('totalAmount');

            if (bookingBtn) {
                bookingBtn.addEventListener('click', function() {
                    // Check if user is logged in
                    if (!isAuthenticated) {
                        Swal.fire({
                            title: 'Login Diperlukan',
                            text: 'Anda harus login terlebih dahulu untuk melakukan booking.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Login Sekarang',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#3B82F6',
                            cancelButtonColor: '#6B7280'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect to login page with return URL
                                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                            }
                        });
                        return;
                    }

                    openYogaBookingModal();
                });
            }

            // Handle service selection changes
            if (serviceSelect) {
                serviceSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value && selectedOption.dataset.price) {
                        const price = parseInt(selectedOption.dataset.price);
                        const category = selectedOption.dataset.category || 'General';
                        const adminFee = 5000;
                        const totalAmount = price + adminFee;

                        // Update hidden fields
                        classTypeBooking.value = category;
                        totalAmountField.value = totalAmount;
                    } else {
                        // Reset hidden fields
                        classTypeBooking.value = 'General';
                        totalAmountField.value = '0';
                    }
                });
            }

            function openYogaBookingModal() {
                yogaBookingModal.classList.remove('hidden');
                // Set minimum date to today
                const dateInput = yogaBookingForm.querySelector('input[name="booking_date"]');
                if (dateInput) {
                    const today = new Date().toISOString().split('T')[0];
                    dateInput.setAttribute('min', today);
                }
            }

            window.closeYogaBookingModal = function() {
                yogaBookingModal.classList.add('hidden');
                yogaBookingForm.reset();
            }

            // Form submission
            if (yogaBookingForm) {
                yogaBookingForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validate service selection
                    const serviceSelect = document.getElementById('serviceSelect');
                    if (!serviceSelect.value || serviceSelect.value === 'null') {
                        Swal.fire('Error', 'Please select a yoga class.', 'error');
                        return;
                    }

                    // Validate total amount
                    const totalAmount = document.getElementById('totalAmount').value;
                    if (!totalAmount || totalAmount == '0') {
                        Swal.fire('Error', 'Please select a valid yoga class.', 'error');
                        return;
                    }

                    // Prepare form data
                    const formData = new FormData(yogaBookingForm);
                    const data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    // Show loading state
                    const submitBtn = yogaBookingForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML =
                        '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing...';
                    submitBtn.disabled = true;

                    // Log the data being sent
                    console.log('[YOGA BOOKING] Sending booking data:', data);

                    // Submit booking request
                    fetch('/api/create-yoga-payment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify(data)
                        })
                        .then(async response => {
                            console.log('[YOGA BOOKING] Response status:', response.status);
                            console.log('[YOGA BOOKING] Response OK:', response.ok);

                            // Handle authentication error (401)
                            if (response.status === 401) {
                                const errorData = await response.json();
                                console.log('[YOGA BOOKING] Authentication required:', errorData);

                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;

                                // Show login prompt and redirect to login
                                Swal.fire({
                                    title: 'Login Required',
                                    text: errorData.message ||
                                        'You must be logged in to make a booking. Please login first.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Login Now',
                                    cancelButtonText: 'Cancel',
                                    confirmButtonColor: '#3B82F6',
                                    cancelButtonColor: '#6B7280'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redirect to login page
                                        window.location.href = '/login';
                                    }
                                });

                                return null; // Return null to skip next then
                            }

                            // Handle validation errors (422)
                            if (response.status === 422) {
                                const errorData = await response.json();
                                console.log('[YOGA BOOKING] Validation errors:', errorData);

                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;

                                const errorMessages = errorData.errors
                                    ? Object.values(errorData.errors).flat().join('\n')
                                    : errorData.message || 'Validation failed';

                                Swal.fire('Validation Error', errorMessages, 'error');
                                return null;
                            }

                            // Handle other HTTP errors
                            if (!response.ok) {
                                const errorText = await response.text();
                                console.log('[YOGA BOOKING] Server error response:', errorText);

                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;

                                Swal.fire('Server Error', `HTTP ${response.status}: ${response.statusText}`, 'error');
                                return null;
                            }

                            return response.json();
                        })
                        .then(result => {
                            if (!result) return; // Skip if we already handled auth error

                            console.log('[YOGA BOOKING] Response data:', result);

                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;

                            if (result.success && result.payment_token && result.booking_id) {
                                console.log('[YOGA BOOKING] Payment successful, loading Midtrans...');
                                closeYogaBookingModal();
                                // Show payment processing with Midtrans
                                loadMidtransSnap(result.payment_token, result.booking_id);
                            } else {
                                console.log('[YOGA BOOKING] Booking failed:', result.message);
                                // Only show error message if it's not an authentication error
                                if (result.message && !result.message.includes('logged in')) {
                                    Swal.fire('Error', result.message ||
                                        'Booking failed. Please try again.', 'error');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('[YOGA BOOKING] Error:', error);
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                            Swal.fire('Error', 'A server error occurred. Please try again.', 'error');
                        });
                });
            }

            function loadMidtransSnap(token, bookingId) {
                if (!window.snap) {
                    Swal.fire('Error', 'Midtrans Snap is not loaded. Please refresh the page.', 'error');
                    return;
                }

                window.snap.pay(token, {
                    onSuccess: function(result) {
                        Swal.fire('Payment Successful', 'Your yoga booking has been paid!', 'success')
                            .then(() => window.location.reload());
                    },
                    onPending: function(result) {
                        Swal.fire('Payment Pending', 'Your payment is being processed.', 'info');
                    },
                    onError: function(result) {
                        Swal.fire('Error', 'Payment failed. Please try again.', 'error');
                    },
                    onClose: function() {
                        Swal.fire('Cancelled', 'You closed the payment without completing it.',
                            'warning');
                    }
                });
            }
        });
    </script>
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</x-app-layout>
