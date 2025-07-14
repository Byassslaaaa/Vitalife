<x-app-layout>
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

                        <!-- Services Section (Display Only) -->
                        @if ($yoga->yogaServices && $yoga->yogaServices->count() > 0)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Classes</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($yoga->yogaServices->where('is_active', true) as $service)
                                        <div
                                            class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">{{ $service->name }}
                                                </h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $service->description ?? 'Professional yoga class' }}</p>
                                                <div class="flex justify-between items-center mt-2">
                                                    <span class="text-sm text-gray-500">{{ $service->duration }}</span>
                                                    <span
                                                        class="font-bold text-blue-600">{{ $service->formatted_price }}</span>
                                                </div>
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
                                    $facilities = $yoga->detailConfig->facilities;
                                } elseif (is_string($yoga->detailConfig->facilities)) {
                                    $decoded = json_decode($yoga->detailConfig->facilities, true);
                                    if ($decoded) {
                                        $facilities = $decoded;
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
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Facilities</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach ($facilities as $facility)
                                        <div
                                            class="flex items-center space-x-3 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 hover:shadow-md transition-shadow">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <span
                                                class="font-medium text-gray-900">{{ is_array($facility) ? $facility['title'] : $facility }}</span>
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
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-lg">
                                        {{ $yoga->detailConfig->contact_person_name ?? 'Contact Person' }}
                                    </p>
                                    <p class="text-gray-600">
                                        {{ $yoga->detailConfig->contact_person_phone ?? ($yoga->noHP ?? 'N/A') }}
                                    </p>
                                </div>
                            </div>
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
                        <select name="selected_service" id="serviceSelect" required
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
                                <option value="{{ $service['title'] }}" data-price="{{ $service['price'] }}"
                                    data-category="{{ $service['category'] ?? 'General' }}">
                                    {{ $service['title'] }} - Rp {{ number_format($service['price'], 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Class Type (hidden field based on selected service) -->
                    <input type="hidden" name="class_type_booking" id="classTypeBooking" value="General">

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

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
                    // Allow users to access booking modal without authentication
                    // Authentication check moved to payment processing stage
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
                    if (!serviceSelect.value) {
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
                        .then(response => {
                            // Handle authentication error (401)
                            if (response.status === 401) {
                                response.json().then(errorData => {
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
                                });

                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;
                                return;
                            }

                            return response.json();
                        })
                        .then(result => {
                            if (!result) return; // Skip if we already handled auth error

                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;

                            if (result.success && result.payment_token && result.booking_id) {
                                closeYogaBookingModal();
                                // Show payment processing with Midtrans
                                loadMidtransSnap(result.payment_token, result.booking_id);
                            } else {
                                // Only show error message if it's not an authentication error
                                if (result.message && !result.message.includes('logged in')) {
                                    Swal.fire('Error', result.message ||
                                        'Booking failed. Please try again.', 'error');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Booking error:', error);
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
    <script type="text/javascript" src="{{ config('services.midtrans.snap_url') }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</x-app-layout>
