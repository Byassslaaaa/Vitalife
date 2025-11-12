<x-app-layout>
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .snap-x { scroll-snap-type: x mandatory; }
        .snap-start { scroll-snap-align: start; }
        .voucher-card-simple { background: white; transition: all 0.3s ease; }
        .voucher-card-simple:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); }
        .voucher-header-simple { background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); }
        .float-animation { animation: float 3s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>

    <div class="bg-white min-h-screen">
        {{-- Hero Section --}}
        <div class="pt-32 pb-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[500px]">
                    <div class="space-y-8">
                        <div class="space-y-6">
                            <div class="flex items-center space-x-2">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                                    BEST PLACE TO FIND THE WELLNESS
                                </p>
                            </div>
                            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-gray-900 leading-tight">
                                EXPLORE
                                <span class="block mt-2 bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">WELLNESS</span>
                            </h1>
                            <p class="text-base text-gray-600 max-w-xl leading-relaxed">
                                Discover the best spa centers, yoga studios, and fitness gyms in Indonesia. HeaLife helps you find wellness destinations and book your perfect retreat for a healthier lifestyle.
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-4">
                            <button onclick="document.getElementById('wellness-section').scrollIntoView({behavior: 'smooth'})"
                                class="inline-flex items-center px-8 py-4 bg-gray-900 text-white font-semibold text-base rounded-full hover:bg-gray-800 transition-colors shadow-lg">
                                <span class="mr-3">Explore Now</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="relative flex justify-center lg:justify-end">
                        <div class="relative w-full max-w-md lg:max-w-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Large Main Image (Top) - Yoga Poster -->
                                <div class="col-span-2 relative group overflow-hidden rounded-2xl shadow-xl">
                                    <img src="{{ asset('image/poster-yoga.png') }}" alt="Yoga Wellness" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>

                                <!-- Small Image 1 (Bottom Left) - Placeholder -->
                                <div class="relative group overflow-hidden rounded-2xl shadow-lg">
                                    <img src="{{ asset('image/poster-spa.png') }}" alt="Spa Wellness" class="w-full h-32 object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-teal-500/20"></div>
                                </div>

                                <!-- Small Image 2 (Bottom Right) - Gym Poster -->
                                <div class="relative group overflow-hidden rounded-2xl shadow-lg">
                                    <img src="{{ asset('image/poster-gym.png') }}" alt="Gym Fitness" class="w-full h-32 object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 to-blue-500/20"></div>
                                </div>

                                <!-- Floating Badge -->
                                <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl p-4 float-animation">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Trusted by</p>
                                            <p class="text-lg font-bold text-gray-900">5000+</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Features Section - 3 Categories --}}
        <div id="wellness-section" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-4">
                        Start Your Wellness Journey
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Choose your path to better health and wellbeing</p>
                </div>

                <div class="grid lg:grid-cols-3 gap-6">
                    {{-- Spa Card --}}
                    <a href="{{ route('spa.index') }}" class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-50 to-teal-50 p-8 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-emerald-400/20 to-teal-400/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative h-full flex flex-col">
                            <div class="flex items-center justify-between mb-8">
                                <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                    <img src="{{ asset('image/spa.png') }}" alt="Spa" class="w-10 h-10">
                                </div>

                                {{-- <div class="px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full text-sm font-bold text-emerald-600">
                                    Spa
                                </div> --}}
                            </div>

                            <h3 class="text-3xl font-black text-gray-900 mb-auto">
                                Spa & Relaxation
                            </h3>

                            <div class="flex items-center text-emerald-600 font-bold group-hover:translate-x-2 transition-transform duration-300 mt-8">
                                <span>Explore Spa Centers</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    {{-- Yoga Card --}}
                    <a href="{{ route('yoga.index') }}" class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-teal-50 to-cyan-50 p-8 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-teal-400/20 to-cyan-400/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative h-full flex flex-col">
                            <div class="flex items-center justify-between mb-8">
                                <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                    <img src="{{ asset('image/meditation.png') }}" alt="Yoga" class="w-10 h-10">
                                </div>
                                {{-- <div class="px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full text-sm font-bold text-teal-600">
                                    Yoga
                                </div> --}}
                            </div>

                            <h3 class="text-3xl font-black text-gray-900 mb-auto">
                                Yoga & Meditation
                            </h3>

                            <div class="flex items-center text-teal-600 font-bold group-hover:translate-x-2 transition-transform duration-300 mt-8">
                                <span>Explore Yoga Studios</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    {{-- Gym Card --}}
                    <a href="{{ route('gym.index') }}" class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-cyan-50 to-blue-50 p-8 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-cyan-400/20 to-blue-400/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative h-full flex flex-col">
                            <div class="flex items-center justify-between mb-8">
                                <div class="w-16 h-16 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                    <img src="{{ asset('image/run.png') }}" alt="Gym" class="w-10 h-10">
                                </div>
                                {{-- <div class="px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full text-sm font-bold text-cyan-600">
                                    Gym
                                </div> --}}
                            </div>

                            <h3 class="text-3xl font-black text-gray-900 mb-auto">
                                Gym & Fitness
                            </h3>

                            <div class="flex items-center text-cyan-600 font-bold group-hover:translate-x-2 transition-transform duration-300 mt-8">
                                <span>Explore Fitness Centers</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Trending Section --}}
        @if(isset($trendingItems) && $trendingItems->isNotEmpty())
        <div class="py-16 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">Trending Wellness Centers</h2>
                    <p class="text-lg text-gray-600">Most popular destinations this week</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($trendingItems->take(3) as $item)
                        <a href="{{ $item['detail_url'] ?? route($item['type'] . '.detail', $item['id']) }}"
                           class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-semibold text-gray-900">
                                    {{ ucfirst($item['type']) }}
                                </div>
                                @if(isset($item['is_open']) && $item['is_open'])
                                <div class="absolute top-4 left-4 px-3 py-1 bg-green-500/90 backdrop-blur-sm rounded-full text-xs font-semibold text-white flex items-center">
                                    <div class="w-1.5 h-1.5 bg-white rounded-full mr-1.5 animate-pulse"></div>
                                    Open
                                </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1">{{ $item['name'] }}</h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex items-start">
                                    <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $item['location'] ?? 'Location not available' }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <div class="text-xs text-gray-500">
                                        {{ $item['opening_hours'] ?? 'Contact for hours' }}
                                    </div>
                                    @if(isset($item['formatted_price']))
                                    <span class="text-sm font-semibold text-emerald-600">{{ $item['formatted_price'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Featured Services Section --}}
        @if(isset($trendingItems) && $trendingItems->isNotEmpty())
        <div class="py-16 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">Featured Services</h2>
                    <p class="text-lg text-gray-600">Discover our most popular wellness services</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($trendingItems->take(6) as $item)
                        @if(isset($item['services']) && $item['services']->isNotEmpty())
                            @foreach($item['services']->take(1) as $service)
                            <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                                <div class="relative h-48 bg-gradient-to-br from-{{ $item['type'] == 'spa' ? 'emerald' : ($item['type'] == 'yoga' ? 'teal' : 'cyan') }}-100 to-{{ $item['type'] == 'spa' ? 'teal' : ($item['type'] == 'yoga' ? 'cyan' : 'blue') }}-100 flex items-center justify-center overflow-hidden">
                                    @if(isset($service['image']) && $service['image'] && $service['image'] != 'image/spa-service.png')
                                        <img src="{{ $service['image'] }}" alt="{{ $service['name'] }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-24 h-24 bg-gradient-to-br from-{{ $item['type'] == 'spa' ? 'emerald' : ($item['type'] == 'yoga' ? 'teal' : 'cyan') }}-500 to-{{ $item['type'] == 'spa' ? 'teal' : ($item['type'] == 'yoga' ? 'cyan' : 'blue') }}-500 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($item['type'] == 'spa')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @elseif($item['type'] == 'yoga')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                @endif
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-semibold text-gray-900 capitalize">
                                        {{ $item['type'] }}
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ $service['name'] ?? 'Service' }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $service['description'] ?? 'Professional wellness service' }}</p>
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                        <span class="text-xs text-gray-500">{{ $item['name'] }}</span>
                                        <a href="{{ $item['detail_url'] }}"
                                           class="inline-flex items-center text-sm font-semibold text-{{ $item['type'] == 'spa' ? 'emerald' : ($item['type'] == 'yoga' ? 'teal' : 'cyan') }}-600 hover:text-{{ $item['type'] == 'spa' ? 'emerald' : ($item['type'] == 'yoga' ? 'teal' : 'cyan') }}-700">
                                            View Details
                                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Vouchers Section --}}
        @if(isset($vouchers) && $vouchers->isNotEmpty())
        <div class="py-16 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">Special Vouchers</h2>
                        <p class="text-lg text-gray-600">Save more on your wellness journey</p>
                    </div>
                    <a href="{{ route('voucher') }}" class="hidden md:inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-full hover:bg-gray-800 transition-colors">
                        View All
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="flex gap-6 overflow-x-auto snap-x scrollbar-hide pb-4">
                    @foreach ($vouchers as $voucher)
                        <div class="flex-shrink-0 w-80 snap-start">
                            <div class="voucher-card-simple rounded-2xl overflow-hidden shadow-lg">
                                <div class="voucher-header-simple p-6 text-white">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm font-medium opacity-90">VOUCHER CODE</span>
                                        <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold">
                                            {{ $voucher->discount_type === 'percentage' ? $voucher->discount_value . '%' : 'Rp ' . number_format($voucher->discount_value, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <p class="text-3xl font-black mb-2 tracking-wider">{{ $voucher->code }}</p>
                                    <p class="text-sm opacity-90">{{ $voucher->description }}</p>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>Min. Purchase: Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Valid until: {{ \Carbon\Carbon::parse($voucher->valid_until)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <button onclick="navigator.clipboard.writeText('{{ $voucher->code }}')"
                                        class="w-full py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                                        Copy Code
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- How It Works Section --}}
        <div class="py-16">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">How It Works</h2>
                    <p class="text-lg text-gray-600">Book your wellness experience in 3 easy steps</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    @php
                        $steps = [
                            ['icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'title' => 'Browse & Search', 'desc' => 'Explore our curated collection of spas, yoga studios, and gyms in your area', 'color' => 'emerald'],
                            ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Choose & Book', 'desc' => 'Select your preferred service, date, and time slot that fits your schedule', 'color' => 'teal'],
                            ['icon' => 'M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5', 'title' => 'Relax & Enjoy', 'desc' => 'Show up and enjoy your wellness experience with confirmed reservation', 'color' => 'cyan']
                        ];
                    @endphp
                    @foreach($steps as $index => $step)
                    <div class="text-center relative">
                        <div class="relative inline-block mb-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-{{ $step['color'] }}-500 to-teal-500 rounded-full flex items-center justify-center shadow-xl">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"></path>
                                </svg>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-{{ $step['color'] }}-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">{{ $index + 1 }}</div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $step['title'] }}</h3>
                        <p class="text-gray-600">{{ $step['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Why Choose Us Section --}}
        <div class="py-16 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">Why Choose HeaLife</h2>
                    <p class="text-lg text-gray-600">Your trusted partner for wellness bookings</p>
                </div>
                <div class="grid md:grid-cols-4 gap-6">
                    @php
                        $features = [
                            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Verified Venues', 'desc' => 'All wellness centers are verified and quality checked', 'color' => 'emerald'],
                            ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Instant Booking', 'desc' => 'Book instantly with real-time availability', 'color' => 'teal'],
                            ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Best Prices', 'desc' => 'Competitive pricing with exclusive vouchers', 'color' => 'cyan'],
                            ['icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'title' => '24/7 Support', 'desc' => 'Customer support always ready to help you', 'color' => 'blue']
                        ];
                    @endphp
                    @foreach($features as $feature)
                    <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 bg-{{ $feature['color'] }}-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-{{ $feature['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $feature['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- CTA Section --}}
        <div class="py-20">
            <div class="max-w-4xl mx-auto px-6 sm:px-8 lg:px-12 text-center">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                    Ready to Start Your Wellness Journey?
                </h2>
                <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                    Join thousands of happy users who have discovered their perfect wellness destination through HeaLife.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('spa.index') }}" class="inline-flex items-center px-8 py-4 bg-gray-900 text-white font-semibold rounded-full hover:bg-gray-800 transition-colors shadow-lg">
                        Browse Wellness Centers
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('chat') }}" class="inline-flex items-center px-8 py-4 border-2 border-gray-900 text-gray-900 font-semibold rounded-full hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Chat with Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
