<x-app-layout>
    <style>
        .spa-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .spa-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.15);
        }

        .filter-active {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
        }

        .sort-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .sort-dropdown.active {
            max-height: 300px;
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
    </style>

    {{-- Modern SPA Booking Section --}}
    <div class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
        <!-- Hero Section with Search -->
        <section class="pt-32 pb-12 relative overflow-hidden bg-gradient-to-r from-emerald-600 to-teal-600">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10">
                <!-- Title -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-3">
                        SPA & WELLNESS
                    </h1>
                    <p class="text-lg lg:text-xl text-white/90 font-medium">
                        Discover {{ count($spaTotal) }}+ Premium Spa Experiences
                    </p>
                </div>

                <!-- Search Bar -->
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-2xl shadow-2xl p-6">
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Search Input -->
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Search Spa</label>
                                <div class="relative">
                                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <input type="text"
                                           id="search-input"
                                           placeholder="Search by name or location..."
                                           class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none">
                                </div>
                            </div>

                            <!-- Search Button -->
                            <div class="md:self-end">
                                <button id="search-btn" class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Search
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filter and Sort Section -->
        <main class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-12 pb-20">
            <!-- Filter Bar -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <!-- Results Count -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Showing</p>
                            <p class="text-lg font-bold text-gray-900"><span id="results-count">{{ count($spaTotal) }}</span> Spas</p>
                        </div>
                    </div>

                    <!-- Sort and Filter Controls -->
                    <div class="flex flex-wrap gap-3">
                        <!-- Sort Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl hover:border-emerald-500 transition-all">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Sort By</span>
                                <svg class="w-4 h-4 ml-2 text-gray-600" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-100 z-20">
                                <div class="p-2">
                                    <button onclick="sortSpas('rating-high')" class="w-full text-left px-4 py-3 rounded-lg hover:bg-emerald-50 transition-colors flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Highest Rated</span>
                                    </button>
                                    <button onclick="sortSpas('rating-low')" class="w-full text-left px-4 py-3 rounded-lg hover:bg-emerald-50 transition-colors flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Lowest Rated</span>
                                    </button>
                                    <button onclick="sortSpas('reviews-high')" class="w-full text-left px-4 py-3 rounded-lg hover:bg-emerald-50 transition-colors flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Most Reviewed</span>
                                    </button>
                                    <button onclick="sortSpas('name-az')" class="w-full text-left px-4 py-3 rounded-lg hover:bg-emerald-50 transition-colors flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Name (A-Z)</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="flex items-center gap-2">
                            <button onclick="filterByRating('all')" class="filter-btn px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl hover:border-emerald-500 transition-all text-sm font-semibold">
                                All
                            </button>
                            <button onclick="filterByRating(4)" class="filter-btn px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl hover:border-emerald-500 transition-all text-sm font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                4+
                            </button>
                        </div>

                        <!-- Clear Filters -->
                        <button id="clear-filters" onclick="clearAllFilters()" class="px-4 py-2.5 text-sm font-semibold text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all">
                            Clear All
                        </button>
                    </div>
                </div>
            </div>

            <!-- Spa Listings Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="spa-listings">
                @foreach ($spaTotal as $index => $spa)
                    <article class="spa-item spa-card bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-emerald-300 transition-all {{ $index >= 9 ? 'hidden' : '' }}"
                        data-spa-id="{{ $spa->id_spa }}"
                        data-spa-name="{{ strtolower($spa->nama) }}"
                        data-spa-location="{{ strtolower($spa->alamat) }}"
                        data-spa-rating="{{ $spa->average_rating }}"
                        data-spa-reviews="{{ $spa->testimonials_count }}">

                        <!-- Image Section -->
                        <div class="relative overflow-hidden group">
                            <a href="{{ route('spa.detail', $spa->id_spa) }}">
                                <img src="{{ $spa->image ? asset($spa->image) : asset('image/spa-default.jpg') }}"
                                    alt="{{ $spa->nama }}"
                                    class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-700"
                                    loading="{{ $index < 6 ? 'eager' : 'lazy' }}">

                                <!-- Category Badge -->
                                <div class="absolute top-3 left-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
                                    <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    SPA
                                </div>

                                <!-- Rating Badge -->
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-lg flex items-center space-x-1">
                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($spa->average_rating, 1) }}</span>
                                </div>
                            </a>
                        </div>

                        <!-- Content Section -->
                        <div class="p-5">
                            <a href="{{ route('spa.detail', $spa->id_spa) }}" class="block">
                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-emerald-600 transition-colors line-clamp-1">
                                    {{ $spa->nama }}
                                </h3>

                                <!-- Location -->
                                <div class="flex items-start text-gray-600 mb-4">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm line-clamp-2">{{ $spa->alamat }}</span>
                                </div>

                                <!-- Rating and Reviews -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center space-x-1">
                                        @php
                                            $avgRating = $spa->average_rating;
                                            $fullStars = floor($avgRating);
                                            $hasHalf = ($avgRating - $fullStars) >= 0.5;
                                        @endphp
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        @endfor
                                        @if ($hasHalf)
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20">
                                                <defs>
                                                    <linearGradient id="halfSpa{{ $spa->id_spa }}">
                                                        <stop offset="50%" stop-color="#FBBF24"/>
                                                        <stop offset="50%" stop-color="#D1D5DB"/>
                                                    </linearGradient>
                                                </defs>
                                                <path fill="url(#halfSpa{{ $spa->id_spa }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        @endif
                                        @for ($i = 0; $i < (5 - $fullStars - ($hasHalf ? 1 : 0)); $i++)
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium">{{ $spa->testimonials_count }} reviews</span>
                                </div>
                            </a>

                            <!-- View Details Button -->
                            <a href="{{ route('spa.detail', $spa->id_spa) }}" class="mt-4 block w-full py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all text-center shadow-md hover:shadow-lg">
                                View Details & Book
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Load More Button -->
            @if (count($spaTotal) > 6)
                <div class="flex justify-center mt-12">
                    <button id="load-more-spas"
                        class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-full hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        Load More Spas
                    </button>
                </div>
            @endif
        </main>

        <!-- Chatbot Component -->
        <x-chatbot-widget defaultCategory="Spa Information" />
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let allSpas = Array.from(document.querySelectorAll('.spa-item'));
                let filteredSpas = [...allSpas];
                let currentFilter = 'all';
                let currentSort = 'default';
                const loadMoreBtn = document.getElementById('load-more-spas');

                // Search Functionality
                const searchInput = document.getElementById('search-input');
                const searchBtn = document.getElementById('search-btn');

                function performSearch() {
                    const query = searchInput.value.toLowerCase().trim();

                    filteredSpas = allSpas.filter(spa => {
                        const name = spa.dataset.spaName;
                        const location = spa.dataset.spaLocation;
                        return name.includes(query) || location.includes(query);
                    });

                    applyCurrentFiltersAndSort();
                }

                searchBtn.addEventListener('click', performSearch);
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') performSearch();
                });

                // Debounced search on input
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(performSearch, 300);
                });

                // Sort Functionality
                window.sortSpas = function(sortType) {
                    currentSort = sortType;
                    const spasArray = [...filteredSpas];

                    switch(sortType) {
                        case 'rating-high':
                            spasArray.sort((a, b) => parseFloat(b.dataset.spaRating) - parseFloat(a.dataset.spaRating));
                            break;
                        case 'rating-low':
                            spasArray.sort((a, b) => parseFloat(a.dataset.spaRating) - parseFloat(b.dataset.spaRating));
                            break;
                        case 'reviews-high':
                            spasArray.sort((a, b) => parseInt(b.dataset.spaReviews) - parseInt(a.dataset.spaReviews));
                            break;
                        case 'name-az':
                            spasArray.sort((a, b) => a.dataset.spaName.localeCompare(b.dataset.spaName));
                            break;
                        default:
                            break;
                    }

                    filteredSpas = spasArray;
                    displaySpas();
                };

                // Filter by Rating
                window.filterByRating = function(minRating) {
                    currentFilter = minRating;

                    // Update filter button styles
                    document.querySelectorAll('.filter-btn').forEach(btn => {
                        btn.classList.remove('filter-active');
                        btn.classList.add('bg-white', 'border-gray-200');
                    });

                    event.target.classList.add('filter-active');
                    event.target.classList.remove('bg-white', 'border-gray-200');

                    applyCurrentFiltersAndSort();
                };

                function applyCurrentFiltersAndSort() {
                    // Apply search first
                    let spas = [...filteredSpas];

                    // Then apply rating filter
                    if (currentFilter !== 'all') {
                        spas = spas.filter(spa => parseFloat(spa.dataset.spaRating) >= parseFloat(currentFilter));
                    }

                    // Then apply sort
                    if (currentSort === 'rating-high') {
                        spas.sort((a, b) => parseFloat(b.dataset.spaRating) - parseFloat(a.dataset.spaRating));
                    } else if (currentSort === 'rating-low') {
                        spas.sort((a, b) => parseFloat(a.dataset.spaRating) - parseFloat(b.dataset.spaRating));
                    } else if (currentSort === 'reviews-high') {
                        spas.sort((a, b) => parseInt(b.dataset.spaReviews) - parseInt(a.dataset.spaReviews));
                    } else if (currentSort === 'name-az') {
                        spas.sort((a, b) => a.dataset.spaName.localeCompare(b.dataset.spaName));
                    }

                    filteredSpas = spas;
                    displaySpas();
                }

                function displaySpas() {
                    const container = document.getElementById('spa-listings');

                    // Hide all spas first
                    allSpas.forEach(spa => {
                        spa.classList.add('hidden');
                        spa.style.order = '';
                    });

                    // Show and reorder filtered spas
                    filteredSpas.forEach((spa, index) => {
                        spa.style.order = index;
                        if (index < 9) {
                            spa.classList.remove('hidden');
                        }
                    });

                    // Update results count
                    document.getElementById('results-count').textContent = filteredSpas.length;

                    // Update load more button
                    if (loadMoreBtn) {
                        loadMoreBtn.style.display = filteredSpas.length > 9 ? 'block' : 'none';
                    }

                    // Smooth scroll to results
                    if (window.scrollY > 400) {
                        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }

                // Clear All Filters
                window.clearAllFilters = function() {
                    searchInput.value = '';
                    currentFilter = 'all';
                    currentSort = 'default';
                    filteredSpas = [...allSpas];

                    // Reset filter buttons
                    document.querySelectorAll('.filter-btn').forEach(btn => {
                        btn.classList.remove('filter-active');
                        btn.classList.add('bg-white', 'border-gray-200');
                    });

                    displaySpas();
                };

                // Load More Functionality
                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', function() {
                        const hiddenSpas = filteredSpas.filter((spa, index) => {
                            return index >= 9 && spa.classList.contains('hidden');
                        });

                        const nextBatch = hiddenSpas.slice(0, 9);
                        nextBatch.forEach(spa => spa.classList.remove('hidden'));

                        if (document.querySelectorAll('.spa-item:not(.hidden)').length >= filteredSpas.length) {
                            loadMoreBtn.style.display = 'none';
                        }
                    });
                }

                // Initialize display
                displaySpas();
            });
        </script>
    @endpush
</x-app-layout>
