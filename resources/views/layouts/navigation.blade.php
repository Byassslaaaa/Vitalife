<nav x-data="{ open: false }"
    class="bg-white border-b border-gray-100 fixed top-0 left-0 right-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('image/Logo-Healife.png') }}" alt="HeaLife Logo" class="h-12 w-auto">
                </a>
            </div>

            <!-- Center Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('dashboard') }}"
                    class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-gray-900' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                    Home
                </a>

                <x-dropdown align="center" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center text-sm font-medium {{ request()->routeIs('spa.index') || request()->routeIs('yoga.index') || request()->routeIs('gym.index') ? 'text-gray-900' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            Wellness
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('spa.index')">
                            {{ __('Spa Centers') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('yoga.index')">
                            {{ __('Yoga Studios') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('gym.index')">
                            {{ __('Fitness Gyms') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>

                <a href="{{ route('voucher') }}"
                    class="text-sm font-medium {{ request()->routeIs('voucher') ? 'text-gray-900' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                    Vouchers
                </a>

                <a href="{{ route('chat') }}"
                    class="text-sm font-medium {{ request()->routeIs('chat') ? 'text-gray-900' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                    Chat
                </a>

                <a href="{{ route('aboutus') }}"
                    class="text-sm font-medium {{ request()->routeIs('aboutus') ? 'text-gray-900' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                    About
                </a>
            </div>

            <!-- Right Side - Search, User Menu, and Book Button -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Search Icon -->
                <button @click="$dispatch('open-search')" class="p-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                @auth
                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>

                    <!-- Book Now Button -->
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition-colors">
                        Book Now
                    </a>
                @else
                    <!-- Login Button -->
                    <a href="{{ route('login') }}"
                        class="px-6 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition-colors">
                        Login
                    </a>
                @endauth
            </div>

            <!-- Hamburger Menu for Mobile -->
            <div class="flex items-center md:hidden">
                <button @click="open = ! open"
                    class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden border-t border-gray-100">
        <div class="px-4 pt-2 pb-3 space-y-1 bg-white">
            <a href="{{ route('dashboard') }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'text-gray-900 bg-gray-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Home
            </a>

            <!-- Wellness Submenu -->
            <div class="pl-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Wellness</p>
                <a href="{{ route('spa.index') }}"
                    class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('spa.index') ? 'text-gray-900 bg-gray-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    Spa Centers
                </a>
                <a href="{{ route('yoga.index') }}"
                    class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('yoga.index') ? 'text-gray-900 bg-gray-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    Yoga Studios
                </a>
                <a href="{{ route('gym.index') }}"
                    class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('gym.index') ? 'text-gray-900 bg-gray-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    Fitness Gyms
                </a>
            </div>

            <a href="{{ route('voucher') }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('voucher') ? 'text-gray-900 bg-gray-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Vouchers
            </a>

            <a href="{{ route('chat') }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('chat') ? 'text-gray-900 bg-gray-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Chat
            </a>

            <a href="{{ route('aboutus') }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('aboutus') ? 'text-gray-900 bg-gray-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                About
            </a>
        </div>

        <!-- Responsive User Menu -->
        @auth
            <div class="pt-4 pb-3 border-t border-gray-100 bg-white">
                <div class="px-4">
                    <div class="text-base font-medium text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-100 bg-white px-4">
                <a href="{{ route('login') }}"
                    class="block w-full text-center px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition-colors">
                    Login
                </a>
            </div>
        @endauth
    </div>

    <!-- Global Search Modal -->
    <div x-data="searchModal()"
         @open-search.window="openSearch()"
         @keydown.escape.window="closeSearch()"
         x-show="isOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity" @click="closeSearch()"></div>

        <!-- Modal Content -->
        <div class="flex items-start justify-center min-h-screen pt-16 px-4 pb-20">
            <div @click.away="closeSearch()"
                 class="relative bg-white rounded-2xl w-full max-w-2xl transform transition-all shadow-2xl"
                 x-transition:enter="transition ease-out duration-200 delay-100"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                <!-- Search Header -->
                <div class="relative p-6 pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Search Wellness Centers</h3>
                        <button @click="closeSearch()" class="p-2 -mr-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            x-model="query"
                            @input.debounce.300ms="performSearch()"
                            @keydown.enter="performSearch()"
                            placeholder="Search spa, gym, or yoga studio..."
                            class="w-full pl-11 pr-4 py-3 text-base bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-gray-900 focus:ring-2 focus:ring-gray-900 focus:ring-opacity-20 transition-all outline-none"
                            autofocus>
                    </div>
                </div>

                <!-- Search Results -->
                <div class="max-h-[440px] overflow-y-auto px-6 pb-6">
                    <!-- Loading State -->
                    <div x-show="loading" class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                            <svg class="animate-spin h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Searching...</p>
                    </div>

                    <!-- No Query State -->
                    <div x-show="!loading && query.length === 0" class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-2">Find Your Wellness Destination</h4>
                        <p class="text-sm text-gray-500 max-w-sm mx-auto">Search for spa centers, yoga studios, and fitness gyms across Indonesia</p>

                        <!-- Quick Categories -->
                        <div class="mt-6 flex items-center justify-center gap-2">
                            <span class="text-xs text-gray-500">Try:</span>
                            <button @click="query = 'spa'; performSearch();" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">Spa</button>
                            <button @click="query = 'yoga'; performSearch();" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">Yoga</button>
                            <button @click="query = 'gym'; performSearch();" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">Gym</button>
                        </div>
                    </div>

                    <!-- No Results State -->
                    <div x-show="!loading && query.length > 0 && results.length === 0 && searched" class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 mb-2">No Results Found</h4>
                        <p class="text-sm text-gray-500 mb-1">We couldn't find any matches for "<span class="font-medium text-gray-700" x-text="query"></span>"</p>
                        <p class="text-xs text-gray-500">Try using different keywords or browse our categories</p>
                    </div>

                    <!-- Results List -->
                    <div x-show="!loading && results.length > 0" class="space-y-2">
                        <template x-for="result in results" :key="result.type + '-' + result.id">
                            <a :href="result.url"
                               class="flex items-center space-x-3 p-3 rounded-xl border border-gray-100 hover:border-gray-200 hover:bg-gray-50 transition-all group cursor-pointer">
                                <!-- Image -->
                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gray-100">
                                    <img :src="result.image" :alt="result.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                </div>

                                <!-- Content -->
                                <div class="flex-grow min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-sm text-gray-900 truncate" x-text="result.name"></h4>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium flex-shrink-0"
                                              :class="{
                                                  'bg-gray-100 text-gray-700': result.type === 'spa',
                                                  'bg-gray-100 text-gray-700': result.type === 'gym',
                                                  'bg-gray-100 text-gray-700': result.type === 'yoga'
                                              }"
                                              x-text="result.type.toUpperCase()">
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 truncate mb-1" x-text="result.address"></p>
                                    <div class="flex items-center gap-3 text-xs">
                                        <div class="flex items-center text-gray-600" x-show="result.rating > 0">
                                            <svg class="w-3.5 h-3.5 text-yellow-400 fill-current mr-1" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                            <span x-text="result.rating"></span>
                                        </div>
                                        <div x-show="result.is_open" class="flex items-center text-green-600 font-medium">
                                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></div>
                                            Open Now
                                        </div>
                                    </div>
                                </div>

                                <!-- Arrow Icon -->
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-gray-600 group-hover:translate-x-0.5 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </template>
                    </div>

                    <!-- Results Footer -->
                    <div x-show="!loading && results.length > 0" class="mt-4 pt-4 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-500">
                            Showing <span class="font-semibold text-gray-900" x-text="results.length"></span> result<span x-show="results.length !== 1">s</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function searchModal() {
            return {
                isOpen: false,
                query: '',
                results: [],
                loading: false,
                searched: false,

                openSearch() {
                    this.isOpen = true;
                    this.query = '';
                    this.results = [];
                    this.searched = false;
                    setTimeout(() => {
                        document.querySelector('input[type="text"]')?.focus();
                    }, 100);
                },

                closeSearch() {
                    this.isOpen = false;
                    this.query = '';
                    this.results = [];
                    this.searched = false;
                },

                async performSearch() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.searched = false;
                        return;
                    }

                    this.loading = true;
                    this.searched = false;

                    try {
                        const response = await fetch(`/api/search?query=${encodeURIComponent(this.query)}`);
                        const data = await response.json();

                        if (data.success) {
                            this.results = data.results;
                        } else {
                            this.results = [];
                        }
                        this.searched = true;
                    } catch (error) {
                        console.error('Search error:', error);
                        this.results = [];
                        this.searched = true;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</nav>
