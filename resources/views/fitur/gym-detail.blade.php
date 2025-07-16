<x-app-layout>
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom CSS -->
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
                                class="w-full h-96 object-cover rounded-t-2xl">
                        </div>

                        <!-- Thumbnail Images -->
                        <div class="p-4">
                            <div class="grid grid-cols-5 gap-3">
                                @foreach ($galleryImages as $index => $image)
                                    <img src="{{ asset($image) }}" alt="Image {{ $index + 1 }}"
                                        class="thumbnail w-full h-20 object-cover rounded-xl cursor-pointer border-2 transition-all duration-200 {{ $index === 0 ? 'border-blue-500 opacity-100' : 'border-gray-200 opacity-70 hover:opacity-100 hover:border-blue-300' }}">
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Gym Information -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <!-- Title and Location -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $gym->nama }}</h1>
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

                        <!-- Services Section (Original + Additional) -->
                        @if ($gym->all_services && count($gym->all_services) > 0)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Services</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($gym->all_services as $service)
                                        <div
                                            class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                                            <div class="flex-shrink-0">
                                                @if (isset($service['image']) && $service['image'])
                                                    <img src="{{ asset($service['image']) }}"
                                                        alt="{{ $service['name'] }}"
                                                        class="w-16 h-16 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
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
                                                    {{ $service['name'] ?? 'Service' }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $service['description'] ?? 'Professional gym service' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Facilities Section -->
                        @if ($gym->gymDetail && $gym->gymDetail->facilities && count($gym->gymDetail->facilities) > 0)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Facilities</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach ($gym->gymDetail->facilities as $facility)
                                        <div
                                            class="flex items-center space-x-3 p-4 rounded-xl bg-gradient-to-r from-green-50 to-blue-50 border border-green-100 hover:shadow-md transition-shadow">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $facility }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- About This Gym Section -->
                        @if ($gym->gymDetail && $gym->gymDetail->about_gym)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Gym</h2>
                                <div class="prose text-gray-600 text-lg leading-relaxed">
                                    <p>{{ $gym->gymDetail->about_gym }}</p>
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
                                    class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-lg">
                                        {{ $gym->gymDetail->contact_person_name ?? 'Gym Manager' }}
                                    </p>
                                    <p class="text-gray-600">
                                        {{ $gym->gymDetail->contact_person_phone ?? 'Contact Available' }}
                                    </p>
                                </div>
                            </div>
                            @if ($gym->gymDetail && $gym->gymDetail->contact_person_phone)
                                <a href="tel:{{ $gym->gymDetail->contact_person_phone }}"
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
                                <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                Location
                            </h3>
                            @if ($gym->gymDetail && $gym->gymDetail->location_maps)
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
        </div>
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
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

            // Booking modal functionality
            const bookingBtn = document.querySelector('.bookingBtn');
            const gymBookingModal = document.getElementById('gymBookingModal');
            const gymBookingForm = document.getElementById('gymBookingForm');
            const serviceSelect = document.getElementById('serviceSelect');
            const serviceDetails = document.getElementById('service-details');

            let gymServices = [];

            if (bookingBtn) {
                bookingBtn.addEventListener('click', function() {
                    // Allow users to access booking modal without authentication
                    // Authentication check moved to payment processing stage
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
                fetch(`/gym/${gymId}/services`)
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

                    // Submit booking request
                    fetch('/gym/booking', {
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
                                closeGymBookingModal();
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
</x-app-layout>
