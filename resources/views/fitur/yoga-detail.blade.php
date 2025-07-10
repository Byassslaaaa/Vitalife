<x-app-layout>
    <!-- Hero Section with Image Gallery -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 pt-24 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Side - Image Gallery -->
                <div class="lg:col-span-2">
                    <!-- Main Image -->
                    <div class="mb-4">
                        <img id="mainImage" src="{{ asset($yoga->image) }}" alt="{{ $yoga->nama }}" 
                             class="w-full h-96 object-cover rounded-2xl shadow-lg">
                    </div>
                    
                    <!-- Thumbnail Images -->
                    <div class="grid grid-cols-4 gap-3">
                        <img src="{{ asset($yoga->image) }}" alt="Image 1" 
                             class="thumbnail w-full h-24 object-cover rounded-lg cursor-pointer border-2 border-blue-500 opacity-100">
                        <img src="{{ asset($yoga->image) }}" alt="Image 2" 
                             class="thumbnail w-full h-24 object-cover rounded-lg cursor-pointer border-2 border-transparent opacity-70 hover:opacity-100">
                        <img src="{{ asset($yoga->image) }}" alt="Image 3" 
                             class="thumbnail w-full h-24 object-cover rounded-lg cursor-pointer border-2 border-transparent opacity-70 hover:opacity-100">
                        <img src="{{ asset($yoga->image) }}" alt="Image 4" 
                             class="thumbnail w-full h-24 object-cover rounded-lg cursor-pointer border-2 border-transparent opacity-70 hover:opacity-100">
                    </div>
                </div>
                
                <!-- Right Side - Booking Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <!-- Booking Policy Header -->
                        <div class="text-center mb-6">
                            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-semibold text-gray-700">BOOKING POLICY</span>
                                </div>
                                <p class="text-sm text-gray-600">YOUR WELLNESS PLANS</p>
                            </div>
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Phone :</span>
                                <span class="font-semibold text-gray-900">{{ $yoga->noHP }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Price :</span>
                                <span class="font-bold text-blue-600 text-lg">Rp {{ number_format($yoga->harga, 0, ',', '.') }}</span>
                            </div>
                            @if(isset($yoga->class_type))
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Class Type :</span>
                                    <span class="font-semibold text-gray-900 capitalize">{{ $yoga->class_type }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Booking Button -->
                        <button class="bookingBtn w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-6 rounded-lg transition duration-300"
                            data-yoga-id="{{ $yoga->id_yoga }}">
                            Booking Online
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detail Information Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- Yoga Name and Location -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $yoga->nama }}</h1>
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ $yoga->alamat }}</span>
                </div>
            </div>
            
            <!-- Facilities Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Facilities</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-6 bg-orange-50 rounded-xl">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Traditional Massage</h3>
                        <p class="text-gray-600">Traditional massage to relieve tension and stress from your body</p>
                    </div>
                    <div class="text-center p-6 bg-purple-50 rounded-xl">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Reflexology</h3>
                        <p class="text-gray-600">Stimulate foot reflex points to boost health and wellness</p>
                    </div>
                    <div class="text-center p-6 bg-green-50 rounded-xl">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Thai Massage</h3>
                        <p class="text-gray-600">Thai massage with pressure and stretch techniques</p>
                    </div>
                </div>
            </div>
            
            <!-- Opening Hours -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Opening Hours</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <div class="text-center bg-gray-50 p-4 rounded-lg">
                            <p class="font-semibold text-gray-700">{{ $hari }}</p>
                            @if(isset($yoga->waktuBuka[$hari]) && !empty($yoga->waktuBuka[$hari]))
                                <p class="text-sm text-gray-500">{{ $yoga->waktuBuka[$hari] }}</p>
                            @else
                                <p class="text-sm text-gray-500">Tutup</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Location Map -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Location</h2>
                <div id="map-{{$yoga->id_yoga}}" class="w-full h-96 bg-gray-100 rounded-lg"></div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <!-- Modal Booking Yoga -->
    <div id="yogaBookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-900"
                onclick="closeYogaBookingModal()">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Booking Yoga</h3>
                <form id="yogaBookingForm" class="space-y-4">
                    <input type="hidden" id="modal-yoga-id" name="yoga_id" value="{{ $yoga->id_yoga }}">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" id="customer_name" name="customer_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="customer_email" name="customer_email" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700">No. HP</label>
                        <input type="text" id="customer_phone" name="customer_phone" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="booking_date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" id="booking_date" name="booking_date" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="booking_time" class="block text-sm font-medium text-gray-700">Jam</label>
                        <input type="time" id="booking_time" name="booking_time" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="class_type_booking" class="block text-sm font-medium text-gray-700">Tipe Kelas</label>
                        <select id="class_type_booking" name="class_type_booking" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Pilih Tipe</option>
                            <option value="offline">Offline</option>
                            <option value="online">Online</option>
                            <option value="private">Private</option>
                        </select>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea id="notes" name="notes" rows="2"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition duration-300">
                        Booking & Lanjut Bayar
                    </button>
                </form>
            </div>
        </div>
    </div>

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
                        thumb.classList.add('border-transparent', 'opacity-70');
                    });
                    
                    // Add active state to clicked thumbnail
                    this.classList.remove('border-transparent', 'opacity-70');
                    this.classList.add('border-blue-500', 'opacity-100');
                    
                    // Update main image
                    mainImage.src = this.src;
                });
            });

            // Maps functionality for yoga
            @if(isset($yoga))
            try {
                const mapContainer = document.getElementById('map-{{$yoga->id_yoga}}');
                if (mapContainer) {
                    const iframe = document.createElement('iframe');
                    iframe.src = "{{$yoga->maps}}";
                    iframe.width = "100%";
                    iframe.height = "100%";
                    iframe.style.border = "none";
                    iframe.allowFullscreen = true;
                    iframe.loading = "lazy";
                    iframe.referrerPolicy = "no-referrer-when-downgrade";
                    
                    iframe.onerror = function() {
                        createFallbackLink(mapContainer, "{{$yoga->maps}}");
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

            // Booking functionality
            const bookingBtn = document.querySelector('.bookingBtn');
            const yogaBookingModal = document.getElementById('yogaBookingModal');
            const yogaBookingForm = document.getElementById('yogaBookingForm');

            if (bookingBtn) {
                bookingBtn.addEventListener('click', function() {
                    openYogaBookingModal();
                });
            }

            function openYogaBookingModal() {
                yogaBookingModal.classList.remove('hidden');
            }

            window.closeYogaBookingModal = function() {
                yogaBookingModal.classList.add('hidden');
                yogaBookingForm.reset();
            }

            if (yogaBookingForm) {
                yogaBookingForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(yogaBookingForm);
                    const data = {};
                    formData.forEach((value, key) => { data[key] = value; });

                    fetch('/yoga/booking', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    })
                    .then(res => res.json())
                    .then(response => {
                        if (response.success && response.payment_token && response.booking_id) {
                            closeYogaBookingModal();
                            // Show payment processing with Midtrans
                            loadMidtransSnap(response.payment_token, response.booking_id);
                        } else {
                            Swal.fire('Error', response.message || 'Gagal booking. Silakan coba lagi.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    });
                });
            }

            function loadMidtransSnap(token, bookingId) {
                if (!window.snap) {
                    Swal.fire('Error', 'Midtrans Snap belum termuat. Coba refresh halaman.', 'error');
                    return;
                }
                window.snap.pay(token, {
                    onSuccess: function(result){
                        Swal.fire('Pembayaran Berhasil', 'Booking yoga Anda telah dibayar!', 'success')
                            .then(() => window.location.reload());
                    },
                    onPending: function(result){
                        Swal.fire('Pembayaran Pending', 'Pembayaran Anda sedang diproses.', 'info');
                    },
                    onError: function(result){
                        Swal.fire('Error', 'Pembayaran gagal. Silakan coba lagi.', 'error');
                    },
                    onClose: function(){
                        Swal.fire('Dibatalkan', 'Anda menutup pembayaran tanpa menyelesaikan.', 'warning');
                    }
                });
            }
        });
    </script>
    <script type="text/javascript"
        src="{{ config('services.midtrans.snap_url') }}"
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
</x-app-layout>
