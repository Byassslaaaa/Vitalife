<x-app-layout>
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .gym-gradient {
            background: linear-gradient(135deg, #ecfeff 0%, #cffafe 50%, #a5f3fc 100%);
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
            border-color: #06b6d4 !important;
            opacity: 1 !important;
        }
    </style>

    {{-- Modern GYM Detail Section --}}
    <div class="gym-gradient pb-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Side - Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Hero Image Gallery -->
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                        <!-- Main Image -->
                        <div class="relative">
                            @php
                                $galleryImages = $gym->gymDetail->gallery_images_with_fallback ?? [
                                    $gym->image,
                                    $gym->image,
                                    $gym->image,
                                    $gym->image,
                                    $gym->image,
                                ];
                                $mainImage = $galleryImages[0];
                            @endphp
                            <img id="mainImage" src="{{ asset($mainImage) }}" alt="{{ $gym->nama }}"
                                class="w-full h-96 object-cover transition-opacity duration-300">
                        </div>

                        <!-- Thumbnail Images -->
                        <div class="p-4">
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($galleryImages as $index => $image)
                                    <img src="{{ asset($image) }}" alt="Image {{ $index + 1 }}"
                                        class="thumbnail w-full h-20 object-cover rounded-xl cursor-pointer border-2 transition-all duration-200 {{ $index === 0 ? 'border-cyan-500 opacity-100' : 'border-gray-200 opacity-70 hover:opacity-100 hover:border-cyan-300' }}">
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Gym Information -->
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                        <!-- Title and Location -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $gym->nama }}</h1>

                            <!-- Rating Stars -->
                            <div class="flex items-center mb-3">
                                <div class="flex items-center">
                                    @php
                                        $averageRating = $gym->average_rating;
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
                                                <linearGradient id="halfGradGym">
                                                    <stop offset="50%" stop-color="#FBBF24"/>
                                                    <stop offset="50%" stop-color="#D1D5DB"/>
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#halfGradGym)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
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
                                    ({{ $gym->testimonials_count }} {{ $gym->testimonials_count == 1 ? 'review' : 'reviews' }})
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
                                <span>{{ $gym->alamat }}</span>
                            </div>
                        </div>

                        <!-- Services Section (from database/seeder) -->
                        @if ($gym->services && count($gym->services) > 0)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Services</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($gym->services as $service)
                                        <div
                                            class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                                            <div class="flex-shrink-0">
                                                @if (isset($service['image']) && $service['image'])
                                                    <img src="{{ asset($service['image']) }}"
                                                        alt="{{ $service['name'] }}"
                                                        class="w-16 h-16 rounded-full object-cover border-2 border-cyan-200">
                                                @else
                                                    <div
                                                        class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-white" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">
                                                    {{ $service['name'] }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $service['description'] }}</p>
                                                @if (isset($service['price']))
                                                    <p class="text-sm font-bold text-cyan-600 mt-2">
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
                            $gymFacilities = [];
                            // Prioritize facilities from main gym table (from seeder)
                            if ($gym->fasilitas && is_array($gym->fasilitas)) {
                                $gymFacilities = array_filter($gym->fasilitas, function($item) {
                                    return is_string($item);
                                });
                            } elseif ($gym->gymDetail && $gym->gymDetail->facilities) {
                                // Fallback to gymDetail facilities
                                $gymFacilities = array_filter($gym->gymDetail->facilities, function($item) {
                                    return is_string($item);
                                });
                            }
                        @endphp
                        @if (!empty($gymFacilities))
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                    <svg class="w-7 h-7 mr-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                    Facilities & Equipment
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($gymFacilities as $facility)
                                        @php
                                            $iconConfig = ['path' => 'M5 13l4 4L19 7', 'viewBox' => '0 0 24 24', 'gradient' => 'from-cyan-500 to-blue-500'];
                                            $lowerFacility = strtolower($facility);

                                            // Gym-specific icon mappings
                                            if (str_contains($lowerFacility, 'weight') || str_contains($lowerFacility, 'dumbbell')) {
                                                $iconConfig = [
                                                    'path' => 'M20.57 14.86L22 13.43 20.57 12 17 15.57 8.43 7 12 3.43 10.57 2 9.14 3.43 7.71 2 5.57 4.14 4.14 2.71 2.71 4.14l1.43 1.43L2 7.71l1.43 1.43L2 10.57 3.43 12 7 8.43 15.57 17 12 20.57 13.43 22l1.43-1.43L16.29 22l2.14-2.14 1.43 1.43 1.43-1.43-1.43-1.43L22 16.29z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-red-500 to-orange-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'cardio') || str_contains($lowerFacility, 'treadmill') || str_contains($lowerFacility, 'running')) {
                                                $iconConfig = [
                                                    'path' => 'M13.49 5.48c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-3.6 13.9l1-4.4 2.1 2v6h2v-7.5l-2.1-2 .6-3c1.3 1.5 3.3 2.5 5.5 2.5v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1l-5.2 2.2v4.7h2v-3.4l1.8-.7-1.6 8.1-4.9-1-.4 2 7 1.4z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-green-500 to-teal-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'pool') || str_contains($lowerFacility, 'swimming')) {
                                                $iconConfig = [
                                                    'path' => 'M22 21c-1.11 0-1.73-.37-2.18-.64-.37-.22-.6-.36-1.15-.36-.56 0-.78.13-1.15.36-.46.27-1.07.64-2.18.64s-1.73-.37-2.18-.64c-.37-.22-.6-.36-1.15-.36-.56 0-.78.13-1.15.36-.46.27-1.08.64-2.19.64-1.11 0-1.73-.37-2.18-.64-.37-.23-.6-.36-1.15-.36s-.78.13-1.15.36c-.46.27-1.08.64-2.19.64v-2c.56 0 .78-.13 1.15-.36.46-.27 1.08-.64 2.19-.64s1.73.37 2.18.64c.37.23.59.36 1.15.36.56 0 .78-.13 1.15-.36.46-.27 1.08-.64 2.19-.64 1.11 0 1.73.37 2.18.64.37.22.6.36 1.15.36s.78-.13 1.15-.36c.45-.27 1.07-.64 2.18-.64s1.73.37 2.18.64c.37.23.59.36 1.15.36v2zm-.78-4.5c-.37-.22-.6-.36-1.15-.36-.56 0-.78.13-1.15.36-.46.27-1.07.64-2.18.64s-1.73-.37-2.18-.64c-.37-.22-.6-.36-1.15-.36-.56 0-.78.13-1.15.36-.45.27-1.07.64-2.18.64s-1.73-.37-2.18-.64c-.37-.22-.6-.36-1.15-.36s-.78.13-1.15.36c-.47.27-1.09.64-2.2.64v-2c.56 0 .78-.13 1.15-.36.45-.27 1.07-.64 2.18-.64s1.73.37 2.18.64c.37.22.6.36 1.15.36.56 0 .78-.13 1.15-.36.45-.27 1.07-.64 2.18-.64s1.73.37 2.18.64c.37.22.6.36 1.15.36s.78-.13 1.15-.36c.45-.27 1.07-.64 2.18-.64s1.73.37 2.18.64c.37.22.6.36 1.15.36v2c-1.11 0-1.73-.37-2.2-.64zM8.67 12c.56 0 .78-.13 1.15-.36.46-.27 1.08-.64 2.19-.64 1.11 0 1.73.37 2.18.64.37.22.6.36 1.15.36s.78-.13 1.15-.36c.12-.07.26-.15.41-.23L10.48 5C8.93 3.45 7.5 2.99 5 3v2.5c1.82-.01 2.89.39 4 1.5l1 1-3.25 3.25c.31.12.56.27.77.39.37.23.59.36 1.15.36z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-blue-500 to-cyan-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'locker') || str_contains($lowerFacility, 'changing')) {
                                                $iconConfig = [
                                                    'path' => 'M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-gray-600 to-gray-800'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'sauna') || str_contains($lowerFacility, 'steam')) {
                                                $iconConfig = [
                                                    'path' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-red-500 to-orange-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'parking')) {
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
                                            } elseif (str_contains($lowerFacility, 'ac') || str_contains($lowerFacility, 'air')) {
                                                $iconConfig = [
                                                    'path' => 'M22 11h-4.17l3.24-3.24-1.41-1.42L15 11h-2V9l4.66-4.66-1.42-1.41L13 6.17V2h-2v4.17L7.76 2.93 6.34 4.34 11 9v2H9L4.34 6.34 2.93 7.76 6.17 11H2v2h4.17l-3.24 3.24 1.41 1.42L9 13h2v2l-4.66 4.66 1.42 1.41L11 17.83V22h2v-4.17l3.24 3.24 1.42-1.41L13 15v-2h2l4.66 4.66 1.41-1.42L17.83 13H22z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-cyan-500 to-blue-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'trainer') || str_contains($lowerFacility, 'personal')) {
                                                $iconConfig = [
                                                    'path' => 'M16.5 13c-1.2 0-3.07.34-4.5 1-1.43-.67-3.3-1-4.5-1C5.33 13 1 14.08 1 16.25V19h22v-2.75c0-2.17-4.33-3.25-6.5-3.25zm-4 4.5h-10v-1.25c0-.54 2.56-1.75 5-1.75s5 1.21 5 1.75v1.25zm9 0H14v-1.25c0-.46-.2-.86-.52-1.22.88-.3 1.96-.53 3.02-.53 2.44 0 5 1.21 5 1.75v1.25zM7.5 12c1.93 0 3.5-1.57 3.5-3.5S9.43 5 7.5 5 4 6.57 4 8.5 5.57 12 7.5 12zm0-5.5c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 5.5c1.93 0 3.5-1.57 3.5-3.5S18.43 5 16.5 5 13 6.57 13 8.5s1.57 3.5 3.5 3.5zm0-5.5c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-yellow-500 to-orange-500'
                                                ];
                                            } elseif (str_contains($lowerFacility, 'shower') || str_contains($lowerFacility, 'bathroom')) {
                                                $iconConfig = [
                                                    'path' => 'M9 17c0 .55-.45 1-1 1s-1-.45-1-1 .45-1 1-1 1 .45 1 1zm3-1c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm4 0c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm3-4v2H5v-2c0-.55.45-1 1-1h12c.55 0 1 .45 1 1zM9 3l.01 9h2V3.59C13.71 4.09 16 6.56 16 9.58V11h3V9.58C19 5.09 15.52 2 11 2c-.55 0-1 .45-1 1z',
                                                    'viewBox' => '0 0 24 24',
                                                    'gradient' => 'from-blue-500 to-cyan-500'
                                                ];
                                            }
                                        @endphp
                                        <div class="group flex items-start space-x-4 p-5 rounded-2xl bg-white border-2 border-cyan-100 hover:border-cyan-300 hover:shadow-lg transition-all duration-300">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-gradient-to-br {{ $iconConfig['gradient'] }} rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="{{ $iconConfig['viewBox'] }}">
                                                        <path d="{{ $iconConfig['path'] }}"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">{{ $facility }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">Available for all members</p>
                                            </div>
                                            <svg class="w-5 h-5 text-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- About This Gym Section -->
                        @if ($gym->description || ($gym->gymDetail && $gym->gymDetail->about_gym))
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Gym</h2>
                                <div class="prose text-gray-600 text-lg leading-relaxed">
                                    <p>{{ $gym->description ?? $gym->gymDetail->about_gym }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Opening Hours Section -->
                        @if ($gym->gymDetail && $gym->gymDetail->opening_hours)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Opening Hours</h2>

                                @php
                                    $scheduleArray = $gym->gymDetail->formatted_opening_hours;
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

                <!-- Right Side - Contact & Booking Card -->
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
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-xl mb-2">BOOKING POLICY</h3>
                                    <p class="text-sm text-gray-600">ACHIEVE YOUR FITNESS GOALS</p>
                                </div>
                            </div>

                            <!-- Booking Button -->
                            <button
                                class="bookingBtn w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-4 px-6 rounded-xl transition duration-300 transform hover:scale-105 text-lg"
                                data-gym-id="{{ $gym->id_gym }}">
                                Booking Online
                            </button>
                        </div>

                        <!-- Modal Booking -->
                        <div id="gymBookingModal"
                            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                            <div
                                class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-2xl bg-white">
                                <div class="mt-3">
                                    <!-- Modal Header -->
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-2xl font-bold text-gray-900">Booking Gym Service</h3>
                                        <button onclick="closeGymBookingModal()"
                                            class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Booking Form -->
                                    <form id="gymBookingForm" class="space-y-6">
                                        <input type="hidden" name="gym_id" value="{{ $gym->id_gym }}">

                                        <!-- Service Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Choose
                                                Service</label>
                                            <select name="service_id" id="serviceSelect" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">Select a service...</option>
                                            </select>
                                        </div>

                                        <!-- Service Details Display -->
                                        <div id="service-details" class="hidden bg-blue-50 p-4 rounded-lg">
                                            <div class="text-sm">
                                                <div class="font-medium text-gray-900 mb-2"
                                                    id="selected-service-name"></div>
                                                <div class="text-gray-600 mb-2" id="selected-service-description">
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-gray-500" id="selected-service-duration"></span>
                                                    <span class="font-bold text-blue-600"
                                                        id="selected-service-price"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Date and Time -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                                                <input type="date" name="booking_date" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                                                <input type="time" name="booking_time" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            </div>
                                        </div>

                                        <!-- Customer Information -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Full
                                                    Name</label>
                                                <input type="text" name="customer_name" required
                                                    placeholder="Enter your full name"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone
                                                    Number</label>
                                                <input type="tel" name="customer_phone" required
                                                    placeholder="Enter your phone number"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Email
                                                Address</label>
                                            <input type="email" name="customer_email" required
                                                placeholder="Enter your email address"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>

                                        <!-- Special Notes -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Special Notes
                                                (Optional)</label>
                                            <textarea name="notes" rows="3" placeholder="Any special requests or notes..."
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="flex justify-end space-x-3 pt-4">
                                            <button type="button" onclick="closeGymBookingModal()"
                                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
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

                        <!-- Contact Person Card -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Contact Person</h3>
                            <div class="flex items-center space-x-4 mb-6">
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-lg">
                                        {{ $gym->contact_person ?? ($gym->gymDetail->contact_person_name ?? 'Customer Service') }}
                                    </p>
                                    <p class="text-gray-600">
                                        {{ $gym->contact_phone ?? ($gym->gymDetail->contact_person_phone ?? 'Contact Available') }}
                                    </p>
                                </div>
                            </div>
                            @php
                                $contactPhone = $gym->contact_phone ?? ($gym->gymDetail->contact_person_phone ?? null);
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
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">
                                <i class="fas fa-map-marker-alt text-cyan-500 mr-2"></i>
                                Location
                            </h3>
                            @if ($gym->maps)
                                <div class="rounded-xl overflow-hidden h-64 shadow-md">
                                    {!! $gym->maps !!}
                                </div>
                                <div class="mt-3 text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Click and drag to explore the map
                                </div>
                            @elseif ($gym->gymDetail && $gym->gymDetail->location_maps)
                                <div class="rounded-xl overflow-hidden h-64 shadow-md">
                                    {!! $gym->gymDetail->location_maps !!}
                                </div>
                                <div class="mt-3 text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Click and drag to explore the map
                                </div>
                            @else
                                <div class="bg-gray-100 rounded-xl h-64 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <p class="text-gray-600 text-sm font-medium">{{ $gym->alamat }}</p>
                                        <p class="text-gray-500 text-xs mt-1">Map will be available soon</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonials Section -->
            <div class="mt-16 mb-0 pb-20">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-black text-gray-900 mb-4">What Our Members Say</h2>
                    <p class="text-gray-600 text-lg">Real experiences from our valued gym members</p>
                </div>

                {{-- Testimonials loaded from database via GymController --}}
                @if ($testimonials && $testimonials->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($testimonials->take(3) as $testimonial)
                            <div class="bg-white rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-cyan-100">
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
                                <p class="text-cyan-900 mb-4 leading-relaxed">
                                    "{{ $testimonial->comment }}"
                                </p>

                                <!-- Service Badge -->
                                @if ($testimonial->service)
                                    <div class="mb-4">
                                        <span class="inline-block bg-cyan-50 text-cyan-700 text-xs font-semibold px-3 py-1 rounded-full">
                                            {{ $testimonial->service }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Customer Info -->
                                <div class="flex items-center justify-between pt-4 border-t border-cyan-100">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-bold text-gray-900">{{ $testimonial->name }}</p>
                                            <p class="text-xs text-gray-600">{{ $testimonial->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="text-cyan-500">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        // GYM BOOKING SCRIPT v3.0 - Updated 2025-11-08 - PLEASE HARD REFRESH (CTRL+SHIFT+R)!
        console.log('%c[GYM BOOKING v3.0] Page loaded - Services endpoint updated to /api/gym/*/services', 'color: green; font-weight: bold; font-size: 14px');

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
                        thumb.classList.remove('border-cyan-500', 'opacity-100');
                        thumb.classList.add('border-gray-200', 'opacity-70');
                    });

                    // Add active state to clicked thumbnail
                    this.classList.remove('border-gray-200', 'opacity-70');
                    this.classList.add('border-cyan-500', 'opacity-100');

                    // Update main image
                    mainImage.src = this.src;
                });
            });

            // Booking modal functionality
            const bookingBtn = document.querySelector('.bookingBtn');
            const gymBookingModal = document.getElementById('gymBookingModal');
            const gymBookingForm = document.getElementById('gymBookingForm');
            const serviceSelect = document.getElementById('serviceSelect');
            const serviceDetails = document.getElementById('service-details');

            let gymServices = [];

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

                    const gymId = this.getAttribute('data-gym-id');
                    loadGymServices(gymId);
                    openGymBookingModal();
                });
            }

            function openGymBookingModal() {
                gymBookingModal.classList.remove('hidden');
                // Set minimum date to today
                const dateInput = gymBookingForm.querySelector('input[name="booking_date"]');
                if (dateInput) {
                    const today = new Date().toISOString().split('T')[0];
                    dateInput.setAttribute('min', today);
                }
            }

            window.closeGymBookingModal = function() {
                gymBookingModal.classList.add('hidden');
                gymBookingForm.reset();
                serviceDetails.classList.add('hidden');
            }

            // Load gym services
            function loadGymServices(gymId) {
                fetch(`/api/gym/${gymId}/services`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.services) {
                            gymServices = data.services;
                            populateServiceSelect(data.services);
                        } else {
                            showServiceError('Failed to load services: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error loading services:', error);
                        showServiceError('Network error: ' + error.message);
                    });
            }

            // Show service loading error
            function showServiceError(message) {
                serviceSelect.innerHTML = '<option value="">' + message + '</option>';
                serviceDetails.classList.add('hidden');
            }

            // Populate service select
            function populateServiceSelect(services) {
                serviceSelect.innerHTML = '<option value="">Select a service...</option>';
                if (services && services.length > 0) {
                    services.forEach(service => {
                        const option = document.createElement('option');
                        option.value = service.id;
                        option.textContent =
                            `${service.name} - Rp ${new Intl.NumberFormat('id-ID').format(service.price)}`;
                        option.dataset.service = JSON.stringify(service);
                        serviceSelect.appendChild(option);
                    });
                } else {
                    showServiceError('No services available');
                }
            }

            // Service selection change
            if (serviceSelect) {
                serviceSelect.addEventListener('change', function() {
                    if (this.value) {
                        const selectedOption = this.options[this.selectedIndex];
                        const service = JSON.parse(selectedOption.dataset.service);

                        document.getElementById('selected-service-name').textContent = service.name;
                        document.getElementById('selected-service-description').textContent = service
                            .description;
                        document.getElementById('selected-service-price').textContent =
                            `Rp ${new Intl.NumberFormat('id-ID').format(service.price)}`;

                        const durationElement = document.getElementById('selected-service-duration');
                        if (durationElement) {
                            durationElement.textContent = service.duration || '';
                        }

                        serviceDetails.classList.remove('hidden');
                    } else {
                        serviceDetails.classList.add('hidden');
                    }
                });
            }

            // Form submission
            if (gymBookingForm) {
                gymBookingForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validate service selection
                    if (!serviceSelect.value) {
                        Swal.fire('Error', 'Please select a gym service.', 'error');
                        return;
                    }

                    // Prepare form data
                    const formData = new FormData(gymBookingForm);
                    const data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });

                    // Add booking type for gym bookings
                    data.booking_type = 'gym';

                    // Show loading state
                    const submitBtn = gymBookingForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML =
                        '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing...';
                    submitBtn.disabled = true;

                    // Log the data being sent
                    console.log('[GYM BOOKING] Sending booking data:', data);

                    // Submit booking request
                    fetch('/api/create-gym-payment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify(data)
                        })
                        .then(async response => {
                            console.log('[GYM BOOKING] Response status:', response.status);
                            console.log('[GYM BOOKING] Response OK:', response.ok);

                            // Handle authentication error (401)
                            if (response.status === 401) {
                                const errorData = await response.json();
                                console.log('[GYM BOOKING] Authentication required:', errorData);

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
                                console.log('[GYM BOOKING] Validation errors:', errorData);

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
                                console.log('[GYM BOOKING] Server error response:', errorText);

                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;

                                Swal.fire('Server Error', `HTTP ${response.status}: ${response.statusText}`, 'error');
                                return null;
                            }

                            return response.json();
                        })
                        .then(result => {
                            if (!result) return; // Skip if we already handled auth error

                            console.log('[GYM BOOKING] Response data:', result);

                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;

                            if (result.success && result.payment_token && result.booking_id) {
                                console.log('[GYM BOOKING] Payment successful, loading Midtrans...');
                                closeGymBookingModal();
                                // Show payment processing with Midtrans
                                loadMidtransSnap(result.payment_token, result.booking_id);
                            } else {
                                console.log('[GYM BOOKING] Booking failed:', result.message);
                                // Only show error message if it's not an authentication error
                                if (result.message && !result.message.includes('logged in')) {
                                    Swal.fire('Error', result.message ||
                                        'Booking failed. Please try again.', 'error');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('[GYM BOOKING] Error:', error);
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
                        Swal.fire('Payment Successful', 'Your gym booking has been paid!', 'success')
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
    </div>
</x-app-layout>
