<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-left mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">YOGA</h1>
                <p class="text-xl text-gray-600">Finding Yoga location nearby you</p>
            </div>
        </div>
    </div>

    <!-- Yoga Listings Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div id="yoga-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($yogaTotal as $index => $yoga)
                <div
                    class="yoga-item bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 {{ $index >= 6 ? 'hidden' : '' }}">
                    <!-- Make the entire card clickable -->
                    <a href="{{ route('yoga.detail', $yoga->id_yoga) }}" class="block h-full">
                        <!-- Header -->
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $yoga->nama }}</h3>
                            <div class="flex items-center text-gray-600 mb-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm">{{ $yoga->alamat }}</span>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="{{ asset($yoga->image) }}" alt="{{ $yoga->nama }}"
                                class="w-full h-48 object-cover">
                        </div>

                        <!-- Facilities -->
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Facilities</h4>
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium text-gray-900">Traditional</p>
                                    <p class="text-xs font-medium text-gray-900">Massage</p>
                                    <p class="text-xs text-gray-500 mt-1">Traditional massage to relieve tension</p>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium text-gray-900">Reflexology</p>
                                    <p class="text-xs text-gray-500 mt-1">Stimulate foot reflex points to boost health
                                    </p>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium text-gray-900">Thai Massage</p>
                                    <p class="text-xs text-gray-500 mt-1">Thai massage with pressure and stretch</p>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Phone:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $yoga->noHP }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Price:</span>
                                    <span class="text-lg font-bold text-blue-600">Rp
                                        {{ number_format($yoga->harga, 0, ',', '.') }}</span>
                                </div>
                                @if (isset($yoga->class_type))
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Class Type:</span>
                                        <span
                                            class="text-sm font-medium text-gray-900 capitalize">{{ $yoga->class_type }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Opening Hours -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h5 class="text-sm font-semibold text-gray-900">Opening Work</h5>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                                    <span class="text-sm">Open 24 Hours</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Load More Button -->
        @if (count($yogaTotal) > 6)
            <div class="text-center mt-12">
                <button id="loadMoreBtn"
                    class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-8 rounded-lg transition duration-300">
                    Load More
                </button>
            </div>
        @endif
    </div>

    @include('layouts.footer')

    <!-- Modal Booking Yoga (Keep existing modal) -->
    <div id="yogaBookingModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-900" onclick="closeYogaBookingModal()">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Booking Yoga</h3>
                <form id="yogaBookingForm" class="space-y-4">
                    <input type="hidden" id="modal-yoga-id" name="yoga_id">
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
                        <label for="class_type_booking" class="block text-sm font-medium text-gray-700">Tipe
                            Kelas</label>
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
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        let currentlyShown = 6;
        const totalItems = document.querySelectorAll('.yoga-item').length;

        // Load More functionality
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const hiddenItems = document.querySelectorAll('.yoga-item.hidden');
                const itemsToShow = Math.min(6, hiddenItems.length);

                for (let i = 0; i < itemsToShow; i++) {
                    hiddenItems[i].classList.remove('hidden');
                }

                currentlyShown += itemsToShow;

                // Hide Load More button if all items are shown
                if (currentlyShown >= totalItems) {
                    loadMoreBtn.style.display = 'none';
                }
            });
        }

        // Cek status pencarian
        const searchStatus = '{{ session('search_status') }}';
        const searchQuery = '{{ session('search_query') }}';

        if (searchStatus === 'success') {
            Swal.fire({
                title: 'Yoga Ditemukan!',
                text: `Hasil pencarian untuk ${searchQuery}`,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        } else if (searchStatus === 'not_found') {
            Swal.fire({
                title: 'Yoga Tidak Ditemukan',
                text: `Tidak ada hasil untuk pencarian dengan kriteria: ${searchQuery}`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

        // Booking Yoga Button functionality is preserved in the detail page
    });
</script>
<script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
    data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // For each yoga item, try to create a direct iframe or fallback to a text link
        @foreach ($yogaTotal as $yoga)
            try {
                const mapContainer = document.getElementById('map-{{ $yoga->id_yoga }}');
                if (mapContainer) {
                    // Try to create the iframe
                    const iframe = document.createElement('iframe');
                    iframe.src = "{{ $yoga->maps }}";
                    iframe.width = "100%";
                    iframe.height = "100%";
                    iframe.style.border = "none";
                    iframe.allowFullscreen = true;
                    iframe.loading = "lazy";
                    iframe.referrerPolicy = "no-referrer-when-downgrade";

                    // Handle errors
                    iframe.onerror = function() {
                        createFallbackLink(mapContainer, "{{ $yoga->maps }}");
                    };

                    // Try to detect if the iframe loaded properly
                    iframe.onload = function() {
                        if (!this.contentWindow || !this.contentWindow.location || this.contentWindow
                            .location.href === "about:blank") {
                            createFallbackLink(mapContainer, "{{ $yoga->maps }}");
                        }
                    };

                    mapContainer.appendChild(iframe);
                }
            } catch (e) {
                console.error("Error creating map:", e);
                if (mapContainer) {
                    createFallbackLink(mapContainer, "{{ $yoga->maps }}");
                }
            }
        @endforeach

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
    });
</script>
