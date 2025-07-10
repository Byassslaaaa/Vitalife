<x-app-layout>
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="{{ asset('css/spa-booking.css') }}">
    
    <!-- Custom CSS from Admin -->
    @if(isset($spa->detailConfig) && $spa->detailConfig && $spa->detailConfig->custom_css)
        <style>
            {!! $spa->detailConfig->custom_css !!}
        </style>
    @endif
    
    <!-- Custom CSS for Image Gallery -->
    <style>
        .thumbnail {
            transition: all 0.2s ease-in-out;
        }
        .thumbnail:hover {
            transform: scale(1.05);
        }
    </style>
    
    <!-- Main Container with Light Blue Background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-100 to-blue-50 pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Side - Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Hero Image Gallery - FIXED TO MATCH GYM-DETAIL -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
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
                                    $galleryImages = [$defaultImage, $defaultImage, $defaultImage, $defaultImage, $defaultImage];
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
                                 class="w-full h-96 object-cover rounded-t-2xl">
                        </div>
                        
                        <!-- Thumbnail Images - CHANGED TO 5 COLUMNS LIKE GYM-DETAIL -->
                        <div class="p-4">
                            <div class="grid grid-cols-5 gap-3">
                                @foreach($galleryImages as $index => $image)
                                    <img src="{{ asset($image) }}" alt="Image {{ $index + 1 }}" 
                                         class="thumbnail w-full h-20 object-cover rounded-xl cursor-pointer border-2 transition-all duration-200 {{ $index === 0 ? 'border-blue-500 opacity-100' : 'border-gray-200 opacity-70 hover:opacity-100 hover:border-blue-300' }}">
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Spa Information -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <!-- Title and Location -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-3">
                                {{ (isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->hero_title) ? $spa->spaDetail->hero_title : ($spa->nama ?? 'Spa Name') }}
                            </h1>
                            <div class="flex items-center text-gray-600 text-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $spa->alamat ?? 'Location not available' }}</span>
                            </div>
                        </div>
                        
                        <!-- Services Section (Display Only - from spa-details admin) -->
                        @if(!isset($spa->spaDetail) || $spa->spaDetail->show_facilities !== false)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Services</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    // Get display services from spa-details admin (additional_services)
                                    $displayServices = [];
                                    
                                    if (isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->additional_services) {
                                        if (is_array($spa->spaDetail->additional_services)) {
                                            $displayServices = $spa->spaDetail->additional_services;
                                        } elseif (is_string($spa->spaDetail->additional_services)) {
                                            $displayServices = json_decode($spa->spaDetail->additional_services, true) ?? [];
                                        }
                                    }
                                    
                                    // Fallback to default display services if empty
                                    if (empty($displayServices)) {
                                        $displayServices = [
                                            [
                                                'name' => 'Traditional Massage',
                                                'description' => 'Traditional massage to relieve tension and stress.',
                                                'image' => 'images/massage1.jpg'
                                            ],
                                            [
                                                'name' => 'Deep Tissue Massage',
                                                'description' => 'Deep tissue massage for muscle relief.',
                                                'image' => 'images/massage2.jpg'
                                            ],
                                            [
                                                'name' => 'Hot Stone Massage',
                                                'description' => 'Relaxing hot stone therapy treatment.',
                                                'image' => 'images/massage3.jpg'
                                            ],
                                            [
                                                'name' => 'Aromatherapy Massage',
                                                'description' => 'Soothing aromatherapy treatment.',
                                                'image' => 'images/massage4.jpg'
                                            ]
                                        ];
                                    }
                                @endphp
                                
                                @if(!empty($displayServices))
                                    @foreach($displayServices as $service)
                                        <div class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                                            <div class="flex-shrink-0">
                                                @php
                                                    $serviceImage = 'images/default-massage.jpg';
                                                    if (isset($service['image']) && $service['image']) {
                                                        $serviceImage = $service['image'];
                                                    }
                                                @endphp
                                                <img src="{{ asset($serviceImage) }}" 
                                                     alt="{{ $service['name'] ?? 'Service' }}" 
                                                     class="w-16 h-16 rounded-full object-cover">
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">{{ $service['name'] ?? 'Service' }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">{{ $service['description'] ?? 'Professional spa service' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-span-2 text-center py-8">
                                        <p class="text-gray-500">No services available at the moment.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                                            <!-- Facilities Section -->
                    @if($spa->spaDetail && $spa->spaDetail->facilities && count($spa->spaDetail->facilities) > 0)
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Facilities</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($spa->spaDetail->facilities as $facility)
                                <div class="flex items-center space-x-3 p-4 rounded-xl bg-gradient-to-r from-green-50 to-blue-50 border border-green-100 hover:shadow-md transition-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $facility }}</span>
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
                                    $description = 'Memorable Spa is a professional and affordable on-call massage service in Yogyakarta. We understand how important relaxation and health are in daily life, and we are committed to providing an exceptional massage experience right in the comfort of your home or office.';
                                    
                                    if (isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->about_spa) {
                                        $description = $spa->spaDetail->about_spa;
                                    }
                                @endphp
                                <p>{{ $description }}</p>
                            </div>
                        </div>

                        <!-- Opening Work Section -->
                        @if(!isset($spa->spaDetail) || $spa->spaDetail->show_opening_hours !== false)
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
                                    foreach($waktuBuka as $day => $hours) {
                                        $scheduleArray[] = [
                                            'day' => $dayMapping[$day] ?? $day,
                                            'hours' => $hours
                                        ];
                                    }
                                @endphp
                                
                                @for($i = 0; $i < 3; $i++)
                                    @if(isset($scheduleArray[$i]))
                                        <div class="bg-gray-100 rounded-lg p-4 hover:bg-gray-200 transition-colors">
                                            <div class="font-medium text-gray-800 mb-1">{{ $scheduleArray[$i]['day'] }}</div>
                                            <div class="text-sm text-gray-600">{{ $scheduleArray[$i]['hours'] }}</div>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                            
                            <!-- Second Row - 3 cards -->
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                @for($i = 3; $i < 6; $i++)
                                    @if(isset($scheduleArray[$i]))
                                        <div class="bg-gray-100 rounded-lg p-4 hover:bg-gray-200 transition-colors">
                                            <div class="font-medium text-gray-800 mb-1">{{ $scheduleArray[$i]['day'] }}</div>
                                            <div class="text-sm text-gray-600">{{ $scheduleArray[$i]['hours'] }}</div>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                            
                            <!-- Third Row - 1 card -->
                            <div class="grid grid-cols-3 gap-4">
                                @if(isset($scheduleArray[6]))
                                    <div class="bg-gray-100 rounded-lg p-4 hover:bg-gray-200 transition-colors">
                                        <div class="font-medium text-gray-800 mb-1">{{ $scheduleArray[6]['day'] }}</div>
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
                        @if(!isset($spa->spaDetail) || $spa->spaDetail->show_booking_policy !== false)
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <!-- Policy Header with Clipboard Icon -->
                            <div class="text-center mb-6">
                                <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                                    <div class="flex items-center justify-center mb-4">
                                        <div class="bg-white p-3 rounded-xl shadow-sm">
                                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-xl mb-2">
                                        {{ (isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->booking_policy_title) ? $spa->spaDetail->booking_policy_title : 'BOOKING POLICY' }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ (isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->booking_policy_subtitle) ? $spa->spaDetail->booking_policy_subtitle : 'YOUR WELLNESS PLANS' }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Booking Button -->
                            <button class="bookingBtn w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-4 px-6 rounded-xl transition duration-300 transform hover:scale-105 text-lg"
                                data-spa-id="{{ $spa->id_spa ?? '' }}">
                                Booking Online
                            </button>
                        </div>
                        @endif
                        
                        <!-- Contact Person Card -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Contact Person</h3>
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-lg">
                                        {{ (isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->contact_person_name) ? $spa->spaDetail->contact_person_name : 'Contact Person' }}
                                    </p>
                                    <p class="text-gray-600">
                                        {{ (isset($spa->spaDetail) && $spa->spaDetail && $spa->spaDetail->contact_person_phone) ? $spa->spaDetail->contact_person_phone : ($spa->noHP ?? 'N/A') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Location Card -->
                        @if(!isset($spa->spaDetail) || $spa->spaDetail->show_location_map !== false)
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Location</h3>
                            @if($spa->maps)
                                <div class="rounded-xl overflow-hidden h-48">
                                    {!! $spa->maps !!}
                                </div>
                            @else
                                <div class="bg-gray-100 rounded-xl h-48 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <p class="text-gray-600 text-sm">{{ $spa->alamat ?? 'Location not available' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="spaBookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Booking Spa Service</h3>
                    <button onclick="closeSpaBookingModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Booking Form -->
                <form id="spaBookingForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="customer_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="customer_email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. HP *</label>
                            <input type="tel" name="customer_phone" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Layanan *</label>
                            <select name="service_type" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Layanan</option>
                                @php
                                    // Get bookable services from spa-services management
                                    $bookableServices = [];
                                    
                                    if (isset($spa->spaServices) && $spa->spaServices->count() > 0) {
                                        foreach($spa->spaServices->where('is_active', true) as $service) {
                                            $bookableServices[] = [
                                                'id' => $service->id,
                                                'title' => $service->name,
                                                'price' => $service->price,
                                                'duration' => $service->duration ?? 60,
                                                'category' => $service->category ?? 'Spa Service'
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
                                                'category' => 'Traditional'
                                            ],
                                            [
                                                'id' => null,
                                                'title' => 'Deep Tissue Massage',
                                                'price' => 200000,
                                                'duration' => 90,
                                                'category' => 'Therapeutic'
                                            ],
                                            [
                                                'id' => null,
                                                'title' => 'Hot Stone Massage',
                                                'price' => 250000,
                                                'duration' => 90,
                                                'category' => 'Premium'
                                            ],
                                            [
                                                'id' => null,
                                                'title' => 'Aromatherapy Massage',
                                                'price' => 180000,
                                                'duration' => 75,
                                                'category' => 'Relaxation'
                                            ]
                                        ];
                                    }
                                @endphp
                                @foreach($bookableServices as $service)
                                    <option value="{{ $service['title'] }}" data-price="{{ $service['price'] }}">
                                        {{ $service['title'] }} - Rp {{ number_format($service['price'], 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Booking *</label>
                            <input type="date" name="booking_date" id="booking_date" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu *</label>
                            <input type="time" name="booking_time" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Layanan *</label>
                        <textarea name="service_address" required rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Masukkan alamat lengkap untuk layanan spa..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                        <textarea name="notes" rows="2" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Catatan khusus untuk terapis (opsional)..."></textarea>
                    </div>

                    <!-- Price Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Ringkasan Pembayaran</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Harga Layanan:</span>
                                <span id="servicePrice">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Admin:</span>
                                <span>Rp 5.000</span>
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
                            class="w-full bg-blue-600 text-white font-semibold py-4 px-6 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                        Lanjutkan Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript"
            src="{{ config('services.midtrans.snap_url') }}"
            data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
    
    <script>
        // Global variables
        const bookableServices = @json($bookableServices ?? []);
        const spaData = @json($spa);
        
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
                        thumb.classList.remove('border-blue-500', 'opacity-100');
                        thumb.classList.add('border-gray-200', 'opacity-70');
                    });
                    
                    // Add active state to clicked thumbnail
                    this.classList.remove('border-gray-200', 'opacity-70');
                    this.classList.add('border-blue-500', 'opacity-100');
                    
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
            const totalPriceEl = document.getElementById('totalPrice');

            console.log('Booking button found:', bookingBtn);

            if (bookingBtn) {
                bookingBtn.addEventListener('click', function() {
                    console.log('Booking button clicked');
                    openSpaBookingModal();
                });
            }

            // Update price when service is selected
            if (serviceSelect) {
                serviceSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const price = selectedOption.dataset.price || 0;
                    const adminFee = 5000;
                    const total = parseInt(price) + adminFee;
                    
                    servicePriceEl.textContent = 'Rp ' + formatNumber(price);
                    totalPriceEl.textContent = 'Rp ' + formatNumber(total);
                });
            }

            function openSpaBookingModal() {
                console.log('Opening booking modal');
                spaBookingModal.classList.remove('hidden');
                // Set minimum date to today
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('booking_date').setAttribute('min', today);
            }

            window.closeSpaBookingModal = function() {
                console.log('Closing booking modal');
                spaBookingModal.classList.add('hidden');
                spaBookingForm.reset();
                servicePriceEl.textContent = 'Rp 0';
                totalPriceEl.textContent = 'Rp 5.000';
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
                    formData.forEach((value, key) => { data[key] = value; });
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
                                    console.log('Opening Midtrans Snap with token:', response.payment_token);
                                    window.snap.pay(response.payment_token, {
                                        onSuccess: function(result){
                                            console.log('Payment success:', result);
                                            Swal.fire({
                                                title: 'Pembayaran Berhasil!',
                                                text: 'Booking layanan spa Anda telah berhasil dibayar. Terapis akan segera menghubungi Anda.',
                                                icon: 'success',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#10B981'
                                            }).then(() => {
                                                window.location.reload();
                                            });
                                        },
                                        onPending: function(result){
                                            console.log('Payment pending:', result);
                                            Swal.fire({
                                                title: 'Pembayaran Pending',
                                                text: 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.',
                                                icon: 'info',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#3B82F6'
                                            });
                                        },
                                        onError: function(result){
                                            console.log('Payment error:', result);
                                            Swal.fire({
                                                title: 'Pembayaran Gagal',
                                                text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
                                                icon: 'error',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#EF4444'
                                            });
                                        },
                                        onClose: function(){
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
                                Swal.fire({
                                    title: 'Booking Gagal',
                                    text: response.message || 'Gagal memproses booking. Silakan coba lagi.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#EF4444'
                                });
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
                
                // Get service price
                const serviceSelect = document.querySelector('select[name="service_type"]');
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const serviceFee = parseInt(selectedOption.dataset.price) || 100000;
                const adminFee = 5000;
                const totalAmount = serviceFee + adminFee;
                
                // Prepare data for backend
                const paymentData = {
                    order_id: orderId,
                    spa_id: spaData.id_spa,
                    customer_name: bookingData.customer_name,
                    customer_email: bookingData.customer_email,
                    customer_phone: bookingData.customer_phone,
                    service_type: bookingData.service_type,
                    service_price: serviceFee,
                    admin_fee: adminFee,
                    total_amount: totalAmount,
                    booking_date: bookingData.booking_date,
                    booking_time: bookingData.booking_time,
                    service_address: bookingData.service_address,
                    notes: bookingData.notes
                };

                console.log('Sending payment data:', paymentData);

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }

                // Call backend API to create payment
                const response = await fetch('/api/create-spa-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    },
                    body: JSON.stringify(paymentData)
                });

                console.log('API response status:', response.status);

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('API error:', errorData);
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('API result:', result);
                
                if (result.success && result.payment_token) {
                    return {
                        success: true,
                        payment_token: result.payment_token,
                        order_id: orderId,
                        total_amount: totalAmount,
                        booking_data: bookingData
                    };
                } else {
                    throw new Error(result.message || 'Failed to create payment token');
                }
            } catch (error) {
                console.error('Error creating payment:', error);
                return {
                    success: false,
                    message: error.message || 'Terjadi kesalahan saat membuat pembayaran'
                };
            }
        }

        // Fungsi validasi form
        function validateForm() {
            const form = document.getElementById('spaBookingForm');
            const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            // Validasi email
            const email = form.querySelector('input[type="email"]');
            if (email && email.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    email.classList.add('border-red-500');
                    isValid = false;
                    Swal.fire({
                        title: 'Email Tidak Valid',
                        text: 'Silakan masukkan alamat email yang valid.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#EF4444'
                    });
                }
            }

            // Validasi nomor telepon
            const phone = form.querySelector('input[type="tel"]');
            if (phone && phone.value) {
                const phoneRegex = /^[\d\s\-\+()]+$/;
                if (!phoneRegex.test(phone.value) || phone.value.length < 10) {
                    phone.classList.add('border-red-500');
                    isValid = false;
                    Swal.fire({
                        title: 'Nomor Telepon Tidak Valid',
                        text: 'Silakan masukkan nomor telepon yang valid (minimal 10 digit).',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#EF4444'
                    });
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
                    Swal.fire({
                        title: 'Tanggal Tidak Valid',
                        text: 'Tanggal booking tidak boleh di masa lalu.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#EF4444'
                    });
                }
            }

            if (!isValid) {
                Swal.fire({
                    title: 'Form Tidak Lengkap',
                    text: 'Silakan lengkapi semua field yang diperlukan.',
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
    </script>
</x-app-layout>
