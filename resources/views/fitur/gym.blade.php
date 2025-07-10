<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/gym-booking.css') }}">
    
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-green-50 to-emerald-100 pt-24 pb-16" aria-labelledby="hero-title">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-left mb-12">
                <h1 id="hero-title" class="text-4xl font-bold text-gray-900 mb-4">GYM</h1>
                <p class="text-xl text-gray-600">Finding GYM location nearby you</p>
            </div>
        </div>
    </section>

    <!-- Gym Listings Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="gym-listings">
            @foreach ($gymTotal as $index => $gym)
                <article class="gym-item bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 {{ $index >= 6 ? 'hidden' : '' }}" 
                         data-gym-id="{{ $gym->id_gym }}">
                    
                    <!-- Make the entire card clickable -->
                    <a href="{{ route('gym.detail', $gym->id_gym) }}" class="block">
                        <!-- Header -->
                        <header class="p-6 border-b border-gray-100">
                            <h2 id="gym-name-{{ $gym->id_gym }}" class="text-xl font-bold text-gray-900 mb-2">{{ $gym->nama }}</h2>
                            <div class="flex items-center text-gray-600 mb-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <address class="text-sm not-italic">{{ $gym->alamat }}</address>
                            </div>
                        </header>

                        <!-- Main Image -->
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="{{ asset($gym->image) }}" 
                                alt="{{ $gym->nama }} gym interior" 
                                class="w-full h-48 object-cover"
                                loading="{{ $index < 3 ? 'eager' : 'lazy' }}">
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Services Section with Circular Images -->
                            <section class="mb-6" aria-labelledby="services-{{ $gym->id_gym }}">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 id="services-{{ $gym->id_gym }}" class="text-lg font-bold text-gray-900">Services</h3>
                                    <span class="text-sm text-gray-500 hover:text-gray-700 cursor-pointer">View all</span>
                                </div>
                                
                                @if($gym->services && is_array($gym->services) && count($gym->services) >= 3)
                                    <div class="grid grid-cols-3 gap-4">
                                        @foreach(array_slice($gym->services, 0, 3) as $index => $service)
                                            <div class="text-center">
                                                <!-- Circular Image -->
                                                <div class="w-16 h-16 mx-auto mb-3 rounded-full overflow-hidden bg-gray-100 shadow-md">
                                                    @if(isset($service['image']) && $service['image'])
                                                        <img src="{{ asset($service['image']) }}" 
                                                             alt="{{ $service['name'] ?? 'Service ' . ($index + 1) }}" 
                                                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-200">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
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
                                                    {{ Str::limit($service['description'] ?? 'Professional gym service', 40) }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Default Services Display if no services data -->
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="text-center">
                                            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center shadow-md">
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Weight Training</h4>
                                            <p class="text-xs text-gray-600">Complete weight training equipment</p>
                                        </div>
                                        <div class="text-center">
                                            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center shadow-md">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Cardio</h4>
                                            <p class="text-xs text-gray-600">Modern cardio machines available</p>
                                        </div>
                                        <div class="text-center">
                                            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center shadow-md">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Personal Trainer</h4>
                                            <p class="text-xs text-gray-600">Professional personal trainers</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Divider Line -->
                                <div class="border-t border-gray-200 mt-6 mb-4"></div>
                            </section>

                            <!-- Opening Work Section -->
                            <section class="text-center">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Opening Work</h3>
                                <div class="flex items-center justify-center">
                                    @if($gym->is_open)
                                        <div class="flex items-center text-green-600">
                                            <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                            <span class="text-sm font-medium">{{ $gym->open_status_with_time }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-red-600">
                                            <div class="w-3 h-3 bg-red-400 rounded-full mr-2"></div>
                                            <span class="text-sm font-medium">{{ $gym->open_status_with_time }}</span>
                                        </div>
                                    @endif
                                </div>
                            </section>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <!-- Load More Button -->
        @if(count($gymTotal) > 6)
        <div class="text-center mt-12">
            <button id="loadMoreBtn" 
                    class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-8 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    aria-describedby="load-more-desc">
                Load More Gyms
            </button>
            <span id="load-more-desc" class="sr-only">Load more gym listings</span>
        </div>
        @endif
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        class GymListingManager {
            constructor() {
                this.currentlyShown = 6;
                this.totalItems = document.querySelectorAll('.gym-item').length;
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
                const hiddenItems = document.querySelectorAll('.gym-item.hidden');
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
                        title: 'Gym Found!',
                        text: `Search results for: ${searchQuery}`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else if (searchStatus === 'not_found') {
                    Swal.fire({
                        title: 'No Gym Found',
                        text: `No results found for: ${searchQuery}`,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new GymListingManager();
        });
    </script>
</x-app-layout>