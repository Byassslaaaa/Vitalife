<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/spa-booking.css') }}">
    
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-pink-50 to-rose-100 pt-24 pb-16" aria-labelledby="hero-title">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-left mb-12">
                <h1 id="hero-title" class="text-4xl font-bold text-gray-900 mb-4">SPA</h1>
                <p class="text-xl text-gray-600">Finding SPA location nearby you</p>
            </div>
        </div>
    </section>

    <!-- Spa Listings Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="spa-listings">
            @foreach ($spaTotal as $index => $spa)
                <article class="spa-item bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 {{ $index >= 6 ? 'hidden' : '' }}" 
                         data-spa-id="{{ $spa->id_spa }}">
                    
                    <!-- Make the entire card clickable -->
                    <a href="{{ route('spa.detail', $spa->id_spa) }}" class="block">
                        <!-- Header -->
                        <header class="p-6 border-b border-gray-100">
                            <h2 id="spa-name-{{ $spa->id_spa }}" class="text-xl font-bold text-gray-900 mb-2">{{ $spa->nama }}</h2>
                            <div class="flex items-center text-gray-600 mb-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <address class="text-sm not-italic">{{ $spa->alamat }}</address>
                            </div>
                        </header>

                        <!-- Main Image -->
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="{{ $spa->image ? asset($spa->image) : asset('image/spa-default.jpg') }}" 
                                alt="{{ $spa->nama }} spa interior" 
                                class="w-full h-48 object-cover"
                                loading="{{ $index < 3 ? 'eager' : 'lazy' }}">
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Services Section with Circular Images -->
                            <section class="mb-6" aria-labelledby="services-{{ $spa->id_spa }}">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 id="services-{{ $spa->id_spa }}" class="text-lg font-bold text-gray-900">Services</h3>
                                    <span class="text-sm text-gray-500 hover:text-gray-700 cursor-pointer">View all</span>
                                </div>
                                
                                @if($spa->services && is_array($spa->services) && count($spa->services) >= 3)
                                    <div class="grid grid-cols-3 gap-4">
                                        @foreach(array_slice($spa->services, 0, 3) as $index => $service)
                                            <div class="text-center">
                                                <!-- Circular Image -->
                                                <div class="w-16 h-16 mx-auto mb-3 rounded-full overflow-hidden bg-gray-100 shadow-md">
                                                    @if(isset($service['image']) && $service['image'])
                                                        <img src="{{ asset($service['image']) }}" 
                                                             alt="{{ $service['name'] ?? 'Service ' . ($index + 1) }}" 
                                                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-200">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Service Title -->
                                                <h4 class="text-sm font-semibold text-gray-900 mb-1 leading-tight">
                                                    {{ $service['name'] ?? 'Service ' . ($index + 1) }}
                                                </h4>
                                                
                                                <!-- Service Description -->
                                                <p class="text-xs text-gray-600 leading-relaxed">
                                                    {{ Str::limit($service['description'] ?? 'Professional spa service', 40) }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($spa->spaServices && $spa->spaServices->count() >= 3)
                                    <div class="grid grid-cols-3 gap-4">
                                        @foreach($spa->spaServices->take(3) as $index => $service)
                                            <div class="text-center">
                                                <!-- Circular Image -->
                                                <div class="w-16 h-16 mx-auto mb-3 rounded-full overflow-hidden bg-gray-100 shadow-md">
                                                    @if($service->image)
                                                        <img src="{{ asset($service->image) }}" 
                                                             alt="{{ $service->name }}" 
                                                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-200">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Service Title -->
                                                <h4 class="text-sm font-semibold text-gray-900 mb-1 leading-tight">
                                                    {{ $service->name }}
                                                </h4>
                                                
                                                <!-- Service Description -->
                                                <p class="text-xs text-gray-600 leading-relaxed">
                                                    {{ Str::limit($service->description ?? 'Professional spa service', 40) }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Default Services Display if no services data -->
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="text-center">
                                            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center shadow-md">
                                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Body Massage</h4>
                                            <p class="text-xs text-gray-600">Traditional massage to relieve tension</p>
                                        </div>
                                        <div class="text-center">
                                            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center shadow-md">
                                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Facial Treatment</h4>
                                            <p class="text-xs text-gray-600">Rejuvenating facial care treatment</p>
                                        </div>
                                        <div class="text-center">
                                            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center shadow-md">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Body Scrub</h4>
                                            <p class="text-xs text-gray-600">Exfoliating body treatment</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Divider Line -->
                                <div class="border-t border-gray-200 mt-6 mb-4"></div>
                            </section>

                            <!-- Opening Hours Section -->
                            <section class="text-center">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Opening Hours</h3>
                                <div class="flex items-center justify-center">
                                    @if($spa->is_open)
                                        <div class="flex items-center text-green-600">
                                            <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                            <span class="text-sm font-medium">
                                                @if($spa->waktuBuka && is_array($spa->waktuBuka))
                                                    @php
                                                        $today = now()->format('l');
                                                        $dayMapping = [
                                                            'Monday' => 'Senin',
                                                            'Tuesday' => 'Selasa', 
                                                            'Wednesday' => 'Rabu',
                                                            'Thursday' => 'Kamis',
                                                            'Friday' => 'Jumat',
                                                            'Saturday' => 'Sabtu',
                                                            'Sunday' => 'Minggu'
                                                        ];
                                                        $indonesianDay = $dayMapping[$today] ?? $today;
                                                        $todayHours = $spa->waktuBuka[$indonesianDay] ?? 'Contact for hours';
                                                    @endphp
                                                    Open Today: {{ $todayHours }}
                                                @else
                                                    Open - Contact for hours
                                                @endif
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-red-600">
                                            <div class="w-3 h-3 bg-red-400 rounded-full mr-2"></div>
                                            <span class="text-sm font-medium">Currently Closed</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Contact Info -->
                                @if($spa->noHP)
                                    <div class="mt-2 text-xs text-gray-500">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $spa->noHP }}
                                    </div>
                                @endif
                            </section>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <!-- Load More Button -->
        @if(count($spaTotal) > 6)
        <div class="text-center mt-12">
            <button id="loadMoreBtn" 
                    class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-8 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    aria-describedby="load-more-desc">
                Load More Spas
            </button>
            <span id="load-more-desc" class="sr-only">Load more spa listings</span>
        </div>
        @endif
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/spa-booking.js') }}"></script>
    <script>
        class SpaListingManager {
            constructor() {
                this.currentlyShown = 6;
                this.totalItems = document.querySelectorAll('.spa-item').length;
                this.init();
            }

            init() {
                this.setupLoadMore();
                this.handleSearchStatus();
            }

            setupLoadMore() {
                const loadMoreBtn = document.getElementById('loadMoreBtn');
                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', this.loadMore.bind(this));
                }
            }

            loadMore() {
                const hiddenItems = document.querySelectorAll('.spa-item.hidden');
                const itemsToShow = Math.min(6, hiddenItems.length);
                
                for (let i = 0; i < itemsToShow; i++) {
                    hiddenItems[i].classList.remove('hidden');
                }
                
                this.currentlyShown += itemsToShow;
                
                // Hide Load More button if all items are shown
                if (this.currentlyShown >= this.totalItems) {
                    document.getElementById('loadMoreBtn').style.display = 'none';
                }
            }

            handleSearchStatus() {
                const searchStatus = '{{ session('search_status') }}';
                const searchQuery = '{{ session('search_query') }}';

                if (searchStatus === 'success') {
                    Swal.fire({
                        title: 'Spa Found!',
                        text: `Search results for: ${searchQuery}`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else if (searchStatus === 'not_found') {
                    Swal.fire({
                        title: 'No Spa Found',
                        text: `No results found for: ${searchQuery}`,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new SpaListingManager();
        });
    </script>
</x-app-layout>
