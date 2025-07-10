<x-app-layout>
    <style>
        .unified-gradient {
            background: linear-gradient(to bottom, #FFFFFF 0%, #BED9FE 100%);
        }
    </style>

    {{-- Unified Dashboard Section --}}
    <div class="unified-gradient min-h-screen">
        {{-- Hero Section --}}
        <div class="pt-40 pb-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="space-y-8">
                        <div class="space-y-6">
                            <p class="text-base lg:text-lg font-bold text-gray-700 uppercase tracking-wider">
                                SKIP THE TRAVEL! TAKE ONLINE
                            </p>
                            <h1 class="text-5xl lg:text-7xl xl:text-8xl font-black text-gray-900 leading-tight">
                                WELCOME<br>
                                <span class="text-blue-400 font-bold">VITALIFE</span>
                            </h1>
                            <p class="text-xl lg:text-2xl text-gray-700 max-w-lg leading-relaxed">
                                We are the solution for travelling in a healthy condition and we provide health specialists
                            </p>
                        </div>
                        
                        <button class="inline-flex items-center px-10 py-5 bg-gray-900 text-white font-bold text-lg rounded-full hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 shadow-xl">
                            <span class="mr-3">Explore now</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </button>
                    </div>
                   
                    <!-- Right Content - Image with Frame Effect -->
                    <div class="relative flex justify-center lg:justify-end">
                        <div class="relative w-96 lg:w-[450px]">
                            <!-- Background Frame -->
                            <div class="absolute top-6 left-6 w-full h-full border-4 border-gray-900 z-0"></div>
                            
                            <!-- Main Image Container -->
                            <div class="relative z-10 shadow-2xl">
                                <img 
                                    src="{{ asset('image/bgdash.png') }}" 
                                    alt="Yoga Woman in Mountains"
                                    class="w-96 h-98 lg:h-[450px] object-cover object-center"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wellness Support Section --}}
        <div class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Wellness Support</h2>
                    <p class="text-lg text-gray-600">Find The Wellness</p>
                </div>
                <div class="grid md:grid-cols-3 gap-12 max-w-5xl mx-auto">
                    @auth
                        <a href="{{ route('spa.index') }}" class="group">
                            <div class="bg-blue-50 rounded-full w-32 h-32 mx-auto mb-6 flex items-center justify-center group-hover:bg-blue-100 group-hover:scale-110 transition-all duration-300 shadow-lg">
                                <img src="{{ asset('image/spa.png') }}" alt="SPA" class="h-16 w-16" />
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">SPA</h3>
                        </a>
                        
                        <a href="{{ route('yoga.index') }}" class="group">
                            <div class="bg-blue-50 rounded-full w-32 h-32 mx-auto mb-6 flex items-center justify-center group-hover:bg-blue-100 group-hover:scale-110 transition-all duration-300 shadow-lg">
                                <img src="{{ asset('image/meditation.png') }}" alt="YOGA" class="h-16 w-16" />
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">YOGA</h3>
                        </a>
                        
                        <a href="{{ route('gym.index') }}" class="group">
                            <div class="bg-blue-50 rounded-full w-32 h-32 mx-auto mb-6 flex items-center justify-center group-hover:bg-blue-100 group-hover:scale-110 transition-all duration-300 shadow-lg">
                                <img src="{{ asset('image/run.png') }}" alt="GYM" class="h-16 w-16" />
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">GYM</h3>
                        </a>
                    @else
                        <div class="group cursor-pointer" onclick="showLoginAlert()">
                            <div class="bg-blue-50 rounded-full w-32 h-32 mx-auto mb-6 flex items-center justify-center group-hover:bg-blue-100 group-hover:scale-110 transition-all duration-300 shadow-lg relative">
                                <img src="{{ asset('image/spa.png') }}" alt="SPA" class="h-16 w-16 opacity-50" />
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-500">SPA</h3>
                            <p class="text-sm text-gray-400">Login required</p>
                        </div>
                        
                        <div class="group cursor-pointer" onclick="showLoginAlert()">
                            <div class="bg-blue-50 rounded-full w-32 h-32 mx-auto mb-6 flex items-center justify-center group-hover:bg-blue-100 group-hover:scale-110 transition-all duration-300 shadow-lg relative">
                                <img src="{{ asset('image/meditation.png') }}" alt="YOGA" class="h-16 w-16 opacity-50" />
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-500">YOGA</h3>
                            <p class="text-sm text-gray-400">Login required</p>
                        </div>
                        
                        <div class="group cursor-pointer" onclick="showLoginAlert()">
                            <div class="bg-blue-50 rounded-full w-32 h-32 mx-auto mb-6 flex items-center justify-center group-hover:bg-blue-100 group-hover:scale-110 transition-all duration-300 shadow-lg relative">
                                <img src="{{ asset('image/run.png') }}" alt="GYM" class="h-16 w-16 opacity-50" />
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-500">GYM</h3>
                            <p class="text-sm text-gray-400">Login required</p>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Trending Now Section --}}
        <div class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Trending Now</h2>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    @forelse($trendingItems->take(3) as $item)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-48 object-cover" />
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $item['name'] }}</h3>
                            <div class="flex items-center text-gray-600 mb-4">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm">{{ Str::limit($item['location'], 30) }}</span>
                            </div>
                            
                            @if($item['type'] === 'spa')
                            <div class="mb-6 flex-grow">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-semibold text-gray-900">Services</h4>
                                    @auth
                                        <a href="{{ $item['detail_url'] }}" class="text-sm text-blue-600 cursor-pointer hover:text-blue-800">View all</a>
                                    @else
                                        <span class="text-sm text-gray-400">Login to view</span>
                                    @endauth
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach($item['services']->take(3) as $service)
                                    <div class="text-center">
                                        <img src="{{ asset($service['image']) }}" alt="{{ $service['name'] }}" class="w-12 h-12 mx-auto mb-2" />
                                        <p class="text-xs font-semibold text-gray-700">{{ Str::limit($service['name'], 15) }}</p>
                                        <p class="text-xs text-gray-500">{{ Str::limit($service['description'], 30) }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @elseif($item['type'] === 'yoga')
                            <div class="mb-6 flex-grow">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">Yoga Session</h4>
                                    <p class="text-lg font-bold text-blue-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600">Professional yoga instruction</p>
                                    @if($item['phone'])
                                    <p class="text-xs text-gray-500 mt-1">Phone: {{ $item['phone'] }}</p>
                                    @endif
                                </div>
                            </div>
                            @elseif($item['type'] === 'gym')
                            <div class="mb-6 flex-grow">
                                <div class="bg-green-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">Gym Facilities</h4>
                                    <p class="text-sm text-gray-600">Modern equipment and professional trainers</p>
                                    <div class="flex items-center mt-2">
                                        @if($item['is_open'])
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                            <span class="text-xs text-green-600 font-medium">Currently Open</span>
                                        @else
                                            <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                            <span class="text-xs text-red-600 font-medium">Currently Closed</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Button positioned at bottom -->
                            <div class="mt-auto">
                                @auth
                                    <a href="{{ $item['detail_url'] }}" class="w-full bg-gray-900 text-white py-2 px-4 rounded-lg font-semibold hover:bg-gray-800 transition-colors text-center block">
                                        View Details
                                    </a>
                                @else
                                    <button onclick="showLoginAlert()" class="w-full bg-gray-400 text-white py-2 px-4 rounded-lg font-semibold cursor-pointer text-center">
                                        Login to View
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500 text-lg">No trending items available at the moment.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Voucher Section - Only show to authenticated users --}}
        @auth
        <div id="voucher" class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl lg:text-4xl font-bold text-center mb-16 text-gray-900">Voucher</h2>
                @if($vouchers->count() > 0)
                <div class="relative" x-data="imageSlider()">
                    <div id="imageSlider" class="overflow-hidden">
                        <div class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${currentIndex * (100/3)}%)` }">
                            @foreach ($vouchers as $voucher)
                            <div class="w-1/3 flex-shrink-0 px-4">
                                <div class="bg-white rounded-xl p-6 text-gray-900 cursor-pointer transform hover:scale-105 transition-transform duration-300 shadow-lg" onclick="openPopup('{{ asset($voucher->image) }}', '{{ $voucher->description }}', '{{ $voucher->code }}')">
                                    <img class="w-full h-32 object-cover rounded-lg mb-4" src="{{ asset($voucher->image) }}" alt="{{ $voucher->description }}" />
                                    <h3 class="font-bold text-lg mb-2">Discount {{ $voucher->discount_percentage ?? 50 }}%</h3>
                                    <p class="text-sm text-gray-600 mb-4">{{ $voucher->description }}</p>
                                    <button class="w-full bg-gray-900 text-white py-2 px-4 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
                                        Copy Code
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center">
                    <p class="text-gray-600 text-lg">No vouchers available at the moment.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Voucher Popup -->
        <div id="popup" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto relative">
                <button onclick="closePopup()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <img id="popupImage" src="/placeholder.svg" alt="" class="w-full rounded-xl mb-4">
                <p id="popupDescription" class="text-gray-700 mb-4"></p>
                <div class="border border-gray-300 rounded-md p-3 mb-4 inline-block">
                    <p class="font-bold text-lg" id="voucherCode"></p>
                </div>
            </div>
        </div>
        @endauth

        {{-- FAQ Section --}}
        <div class="py-20 pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Left - Image -->
                    <div class="relative max-w-md mx-auto lg:mx-0">
                        <img src="{{ asset('image/faq-image.png') }}" alt="Doctor with patient" class="w-full rounded-lg shadow-lg" />
                        <div class="absolute bottom-4 left-4 flex items-center bg-white rounded-full px-4 py-2 shadow-md">
                            <span class="text-xl mr-2">😊</span>
                            <p class="font-semibold text-xs">84k+ <span class="font-normal text-gray-600">Happy Patients</span></p>
                        </div>
                    </div>

                    <!-- Right - FAQ Content -->
                    <div class="space-y-6">
                        <div class="text-center lg:text-left mb-12">
                            <p class="text-blue-500 text-sm font-medium mb-4">Get Your Answer</p>
                            <h2 class="text-3xl font-bold text-gray-900">Frequently Asked Questions</h2>
                        </div>
                        
                        <div class="space-y-4" x-data="{ openItem: null }">
                            <!-- FAQ Item 1 -->
                            <div class="border-b pb-4">
                                <button @click="openItem = openItem === 1 ? null : 1"
                                    class="flex justify-between items-center w-full text-left">
                                    <span class="font-medium text-gray-900 flex-grow pr-3">
                                        Why do you prefer Vitalife compared to other wellness tourism platforms?
                                    </span>
                                    <svg class="w-6 h-6 flex-shrink-0 text-blue-500 transform transition-transform duration-200"
                                        :class="{ 'rotate-45': openItem === 1 }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <div x-show="openItem === 1" x-transition
                                    class="mt-3 text-gray-600 text-sm">
                                    I prefer Vitalife because of its transparent pricing, helpful reviews, progress tracking,
                                    and ease of booking consultations and activities.
                                </div>
                            </div>

                            <!-- FAQ Item 2 -->
                            <div class="border-b pb-4">
                                <button @click="openItem = openItem === 2 ? null : 2"
                                    class="flex justify-between items-center w-full text-left">
                                    <span class="font-medium text-gray-900 flex-grow pr-3">
                                        What was your experience with the registration process and initial use of the Vitalife website?
                                    </span>
                                    <svg class="w-6 h-6 flex-shrink-0 text-blue-500 transform transition-transform duration-200"
                                        :class="{ 'rotate-45': openItem === 2 }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <div x-show="openItem === 2" x-transition
                                    class="mt-3 text-gray-600 text-sm">
                                    The registration process was intuitive and user-friendly, making it easy to get started.
                                </div>
                            </div>

                            <!-- FAQ Item 3 -->
                            <div class="border-b pb-4">
                                <button @click="openItem = openItem === 3 ? null : 3"
                                    class="flex justify-between items-center w-full text-left">
                                    <span class="font-medium text-gray-900 flex-grow pr-3">
                                        What features appealed to you the most when you first saw the Vitalife website?
                                    </span>
                                    <svg class="w-6 h-6 flex-shrink-0 text-blue-500 transform transition-transform duration-200"
                                        :class="{ 'rotate-45': openItem === 3 }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <div x-show="openItem === 3" x-transition
                                    class="mt-3 text-gray-600 text-sm">
                                    The online doctor consultation gave me confidence and peace of mind.
                                </div>
                            </div>

                            <!-- FAQ Item 4 -->
                            <div class="border-b pb-4">
                                <button @click="openItem = openItem === 4 ? null : 4"
                                    class="flex justify-between items-center w-full text-left">
                                    <span class="font-medium text-gray-900 flex-grow pr-3">
                                        How can Vitalife help you plan and enjoy your wellness journey?
                                    </span>
                                    <svg class="w-6 h-6 flex-shrink-0 text-blue-500 transform transition-transform duration-200"
                                        :class="{ 'rotate-45': openItem === 4 }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <div x-show="openItem === 4" x-transition
                                    class="mt-3 text-gray-600 text-sm">
                                    Vitalife recommends facilities, packages, and events tailored to your wellness goals.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chatbot Widget - HANYA untuk user yang sudah login --}}
    @auth
    <div id="chatbot-widget" class="fixed bottom-4 right-4 z-50">
        <!-- Chat Button -->
        <button id="chat-button" class="bg-blue-500 hover:bg-blue-600 text-white rounded-full p-4 shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </button>

        <!-- Chat Window -->
        <div id="chat-window" class="hidden bg-white rounded-lg shadow-xl w-80 sm:w-96 h-96 flex flex-col overflow-hidden">
            <!-- Chat Header -->
            <div class="bg-blue-500 text-white p-4 flex justify-between items-center">
                <div class="flex-1">
                    <h3 class="font-bold">Vitalife Support</h3>
                    <div id="session-timer" class="text-xs text-blue-200 hidden">
                        Session expires in: <span id="timer-display">15:00</span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button id="minimize-chat" class="hover:bg-blue-600 rounded p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4"></div>

            <!-- Category Selection (initially visible) -->
            <div id="category-selection" class="p-4 bg-gray-50">
                <p class="text-sm text-gray-600 mb-2">What would you like to discuss?</p>
                <div class="grid grid-cols-2 gap-2">
                    <button class="category-btn bg-white border border-gray-300 rounded p-2 text-sm hover:bg-gray-100 transition-colors" data-category="Facilities & Accommodations">Facilities & Accommodations</button>
                    <button class="category-btn bg-white border border-gray-300 rounded p-2 text-sm hover:bg-gray-100 transition-colors" data-category="Health & Security">Health & Security</button>
                    <button class="category-btn bg-white border border-gray-300 rounded p-2 text-sm hover:bg-gray-100 transition-colors" data-category="Cancellations & Refunds">Cancellations & Refunds</button>
                    <button class="category-btn bg-white border border-gray-300 rounded p-2 text-sm hover:bg-gray-100 transition-colors" data-category="Payments & Promotions">Payments & Promotions</button>
                </div>
            </div>

            <!-- Chat Input (initially hidden) -->
            <div id="chat-input-container" class="p-4 border-t hidden">
                <form id="chat-form" class="flex space-x-2">
                    <input type="text" id="chat-input" class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Type your message...">
                    <button type="submit" class="bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Session Timeout Warning -->
            <div id="timeout-warning" class="hidden bg-yellow-100 border-t border-yellow-300 p-3">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm text-yellow-800">Session will expire in <span id="warning-timer">2:00</span></span>
                    <button id="extend-session" class="ml-auto text-xs bg-yellow-600 text-white px-2 py-1 rounded hover:bg-yellow-700">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Chat JavaScript - HANYA untuk user yang sudah login --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const chatButton = document.getElementById('chat-button');
        const chatWindow = document.getElementById('chat-window');
        const minimizeChat = document.getElementById('minimize-chat');
        const chatMessages = document.getElementById('chat-messages');
        const categorySelection = document.getElementById('category-selection');
        const categoryButtons = document.querySelectorAll('.category-btn');
        const chatInputContainer = document.getElementById('chat-input-container');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const sessionTimer = document.getElementById('session-timer');
        const timerDisplay = document.getElementById('timer-display');
        const timeoutWarning = document.getElementById('timeout-warning');
        const warningTimer = document.getElementById('warning-timer');
        const extendSessionBtn = document.getElementById('extend-session');
        
        // State
        let currentConversation = null;
        let selectedCategory = null;
        let adminActive = false;
        let sessionTimeout = null;
        let warningTimeout = null;
        let timerInterval = null;
        let sessionStartTime = null;
        let sessionDuration = 15 * 60 * 1000; // 15 minutes in milliseconds
        let warningTime = 2 * 60 * 1000; // Show warning 2 minutes before timeout
        
        // Toggle chat window
        chatButton.addEventListener('click', function() {
            chatWindow.classList.toggle('hidden');
            chatButton.classList.toggle('hidden');
            
            if (!chatWindow.classList.contains('hidden')) {
                if (currentConversation === null) {
                    // First time opening, check for existing conversation
                    fetchConversation();
                    // Check admin status
                    checkAdminStatus();
                }
                // Start or resume session timer
                startSessionTimer();
            } else {
                // Pause session timer when minimized
                pauseSessionTimer();
            }
        });
        
        // Minimize chat
        minimizeChat.addEventListener('click', function() {
            chatWindow.classList.add('hidden');
            chatButton.classList.remove('hidden');
            // Pause session timer when minimized
            pauseSessionTimer();
        });
        
        // Category selection
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                selectedCategory = this.dataset.category;
                categorySelection.classList.add('hidden');
                chatInputContainer.classList.remove('hidden');
                sessionTimer.classList.remove('hidden');
                
                // Add system message
                addSystemMessage(`You've selected: ${selectedCategory}. How can I help you today?`);
                
                // Update conversation with category if needed
                if (currentConversation) {
                    updateConversationCategory(currentConversation.id, selectedCategory);
                }
                
                // Reset session timer when category is selected
                resetSessionTimer();
            });
        });
        
        // Send message
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;
            
            // Add user message to UI
            addUserMessage(message);
            chatInput.value = '';
            
            // Send message to server
            sendMessage(message);
            
            // Reset session timer on user activity
            resetSessionTimer();
        });
        
        // Extend session
        extendSessionBtn.addEventListener('click', function() {
            resetSessionTimer();
            hideTimeoutWarning();
        });
        
        // Session Timer Functions
        function startSessionTimer() {
            if (!sessionStartTime) {
                sessionStartTime = Date.now();
            }
            
            // Clear existing timers
            clearTimeout(sessionTimeout);
            clearTimeout(warningTimeout);
            clearInterval(timerInterval);
            
            // Set warning timeout (13 minutes)
            warningTimeout = setTimeout(showTimeoutWarning, sessionDuration - warningTime);
            
            // Set session timeout (15 minutes)
            sessionTimeout = setTimeout(handleSessionTimeout, sessionDuration);
            
            // Update timer display every second
            timerInterval = setInterval(updateTimerDisplay, 1000);
        }
        
        function pauseSessionTimer() {
            clearInterval(timerInterval);
        }
        
        function resetSessionTimer() {
            sessionStartTime = Date.now();
            hideTimeoutWarning();
            startSessionTimer();
        }
        
        function updateTimerDisplay() {
            if (!sessionStartTime) return;
            
            const elapsed = Date.now() - sessionStartTime;
            const remaining = Math.max(0, sessionDuration - elapsed);
            
            const minutes = Math.floor(remaining / 60000);
            const seconds = Math.floor((remaining % 60000) / 1000);
            
            timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            // Update warning timer if warning is shown
            if (!timeoutWarning.classList.contains('hidden')) {
                const warningRemaining = Math.max(0, warningTime - (elapsed - (sessionDuration - warningTime)));
                const warningMinutes = Math.floor(warningRemaining / 60000);
                const warningSeconds = Math.floor((warningRemaining % 60000) / 1000);
                warningTimer.textContent = `${warningMinutes}:${warningSeconds.toString().padStart(2, '0')}`;
            }
            
            if (remaining <= 0) {
                handleSessionTimeout();
            }
        }
        
        function showTimeoutWarning() {
            timeoutWarning.classList.remove('hidden');
            addSystemMessage('⚠️ Your session will expire in 2 minutes due to inactivity. Click "Continue" to extend your session.');
        }
        
        function hideTimeoutWarning() {
            timeoutWarning.classList.add('hidden');
        }
        
        function handleSessionTimeout() {
            // Clear all timers
            clearTimeout(sessionTimeout);
            clearTimeout(warningTimeout);
            clearInterval(timerInterval);
            
            // Reset chat to initial state
            resetChatToInitialState();
            
            // Show timeout message
            addSystemMessage('🕐 Your session has expired due to inactivity. Please select a category to start a new session.');
        }
        
        function resetChatToInitialState() {
            // Reset state
            selectedCategory = null;
            sessionStartTime = null;
            
            // Clear messages
            chatMessages.innerHTML = '';
            
            // Show category selection
            categorySelection.classList.remove('hidden');
            chatInputContainer.classList.add('hidden');
            sessionTimer.classList.add('hidden');
            
            // Hide warning
            hideTimeoutWarning();
            
            // Clear input
            chatInput.value = '';
            
            // Add welcome message
            addSystemMessage('Welcome back! Please select a category to continue our conversation.');
        }
        
        // Fetch or create conversation
        function fetchConversation() {
            fetch('/chat/conversation', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                currentConversation = data.conversation;
                
                // If conversation has messages, display them
                if (data.messages && data.messages.length > 0) {
                    chatMessages.innerHTML = '';
                    data.messages.forEach(message => {
                        if (message.sender_type === 'user') {
                            addUserMessage(message.message, false);
                        } else if (message.sender_type === 'ai') {
                            addBotMessage(message.message, false);
                        } else if (message.sender_type === 'admin') {
                            addAdminMessage(message.message, false);
                        }
                    });
                    
                    // If conversation has a category, skip category selection
                    if (currentConversation.category) {
                        selectedCategory = currentConversation.category;
                        categorySelection.classList.add('hidden');
                        chatInputContainer.classList.remove('hidden');
                        sessionTimer.classList.remove('hidden');
                        startSessionTimer();
                    }
                }
                
                // Scroll to bottom
                scrollToBottom();
            })
            .catch(error => {
                console.error('Error fetching conversation:', error);
                addSystemMessage('There was an error connecting to the chat service. Please try again later.');
            });
        }
        
        // Check admin status
        function checkAdminStatus() {
            fetch('/chat/check-admin-status', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                adminActive = data.admin_active;
                
                if (adminActive) {
                    addSystemMessage('An admin is currently online and will respond to your messages.');
                }
            })
            .catch(error => {
                console.error('Error checking admin status:', error);
            });
            
            // Check admin status every 30 seconds
            setTimeout(checkAdminStatus, 30000);
        }
        
        // Update conversation category
        function updateConversationCategory(conversationId, category) {
            // This is handled when sending the first message
        }
        
        // Send message to server
        function sendMessage(message) {
            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    conversation_id: currentConversation.id,
                    message: message,
                    category: selectedCategory
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update admin status
                if (data.admin_active !== undefined) {
                    adminActive = data.admin_active;
                }
                
                // If AI responded, add the response
                if (data.ai_response) {
                    addBotMessage(data.ai_response.message);
                } else if (adminActive) {
                    // Add a waiting message if admin is active
                    addSystemMessage('An admin will respond to your message soon.');
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                addSystemMessage('There was an error sending your message. Please try again.');
            });
        }
        
        // Add user message to UI
        function addUserMessage(message, scroll = true) {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex justify-end';
            messageElement.innerHTML = `
                <div class="bg-blue-500 text-white rounded-lg py-2 px-4 max-w-[80%]">
                    <p>${escapeHtml(message)}</p>
                </div>
            `;
            chatMessages.appendChild(messageElement);
            if (scroll) scrollToBottom();
        }
        
        // Add bot message to UI
        function addBotMessage(message, scroll = true) {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex justify-start';
            messageElement.innerHTML = `
                <div class="bg-gray-200 rounded-lg py-2 px-4 max-w-[80%]">
                    <p class="text-gray-800">${escapeHtml(message)}</p>
                </div>
            `;
            chatMessages.appendChild(messageElement);
            if (scroll) scrollToBottom();
        }
        
        // Add admin message to UI
        function addAdminMessage(message, scroll = true) {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex justify-start';
            messageElement.innerHTML = `
                <div class="bg-green-100 rounded-lg py-2 px-4 max-w-[80%]">
                    <p class="text-gray-800"><span class="font-bold text-green-600">Admin:</span> ${escapeHtml(message)}</p>
                </div>
            `;
            chatMessages.appendChild(messageElement);
            if (scroll) scrollToBottom();
        }
        
        // Add system message to UI
        function addSystemMessage(message, scroll = true) {
            const messageElement = document.createElement('div');
            messageElement.className = 'flex justify-center';
            messageElement.innerHTML = `
                <div class="bg-gray-100 text-gray-600 rounded-lg py-2 px-4 text-sm max-w-[90%] text-center">
                    <p>${escapeHtml(message)}</p>
                </div>
            `;
            chatMessages.appendChild(messageElement);
            if (scroll) scrollToBottom();
        }
        
        // Scroll chat to bottom
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Escape HTML to prevent XSS
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            clearTimeout(sessionTimeout);
            clearTimeout(warningTimeout);
            clearInterval(timerInterval);
        });
    });
    </script>
    @endauth

    {{-- Chat Button untuk Guest Users - Menampilkan pesan login --}}
    @guest
    <div id="guest-chat-widget" class="fixed bottom-4 right-4 z-50">
        <!-- Chat Button untuk Guest -->
        <button onclick="showChatLoginAlert()" class="bg-gray-400 hover:bg-gray-500 text-white rounded-full p-4 shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-105 relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <!-- Lock icon overlay -->
            <div class="absolute -top-1 -right-1 bg-red-500 rounded-full p-1">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
        </button>
    </div>
    @endguest

    <script>
    // Function to show login alert for guest users
    function showLoginAlert() {
        if (confirm('You need to login to access this feature. Would you like to login now?')) {
            window.location.href = '{{ route("login") }}';
        }
    }

    // Function to show chat login alert for guest users
    function showChatLoginAlert() {
        if (confirm('Chat support is only available for registered users. Would you like to login or create an account?')) {
            window.location.href = '{{ route("login") }}';
        }
    }

    // Voucher popup functions - Only for authenticated users
    @auth
    function openPopup(imageSrc, description, voucherCode) {
        const popup = document.getElementById('popup');
        const popupImage = document.getElementById('popupImage');
        const popupDescription = document.getElementById('popupDescription');
        const voucherCodeElement = document.getElementById('voucherCode');

        popupImage.src = imageSrc;
        popupDescription.textContent = description;
        voucherCodeElement.textContent = voucherCode;
        popup.classList.remove('hidden');
    }

    function closePopup() {
        const popup = document.getElementById('popup');
        popup.classList.add('hidden');
    }

    function imageSlider() {
        return {
            currentIndex: 0,
            totalSlides: {{ $vouchers->count() }},
            nextSlide() {
                this.currentIndex = (this.currentIndex + 1) % Math.max(1, this.totalSlides - 2);
            },
            startSlider() {
                if (this.totalSlides > 3) {
                    setInterval(() => this.nextSlide(), 3000);
                }
            },
            init() {
                this.startSlider();
            }
        }
    }

    // Handle voucher section scroll
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const scroll = urlParams.get('scroll');
        if (scroll === 'voucher') {
            const voucherSection = document.getElementById('voucher');
            if (voucherSection) {
                voucherSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    });
    @endauth
    </script>

    @include('layouts.footer')
</x-app-layout>