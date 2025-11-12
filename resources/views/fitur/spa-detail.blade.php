<x-app-layout>
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Prevent browser caching for booking page -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <style>
        .spa-gradient {
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

    <!-- Custom CSS from Admin -->
    @if (isset($spa->detailConfig) && $spa->detailConfig && $spa->detailConfig->custom_css)
        <style>
            {!! $spa->detailConfig->custom_css !!}
        </style>
    @endif

    {{-- Modern SPA Detail Section --}}
    <div class="spa-gradient pb-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Side - Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Hero Image Gallery -->
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                        <!-- Main Image -->
                        <div class="relative">
                            @php
                                // Simplified image handling like gym-detail
                                $galleryImages = [];
                                if (isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->gallery_images) {
                                    if (is_array($spa->spaDetail->gallery_images)) {
                                        $galleryImages = $spa->spaDetail->gallery_images;
                                    } elseif (is_string($spa->spaDetail->gallery_images)) {
                                        $decoded = json_decode($spa->spaDetail->gallery_images, true);
                                        if ($decoded) {
                                            $galleryImages = $decoded;
                                        }
                                    }
                                }

                                // Fallback with 5 images like gym-detail
                                if (empty($galleryImages)) {
                                    $defaultImage = $spa->image ?? 'images/default-spa.jpg';
                                    $galleryImages = [
                                        $defaultImage,
                                        $defaultImage,
                                        $defaultImage,
                                        $defaultImage,
                                        $defaultImage,
                                    ];
                                } else {
                                    // Ensure we have exactly 5 images
                                    while (count($galleryImages) < 5) {
                                        $galleryImages[] = $galleryImages[0] ?? 'images/default-spa.jpg';
                                    }
                                    $galleryImages = array_slice($galleryImages, 0, 5);
                                }

                                $mainImage = $galleryImages[0];
                            @endphp
                            <img id="mainImage" src="{{ asset($mainImage) }}" alt="{{ $spa->nama ?? 'Spa' }}"
                                class="w-full h-96 object-cover transition-opacity duration-300">
                        </div>

                        <!-- Thumbnail Images - CHANGED TO 5 COLUMNS LIKE GYM-DETAIL -->
                        <div class="p-4">
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($galleryImages as $index => $image)
                                    <img src="{{ asset($image) }}" alt="Image {{ $index + 1 }}"
                                        class="thumbnail w-full h-20 object-cover rounded-xl cursor-pointer border-2 transition-all duration-200 {{ $index === 0 ? 'border-emerald-500 opacity-100' : 'border-gray-200 opacity-70 hover:opacity-100 hover:border-emerald-300' }}">
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Spa Information -->
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                        <!-- Title and Location -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-3">
                                {{ isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->hero_title ? $spa->spaDetail->hero_title : $spa->nama ?? 'Spa Name' }}
                            </h1>

                            <!-- Rating Stars -->
                            <div class="flex items-center mb-3">
                                <div class="flex items-center">
                                    @php
                                        $averageRating = $spa->average_rating;
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
                                                <linearGradient id="halfGrad">
                                                    <stop offset="50%" stop-color="#FBBF24"/>
                                                    <stop offset="50%" stop-color="#D1D5DB"/>
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#halfGrad)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
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
                                    ({{ $spa->testimonials_count }} {{ $spa->testimonials_count == 1 ? 'review' : 'reviews' }})
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
                                <span>{{ $spa->alamat ?? 'Location not available' }}</span>
                            </div>
                        </div>

                        <!-- Services Section (from database/seeder) -->
                        @if ($spa->services && count($spa->services) > 0)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Services</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($spa->services as $service)
                                        <div class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                                            <div class="flex-shrink-0">
                                                @if (isset($service['image']) && $service['image'])
                                                    <img src="{{ asset($service['image']) }}"
                                                        alt="{{ $service['name'] }}"
                                                        class="w-16 h-16 rounded-full object-cover border-2 border-emerald-200">
                                                @else
                                                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">{{ $service['name'] }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">{{ $service['description'] }}</p>
                                                @if (isset($service['price']))
                                                    <p class="text-sm font-bold text-emerald-600 mt-2">
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
                            if ($spa->spaDetail && $spa->spaDetail->facilities) {
                                if (is_array($spa->spaDetail->facilities)) {
                                    // Filter to ensure we only have string values
                                    $facilities = array_filter($spa->spaDetail->facilities, function($item) {
                                        return is_string($item);
                                    });
                                } elseif (is_string($spa->spaDetail->facilities)) {
                                    $decoded = json_decode($spa->spaDetail->facilities, true);
                                    if ($decoded && is_array($decoded)) {
                                        // Filter to ensure we only have string values
                                        $facilities = array_filter($decoded, function($item) {
                                            return is_string($item);
                                        });
                                    }
                                }
                            }
                        @endphp

                        @if (!empty($facilities))
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                    <svg class="w-7 h-7 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                    Facilities & Amenities
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($facilities as $facility)
                                        @php
                                            // Enhanced icon mapping with more variety
                                            $iconConfig = ['path' => 'M5 13l4 4L19 7', 'viewBox' => '0 0 24 24', 'gradient' => 'from-emerald-500 to-teal-500'];
                                            $lowerFacility = strtolower($facility);

                                            if (str_contains($lowerFacility, 'parking') || str_contains($lowerFacility, 'park')) {
                                                $iconConfig = [
                                                    'path' => 'M5 11v8h14v-8H5zm7-6.5c-1.33 0-2.42.92-2.72 2.15h5.44c-.3-1.23-1.39-2.15-2.72-2.15zM12 3c2.21 0 4 1.79 4 4h2c0-3.31-2.69-6-6-6S6 3.69 6 7h2c0-2.21 1.79-4 4-4zM3 9h18v12H3z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-blue-500 to-indigo-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'wifi') || str_contains($lowerFacility, 'internet')) {
                                                $iconConfig = [
                                                    'path' => 'M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-purple-500 to-pink-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'lounge') || str_contains($lowerFacility, 'relaxation') || str_contains($lowerFacility, 'waiting')) {
                                                $iconConfig = [
                                                    'path' => 'M4 11v8h16v-8H4zm12-6H8C6.34 5 5 6.34 5 8v2h14V8c0-1.66-1.34-3-3-3zm3 4H5V8c0-.55.45-1 1-1h12c.55 0 1 .45 1 1v1z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-orange-500 to-red-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'room') || str_contains($lowerFacility, 'private') || str_contains($lowerFacility, 'treatment')) {
                                                $iconConfig = [
                                                    'path' => 'M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-teal-500 to-cyan-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'steam') || str_contains($lowerFacility, 'sauna')) {
                                                $iconConfig = [
                                                    'path' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-5l1.5-4.5L12 12l3.5-1.5L17 15l-4.5-1.5L10 15zm2-6.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5S11.33 10 10.5 10 9 9.33 9 8.5z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-red-500 to-orange-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'ac') || str_contains($lowerFacility, 'air') || str_contains($lowerFacility, 'conditioner')) {
                                                $iconConfig = [
                                                    'path' => 'M22 11h-4.17l3.24-3.24-1.41-1.42L15 11h-2V9l4.66-4.66-1.42-1.41L13 6.17V2h-2v4.17L7.76 2.93 6.34 4.34 11 9v2H9L4.34 6.34 2.93 7.76 6.17 11H2v2h4.17l-3.24 3.24 1.41 1.42L9 13h2v2l-4.66 4.66 1.42 1.41L11 17.83V22h2v-4.17l3.24 3.24 1.42-1.41L13 15v-2h2l4.66 4.66 1.41-1.42L17.83 13H22z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-cyan-500 to-blue-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'couple')) {
                                                $iconConfig = [
                                                    'path' => 'M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-pink-500 to-rose-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'tea') || str_contains($lowerFacility, 'drink') || str_contains($lowerFacility, 'beverage') || str_contains($lowerFacility, 'coffee') || str_contains($lowerFacility, 'cafe')) {
                                                $iconConfig = [
                                                    'path' => 'M20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.11 0 2-.9 2-2V5c0-1.11-.89-2-2-2zm0 5h-2V5h2v3zM4 19h16v2H4z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-amber-500 to-yellow-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'shower') || str_contains($lowerFacility, 'bathroom')) {
                                                $iconConfig = [
                                                    'path' => 'M9 17c0 .55-.45 1-1 1s-1-.45-1-1 .45-1 1-1 1 .45 1 1zm3-1c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm4 0c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm3-4v2H5v-2c0-.55.45-1 1-1h12c.55 0 1 .45 1 1zM9 3l.01 9h2V3.59C13.71 4.09 16 6.56 16 9.58V11h3V9.58C19 5.09 15.52 2 11 2c-.55 0-1 .45-1 1z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-blue-500 to-cyan-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'towel') || str_contains($lowerFacility, 'linen')) {
                                                $iconConfig = [
                                                    'path' => 'M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-6 2.5c0 .83-.67 1.5-1.5 1.5S11 5.33 11 4.5 11.67 3 12.5 3s1.5.67 1.5 1.5zM20 16H8V6h12v10zm-8 4H6c-1.1 0-2-.9-2-2V8c0-.55-.45-1-1-1s-1 .45-1 1v10c0 2.21 1.79 4 4 4h6c.55 0 1-.45 1-1s-.45-1-1-1z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-indigo-500 to-purple-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'lock') || str_contains($lowerFacility, 'secure') || str_contains($lowerFacility, 'safe')) {
                                                $iconConfig = [
                                                    'path' => 'M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-gray-600 to-gray-800'
                                                ];
                                            }
                                        @endphp
                                        <div class="group flex items-start space-x-4 p-5 rounded-2xl bg-white border-2 border-emerald-100 hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-gradient-to-br {{ $iconConfig['gradient'] }} rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="{{ $iconConfig['viewBox'] }}">
                                                        <path d="{{ $iconConfig['path'] }}"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">{{ $facility }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">Available for all guests</p>
                                            </div>
                                            <svg class="w-5 h-5 text-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Description Section -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                            <div class="prose text-gray-600 text-lg leading-relaxed">
                                @php
                                    $description =
                                        'Memorable Spa is a professional and affordable on-call massage service in Yogyakarta. We understand how important relaxation and health are in daily life, and we are committed to providing an exceptional massage experience right in the comfort of your home or office.';

                                    if (isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->about_spa) {
                                        $description = $spa->spaDetail->about_spa;
                                    }
                                @endphp
                                <p>{{ $description }}</p>
                            </div>
                        </div>

                        <!-- Opening Work Section -->
                        @if (!isset($spa->spaDetail) || $spa->spaDetail->show_opening_hours !== false)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Opening Work</h2>

                                <!-- First Row - 3 cards -->
                                <div class="grid grid-cols-3 gap-4 mb-4">
                                    @php
                                        // Get waktuBuka from spa if it exists
                                        $waktuBuka = [];
                                        if (isset($spa->waktuBuka)) {
                                            if (is_string($spa->waktuBuka)) {
                                                $waktuBuka = json_decode($spa->waktuBuka, true) ?? [];
                                            } else {
                                                $waktuBuka = $spa->waktuBuka;
                                            }
                                        }

                                        // Default schedule if waktuBuka is empty
                                        if (empty($waktuBuka)) {
                                            $waktuBuka = [
                                                'Senin' => '08:00 AM - 10:00 PM',
                                                'Selasa' => '08:00 AM - 10:00 PM',
                                                'Rabu' => '08:00 AM - 10:00 PM',
                                                'Kamis' => '08:00 AM - 10:00 PM',
                                                'Jumat' => '08:00 AM - 10:00 PM',
                                                'Sabtu' => '08:00 AM - 10:00 PM',
                                                'Minggu' => '08:00 AM - 10:00 PM',
                                            ];
                                        }

                                        // English day names mapping
                                        $dayMapping = [
                                            'Senin' => 'Monday',
                                            'Selasa' => 'Tuesday',
                                            'Rabu' => 'Wednesday',
                                            'Kamis' => 'Thursday',
                                            'Jumat' => 'Friday',
                                            'Sabtu' => 'Saturday',
                                            'Minggu' => 'Sunday',
                                        ];

                                        $scheduleArray = [];
                                        foreach ($waktuBuka as $day => $hours) {
                                            $scheduleArray[] = [
                                                'day' => $dayMapping[$day] ?? $day,
                                                'hours' => $hours,
                                            ];
                                        }
                                    @endphp

                                    @for ($i = 0; $i < 3; $i++)
                                        @if (isset($scheduleArray[$i]))
                                            <div class="bg-emerald-50 rounded-lg p-4 hover:bg-emerald-100 transition-colors">
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
                                            <div class="bg-emerald-50 rounded-lg p-4 hover:bg-emerald-100 transition-colors">
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
                                        <div class="bg-emerald-50 rounded-lg p-4 hover:bg-emerald-100 transition-colors">
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
                        @if (!isset($spa->spaDetail) || $spa->spaDetail->show_booking_policy !== false)
                            <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-100">
                                <!-- Policy Header with Clipboard Icon -->
                                <div class="text-center mb-6">
                                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6 mb-6 border border-emerald-100">
                                        <div class="flex items-center justify-center mb-4">
                                            <div class="bg-white p-4 rounded-2xl shadow-md">
                                                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <h3 class="font-black text-gray-900 text-xl mb-2">
                                            {{ isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->booking_policy_title ? $spa->spaDetail->booking_policy_title : 'BOOKING POLICY' }}
                                        </h3>
                                        <p class="text-sm font-medium text-emerald-600">
                                            {{ isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->booking_policy_subtitle ? $spa->spaDetail->booking_policy_subtitle : 'YOUR WELLNESS PLANS' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Booking Button -->
                                <button
                                    class="bookingBtn w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg text-lg"
                                    data-spa-id="{{ $spa->id_spa ?? '' }}">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Booking Online
                                    </span>
                                </button>
                            </div>
                        @endif

                        <!-- Contact Person Card -->
                        <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Contact Person</h3>
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-lg">
                                        {{ isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->contact_person_name ? $spa->spaDetail->contact_person_name : 'Contact Person' }}
                                    </p>
                                    <p class="text-gray-600">
                                        {{ isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->contact_person_phone ? $spa->spaDetail->contact_person_phone : $spa->noHP ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Location Card -->
                        @if (!isset($spa->spaDetail) || $spa->spaDetail->show_location_map !== false)
                            <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-100">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Location</h3>
                                @if ($spa->maps)
                                    <div class="rounded-xl overflow-hidden h-48">
                                        {!! $spa->maps !!}
                                    </div>
                                @else
                                    <div class="bg-emerald-50 rounded-xl h-48 flex items-center justify-center">
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
                                                {{ $spa->alamat ?? 'Location not available' }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Testimonials Section -->
            <div class="mt-16 mb-0 pb-20">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-black text-gray-900 mb-4">What Our Clients Say</h2>
                    <p class="text-gray-600 text-lg">Real experiences from our valued customers</p>
                </div>

                {{-- Testimonials loaded from database via SpaController --}}
                @if ($testimonials && $testimonials->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($testimonials->take(3) as $testimonial)
                            <div class="bg-white rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-emerald-100">
                                <!-- Rating Stars -->
                                <div class="flex items-center mb-4">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $testimonial->rating)
                                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>

                                <!-- Comment -->
                                <p class="text-emerald-900 mb-4 leading-relaxed">
                                    "{{ $testimonial->comment }}"
                                </p>

                                <!-- Service Badge -->
                                @if ($testimonial->service)
                                    <div class="mb-4">
                                        <span class="inline-block bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full">
                                            {{ $testimonial->service }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Customer Info -->
                                <div class="flex items-center justify-between pt-4 border-t border-emerald-100">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-bold text-gray-900">{{ $testimonial->name }}</p>
                                            <p class="text-xs text-gray-600">{{ $testimonial->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="text-emerald-500">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-3xl">
                        <p class="text-gray-600">No testimonials available yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="spaBookingModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Booking Spa Service</h3>
                    <button onclick="closeSpaBookingModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Booking Form -->
                <form id="spaBookingForm" class="space-y-6">
                    <!-- Booking Type Selection -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Pilih Jenis Booking</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-white transition-colors">
                                <input type="radio" name="booking_type" value="venue"
                                    class="mr-3 text-emerald-600 focus:ring-emerald-500" checked>
                                <div>
                                    <div class="font-medium text-gray-900">Booking Tempat</div>
                                    <div class="text-sm text-gray-600">Datang ke lokasi spa</div>
                                </div>
                            </label>
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-white transition-colors">
                                <input type="radio" name="booking_type" value="terapis"
                                    class="mr-3 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <div class="font-medium text-gray-900">Booking Terapis</div>
                                    <div class="text-sm text-gray-600">Terapis datang ke alamat Anda</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-emerald-900 mb-2">Nama Lengkap *</label>
                            <input type="text" name="customer_name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-emerald-900 mb-2">Email *</label>
                            <input type="email" name="customer_email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-emerald-900 mb-2">No. HP *</label>
                            <input type="tel" name="customer_phone" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-emerald-900 mb-2">Jenis Layanan *</label>
                            <select name="service_type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="">Pilih Layanan</option>
                                @php
                                    // Get bookable services from spa-services management
                                    $bookableServices = [];

                                    if (
                                        isset($spa->spaServices) &&
                                        $spa->spaServices &&
                                        method_exists($spa->spaServices, 'count') &&
                                        $spa->spaServices->count() > 0
                                    ) {
                                        foreach ($spa->spaServices->where('is_active', true) as $service) {
                                            $bookableServices[] = [
                                                'id' => $service->id,
                                                'title' => $service->name,
                                                'price' => $service->price,
                                                'duration' => $service->duration ?? 60,
                                                'category' => $service->category ?? 'Spa Service',
                                            ];
                                        }
                                    }

                                    // Fallback to default bookable services if empty
                                    if (empty($bookableServices)) {
                                        $bookableServices = [
                                            [
                                                'id' => null,
                                                'title' => 'Traditional Massage',
                                                'price' => 150000,
                                                'duration' => 60,
                                                'category' => 'Traditional',
                                            ],
                                            [
                                                'id' => null,
                                                'title' => 'Deep Tissue Massage',
                                                'price' => 200000,
                                                'duration' => 90,
                                                'category' => 'Therapeutic',
                                            ],
                                            [
                                                'id' => null,
                                                'title' => 'Hot Stone Massage',
                                                'price' => 250000,
                                                'duration' => 90,
                                                'category' => 'Premium',
                                            ],
                                            [
                                                'id' => null,
                                                'title' => 'Aromatherapy Massage',
                                                'price' => 180000,
                                                'duration' => 75,
                                                'category' => 'Relaxation',
                                            ],
                                        ];
                                    }
                                @endphp
                                @foreach ($bookableServices as $service)
                                    <option value="{{ $service['title'] }}" data-price="{{ $service['price'] }}">
                                        {{ $service['title'] }} - Rp
                                        {{ number_format($service['price'], 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-emerald-900 mb-2">Tanggal Booking *</label>
                            <input type="date" name="booking_date" id="booking_date" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-emerald-900 mb-2">Waktu *</label>
                            <input type="time" name="booking_time" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                    </div>

                    <div id="serviceAddressField" style="display: none;">
                        <label class="block text-sm font-medium text-emerald-900 mb-2">
                            Alamat Layanan *
                            <span class="text-sm text-gray-600">(Untuk booking terapis)</span>
                        </label>
                        <textarea name="service_address" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            placeholder="Masukkan alamat lengkap untuk layanan spa di tempat Anda..."></textarea>
                    </div>

                    <div id="venueAddressField">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">📍 Lokasi Spa</h4>
                            <p class="text-emerald-900">{{ $spa->alamat ?? 'Alamat spa akan ditampilkan di sini' }}</p>
                            <p class="text-sm text-gray-600 mt-2">Anda akan datang ke lokasi spa sesuai jadwal yang
                                dipilih.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-emerald-900 mb-2">Catatan Tambahan</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            placeholder="Catatan khusus untuk terapis (opsional)..."></textarea>
                    </div>

                    <!-- Price Summary -->
                    <div class="bg-white rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Ringkasan Pembayaran</h4>

                        <!-- Voucher Section -->
                        <div class="mb-4 p-3 bg-white rounded-lg border border-dashed border-gray-300">
                            <label class="block text-sm font-medium text-emerald-900 mb-2">Kode Voucher (Opsional)</label>
                            <div class="flex gap-2">
                                <input type="text" name="voucher_code" id="voucherCode"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm"
                                    placeholder="Masukkan kode voucher">
                                <button type="button" id="applyVoucherBtn"
                                    class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                                    Terapkan
                                </button>
                            </div>
                            <div id="voucherMessage" class="mt-2 text-sm"></div>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Harga Layanan:</span>
                                <span id="servicePrice">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Admin:</span>
                                <span id="adminFee">Rp 5.000</span>
                            </div>
                            <div id="voucherDiscount" class="flex justify-between text-green-600"
                                style="display: none;">
                                <span>Diskon Voucher:</span>
                                <span id="discountAmount">- Rp 0</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between font-semibold text-lg">
                                <span>Total:</span>
                                <span id="totalPrice">Rp 5.000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-emerald-600 text-white font-semibold py-4 px-6 rounded-lg hover:bg-emerald-700 transition duration-300 transform hover:scale-105">
                        Lanjutkan Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap Script -->
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        // Page Version: 2025-11-08-v2 - PLEASE HARD REFRESH IF YOU SEE ERRORS!
        console.log('%c[SPA BOOKING v2.0] Page loaded successfully', 'color: green; font-weight: bold; font-size: 14px');
        console.log('[INFO] If you see booking errors, please do CTRL+SHIFT+R (hard refresh)');

        // Global variables
        const bookableServices = @json($bookableServices ?? []);
        const spaData = @json($spa);
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        let appliedVoucher = null;
        let currentServicePrice = 0;
        const adminFee = 5000;

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing booking system...');
            console.log('Spa Data:', spaData);
            console.log('Bookable Services:', bookableServices);

            // Image gallery functionality - IMPROVED TO MATCH GYM-DETAIL
            const thumbnails = document.querySelectorAll('.thumbnail');
            const mainImage = document.getElementById('mainImage');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Remove active state from all thumbnails
                    thumbnails.forEach(thumb => {
                        thumb.classList.remove('border-emerald-500', 'opacity-100');
                        thumb.classList.add('border-gray-200', 'opacity-70');
                    });

                    // Add active state to clicked thumbnail
                    this.classList.remove('border-gray-200', 'opacity-70');
                    this.classList.add('border-emerald-500', 'opacity-100');

                    // Update main image with smooth transition
                    mainImage.style.opacity = '0.5';
                    setTimeout(() => {
                        mainImage.src = this.src;
                        mainImage.style.opacity = '1';
                    }, 150);
                });
            });

            // Booking functionality
            const bookingBtn = document.querySelector('.bookingBtn');
            const spaBookingModal = document.getElementById('spaBookingModal');
            const spaBookingForm = document.getElementById('spaBookingForm');
            const serviceSelect = document.querySelector('select[name="service_type"]');
            const servicePriceEl = document.getElementById('servicePrice');
            const adminFeeEl = document.getElementById('adminFee');
            const totalPriceEl = document.getElementById('totalPrice');
            const applyVoucherBtn = document.getElementById('applyVoucherBtn');
            const voucherCodeInput = document.getElementById('voucherCode');
            const voucherMessage = document.getElementById('voucherMessage');
            const voucherDiscountEl = document.getElementById('voucherDiscount');
            const discountAmountEl = document.getElementById('discountAmount');

            // Booking type handling
            const bookingTypeRadios = document.querySelectorAll('input[name="booking_type"]');
            const serviceAddressField = document.getElementById('serviceAddressField');
            const venueAddressField = document.getElementById('venueAddressField');
            const serviceAddressInput = document.querySelector('textarea[name="service_address"]');

            console.log('Booking button found:', bookingBtn);

            // Handle booking type change
            bookingTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'terapis') {
                        // Show address field for terapis booking
                        serviceAddressField.style.display = 'block';
                        venueAddressField.style.display = 'none';
                        serviceAddressInput.setAttribute('required', 'required');
                    } else {
                        // Hide address field for venue booking
                        serviceAddressField.style.display = 'none';
                        venueAddressField.style.display = 'block';
                        serviceAddressInput.removeAttribute('required');
                        serviceAddressInput.value = ''; // Clear the field
                    }
                });
            });

            if (bookingBtn) {
                bookingBtn.addEventListener('click', function() {
                    console.log('Booking button clicked');

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

                    openSpaBookingModal();
                });
            }

            // Update price when service is selected
            if (serviceSelect) {
                serviceSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    currentServicePrice = parseInt(selectedOption.dataset.price) || 0;
                    updatePriceDisplay();
                });
            }

            // Voucher functionality
            if (applyVoucherBtn && voucherCodeInput) {
                applyVoucherBtn.addEventListener('click', function() {
                    applyVoucher();
                });

                voucherCodeInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyVoucher();
                    }
                });
            }

            function updatePriceDisplay() {
                console.log('Updating price display, currentServicePrice:', currentServicePrice);
                console.log('Applied voucher:', appliedVoucher);

                servicePriceEl.textContent = 'Rp ' + formatNumber(currentServicePrice);
                adminFeeEl.textContent = 'Rp ' + formatNumber(adminFee);

                let total = currentServicePrice + adminFee;
                let discountAmount = 0;

                if (appliedVoucher) {
                    if (appliedVoucher.discount_type === 'percentage') {
                        discountAmount = Math.floor((currentServicePrice * appliedVoucher.discount_percentage) /
                            100);
                    } else if (appliedVoucher.discount_type === 'fixed') {
                        discountAmount = appliedVoucher.discount_amount;
                    }

                    // Ensure discount doesn't exceed service price
                    discountAmount = Math.min(discountAmount, currentServicePrice);
                    total -= discountAmount;

                    console.log('Discount amount calculated:', discountAmount);
                    console.log('Total after discount:', total);

                    voucherDiscountEl.style.display = 'flex';
                    discountAmountEl.textContent = '- Rp ' + formatNumber(discountAmount);

                    // Update discount text to include voucher code
                    voucherDiscountEl.querySelector('span:first-child').textContent =
                        `Diskon ${appliedVoucher.code}:`;
                } else {
                    voucherDiscountEl.style.display = 'none';
                }

                // Update total price with special styling if discounted
                const finalTotal = Math.max(total, 0);
                totalPriceEl.textContent = 'Rp ' + formatNumber(finalTotal);

                if (appliedVoucher && discountAmount > 0) {
                    totalPriceEl.classList.add('text-green-600', 'font-bold');
                    totalPriceEl.parentElement.classList.add('bg-green-50', 'px-2', 'py-1', 'rounded');
                } else {
                    totalPriceEl.classList.remove('text-green-600', 'font-bold');
                    totalPriceEl.parentElement.classList.remove('bg-green-50', 'px-2', 'py-1', 'rounded');
                }
            }

            async function applyVoucher() {
                const voucherCode = voucherCodeInput.value.trim();

                if (!voucherCode) {
                    showVoucherMessage('Masukkan kode voucher terlebih dahulu.', 'error');
                    return;
                }

                if (currentServicePrice === 0) {
                    showVoucherMessage('Pilih layanan terlebih dahulu.', 'error');
                    return;
                }

                try {
                    applyVoucherBtn.textContent = 'Memproses...';
                    applyVoucherBtn.disabled = true;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    const response = await fetch('/voucher/apply', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        },
                        body: JSON.stringify({
                            voucher_code: voucherCode,
                            service_amount: currentServicePrice,
                            service_type: 'spa'
                        })
                    });

                    const result = await response.json();
                    console.log('Voucher apply response:', result);

                    if (result.success) {
                        appliedVoucher = result.voucher;
                        console.log('Voucher applied successfully:', appliedVoucher);

                        // Calculate discount amount for display
                        let discountAmount = 0;
                        if (appliedVoucher.discount_type === 'percentage') {
                            discountAmount = Math.floor((currentServicePrice * appliedVoucher
                                .discount_percentage) / 100);
                        } else {
                            discountAmount = appliedVoucher.discount_amount;
                        }

                        const discountText = appliedVoucher.discount_type === 'percentage' ?
                            `${appliedVoucher.discount_percentage}%` :
                            `Rp ${formatNumber(appliedVoucher.discount_amount)}`;

                        showVoucherMessage(
                            `✅ Voucher ${appliedVoucher.code} berhasil diterapkan! Hemat ${discountText} (Rp ${formatNumber(discountAmount)})`,
                            'success');
                        voucherCodeInput.disabled = true;
                        applyVoucherBtn.textContent = '✅ Berhasil';
                        applyVoucherBtn.classList.add('bg-green-500', 'text-white');
                        applyVoucherBtn.classList.remove('bg-emerald-500', 'hover:bg-emerald-600');

                        updatePriceDisplay();
                    } else {
                        showVoucherMessage(result.message || 'Kode voucher tidak valid.', 'error');
                        resetVoucher();
                    }
                } catch (error) {
                    console.error('Error applying voucher:', error);
                    showVoucherMessage('Terjadi kesalahan saat memproses voucher.', 'error');
                    resetVoucher();
                } finally {
                    applyVoucherBtn.disabled = false;
                    if (applyVoucherBtn.textContent !== 'Berhasil') {
                        applyVoucherBtn.textContent = 'Terapkan';
                    }
                }
            }

            function showVoucherMessage(message, type) {
                voucherMessage.innerHTML = message;
                if (type === 'success') {
                    voucherMessage.className =
                        'mt-2 text-sm text-green-600 font-semibold bg-green-50 p-2 rounded-md border border-green-200';
                } else {
                    voucherMessage.className =
                        'mt-2 text-sm text-red-600 font-semibold bg-red-50 p-2 rounded-md border border-red-200';
                }
            }

            function resetVoucher() {
                appliedVoucher = null;
                voucherCodeInput.disabled = false;
                voucherCodeInput.value = '';
                voucherMessage.innerHTML = '';
                voucherMessage.className = '';
                applyVoucherBtn.textContent = 'Terapkan';
                applyVoucherBtn.classList.remove('bg-green-500', 'text-white');
                applyVoucherBtn.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
              serviceAddressInput.removeAttribute('required');
            }

            // Close modal when clicking outside
            spaBookingModal.addEventListener('click', function(e) {
                if (e.target === spaBookingModal) {
                    closeSpaBookingModal();
                }
            });

            // Handle form submission
            if (spaBookingForm) {
                spaBookingForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Form submitted');

                    // Validate form
                    if (!validateForm()) {
                        console.log('Form validation failed');
                        return;
                    }

                    const formData = new FormData(spaBookingForm);
                    const data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });
                    console.log('Form data:', data);

                    // Show loading state
                    const submitBtn = spaBookingForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Memproses...';
                    submitBtn.disabled = true;

                    // Process payment
                    processDirectPayment(data)
                        .then(response => {
                            console.log('Payment response:', response);
                            if (response.success) {
                                closeSpaBookingModal();
                                // Langsung ke Midtrans Snap
                                if (window.snap) {
                                    console.log('Opening Midtrans Snap with token:', response
                                        .payment_token);
                                    window.snap.pay(response.payment_token, {
                                        onSuccess: function(result) {
                                            console.log('Payment success:', result);
                                            const bookingType = document.querySelector(
                                                    'input[name="booking_type"]:checked')
                                                .value;
                                            const successMessage = bookingType ===
                                                'terapis' ?
                                                'Booking layanan spa Anda telah berhasil dibayar. Terapis akan segera menghubungi Anda untuk mengatur jadwal kunjungan.' :
                                                'Booking layanan spa Anda telah berhasil dibayar. Silakan datang ke lokasi spa sesuai jadwal yang telah dipilih.';

                                            Swal.fire({
                                                title: 'Pembayaran Berhasil!',
                                                text: successMessage,
                                                icon: 'success',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#10B981'
                                            }).then(() => {
                                                window.location.reload();
                                            });
                                        },
                                        onPending: function(result) {
                                            console.log('Payment pending:', result);
                                            Swal.fire({
                                                title: 'Pembayaran Pending',
                                                text: 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.',
                                                icon: 'info',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#3B82F6'
                                            });
                                        },
                                        onError: function(result) {
                                            console.log('Payment error:', result);
                                            Swal.fire({
                                                title: 'Pembayaran Gagal',
                                                text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
                                                icon: 'error',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#EF4444'
                                            });
                                        },
                                        onClose: function() {
                                            console.log('Payment closed');
                                            Swal.fire({
                                                title: 'Pembayaran Dibatalkan',
                                                text: 'Anda menutup halaman pembayaran. Silakan coba lagi jika ingin melanjutkan.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#F59E0B'
                                            });
                                        }
                                    });
                                } else {
                                    console.error('Midtrans Snap not loaded');
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Sistem pembayaran belum siap. Silakan refresh halaman dan coba lagi.',
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#EF4444'
                                    });
                                }
                            } else {
                                console.error('Payment creation failed:', response);
                                // Only show error message if it's not an authentication error (already handled in processDirectPayment)
                                if (response.message && !response.message.includes('logged in')) {
                                    Swal.fire({
                                        title: 'Booking Gagal',
                                        text: response.message ||
                                            'Gagal memproses booking. Silakan coba lagi.',
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#EF4444'
                                    });
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error processing payment:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Terjadi kesalahan pada sistem. Silakan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#EF4444'
                            });
                        })
                        .finally(() => {
                            // Reset button state
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;
                        });
                });
            }
        });

        // Fungsi untuk memproses pembayaran melalui backend
        async function processDirectPayment(bookingData) {
            try {
                console.log('Processing payment with data:', bookingData);

                // Generate unique order ID
                const timestamp = Date.now();
                const random = Math.random().toString(36).substr(2, 9);
                const orderId = `SPA-${timestamp}-${random}`;

                // Calculate total amount with voucher
                let serviceFee = currentServicePrice;
                const adminFeeAmount = adminFee;
                let discountAmount = 0;
                let totalAmount = serviceFee + adminFeeAmount;

                // Apply voucher discount if available
                if (appliedVoucher) {
                    console.log('Applying voucher discount:', appliedVoucher);
                    if (appliedVoucher.discount_type === 'percentage') {
                        discountAmount = Math.floor((serviceFee * appliedVoucher.discount_percentage) / 100);
                    } else if (appliedVoucher.discount_type === 'fixed') {
                        discountAmount = appliedVoucher.discount_amount;
                    }
                    // Ensure discount doesn't exceed service price
                    discountAmount = Math.min(discountAmount, serviceFee);
                    totalAmount -= discountAmount;
                    totalAmount = Math.max(totalAmount, adminFeeAmount); // Ensure total is not less than admin fee
                    console.log('Final total after voucher:', totalAmount, 'Discount applied:', discountAmount);
                }

                // Get booking type
                const bookingType = document.querySelector('input[name="booking_type"]:checked').value;

                // Prepare data for backend using new universal booking format
                const paymentData = {
                    spa_id: spaData.id_spa,
                    customer_name: bookingData.customer_name,
                    customer_email: bookingData.customer_email,
                    customer_phone: bookingData.customer_phone,
                    booking_date: bookingData.booking_date,
                    booking_time: bookingData.booking_time,
                    service_type: bookingData.service_type,
                    service_price: serviceFee, // Include service price for backend validation
                    booking_type: bookingType, // venue or terapis
                    service_address: bookingType === 'terapis' ? bookingData.service_address :
                        'Venue booking - customer comes to spa location',
                    notes: bookingData.notes,
                    total_amount: totalAmount,
                    service_fee: serviceFee,
                    admin_fee: adminFeeAmount,
                    voucher_discount: discountAmount,
                    voucher_code: appliedVoucher ? appliedVoucher.code : null,
                    voucher_id: appliedVoucher ? appliedVoucher.id : null
                };

                console.log('[DEBUG] Sending payment data:', paymentData);

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('[ERROR] CSRF token not found in page');
                    throw new Error('CSRF token not found');
                }

                console.log('[DEBUG] Making API call to /api/create-spa-payment');

                // Call backend API to create payment using new universal booking controller
                const response = await fetch('/api/create-spa-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    },
                    body: JSON.stringify(paymentData)
                });

                console.log('[DEBUG] API response status:', response.status);
                console.log('[DEBUG] API response headers:', response.headers);

                // Handle authentication error (401)
                if (response.status === 401) {
                    const errorData = await response.json();
                    console.log('Authentication required:', errorData);

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

                    return {
                        success: false,
                        message: errorData.message || 'Authentication required'
                    };
                }

                if (!response.ok) {
                    console.error('[ERROR] Response not OK, status:', response.status);
                    const errorData = await response.json();
                    console.error('[ERROR] API error data:', errorData);
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('[DEBUG] API result:', result);

                if (result.success && result.payment_token) {
                    console.log('[SUCCESS] Payment token received:', result.payment_token);
                    return {
                        success: true,
                        payment_token: result.payment_token,
                        order_id: orderId,
                        total_amount: totalAmount,
                        booking_data: bookingData
                    };
                } else {
                    console.error('[ERROR] Invalid result structure:', result);
                    throw new Error(result.message || 'Failed to create payment token');
                }
            } catch (error) {
                console.error('[ERROR] Exception in processDirectPayment:', error);
                console.error('[ERROR] Error stack:', error.stack);
                return {
                    success: false,
                    message: error.message || 'Terjadi kesalahan saat membuat pembayaran'
                };
            }
        }

        // Fungsi validasi form
        function validateForm() {
            const form = document.getElementById('spaBookingForm');
            const bookingTypeElement = document.querySelector('input[name="booking_type"]:checked');

            // Check if booking type is selected
            if (!bookingTypeElement) {
                Swal.fire({
                    title: 'Booking Type Tidak Dipilih',
                    text: 'Silakan pilih jenis booking (Booking Tempat atau Booking Terapis).',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#F59E0B'
                });
                return false;
            }

            const bookingType = bookingTypeElement.value;
            let isValid = true;

            // Get required fields based on booking type
            let requiredFields;
            if (bookingType === 'terapis') {
                requiredFields = form.querySelectorAll('input[required]:not([name="booking_type"]), select[required]');
                // Add service_address as required for terapis booking
                const serviceAddressField = form.querySelector('textarea[name="service_address"]');
                if (serviceAddressField) {
                    requiredFields = [...requiredFields, serviceAddressField];
                }
            } else {
                // For venue booking, exclude service_address from required validation
                requiredFields = form.querySelectorAll('input[required]:not([name="booking_type"]), select[required]');
            }

            requiredFields.forEach(field => {
                const value = field.value ? field.value.trim() : '';
                if (!value) {
                    field.classList.add('border-red-500');
                    isValid = false;
                    console.log('Field kosong:', field.name, field);
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            // Collect validation errors
            let validationErrors = [];

            // Validasi email
            const email = form.querySelector('input[type="email"]');
            if (email && email.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    email.classList.add('border-red-500');
                    isValid = false;
                    validationErrors.push('Email tidak valid');
                } else {
                    email.classList.remove('border-red-500');
                }
            }

            // Validasi nomor telepon
            const phone = form.querySelector('input[type="tel"]');
            if (phone && phone.value) {
                const phoneRegex = /^[\d\s\-\+()]+$/;
                const cleanPhone = phone.value.replace(/[\s\-\+()]/g, '');
                if (!phoneRegex.test(phone.value) || cleanPhone.length < 10) {
                    phone.classList.add('border-red-500');
                    isValid = false;
                    validationErrors.push('Nomor telepon tidak valid (minimal 10 digit)');
                } else {
                    phone.classList.remove('border-red-500');
                }
            }

            // Validasi tanggal booking
            const bookingDate = form.querySelector('input[type="date"]');
            if (bookingDate && bookingDate.value) {
                const selectedDate = new Date(bookingDate.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (selectedDate < today) {
                    bookingDate.classList.add('border-red-500');
                    isValid = false;
                    validationErrors.push('Tanggal booking tidak boleh di masa lalu');
                } else {
                    bookingDate.classList.remove('border-red-500');
                }
            }

            if (!isValid) {
                // Scroll to first invalid field
                const firstInvalidField = form.querySelector('.border-red-500');
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => {
                        firstInvalidField.focus();
                    }, 300);
                }

                // Build error message
                let errorMessage = 'Silakan lengkapi semua field yang diperlukan (ditandai dengan border merah).';
                if (validationErrors.length > 0) {
                    errorMessage += '\n\nKesalahan:\n' + validationErrors.map((err, idx) => `${idx + 1}. ${err}`).join('\n');
                }

                Swal.fire({
                    title: 'Form Tidak Lengkap',
                    html: errorMessage.replace(/\n/g, '<br>'),
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#F59E0B'
                });
            }

            return isValid;
        }

        // Fungsi helper untuk format angka
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        // Modal functions
        function openSpaBookingModal() {
            const modal = document.getElementById('spaBookingModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Set minimum date to today
                const bookingDateInput = document.getElementById('booking_date');
                if (bookingDateInput) {
                    const today = new Date().toISOString().split('T')[0];
                    bookingDateInput.setAttribute('min', today);
                }
            }
        }

        function closeSpaBookingModal() {
            const modal = document.getElementById('spaBookingModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';

                // Reset form
                const form = document.getElementById('spaBookingForm');
                if (form) {
                    form.reset();
                }

                // Reset voucher
                if (typeof resetVoucher === 'function') {
                    resetVoucher();
                }

                // Update price display
                currentServicePrice = 0;
                if (typeof updatePriceDisplay === 'function') {
                    updatePriceDisplay();
                }
            }
        }
    </script>
</x-app-layout>
