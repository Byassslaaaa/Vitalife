<x-app-layout>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .float-animation {
            animation: float 4s ease-in-out infinite;
        }

        .team-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .team-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(16, 185, 129, 0.2);
        }

        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.15);
        }

        .stat-number {
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-number {
            transform: scale(1.1);
            color: #10b981;
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
    </style>

    {{-- Modern About Us Page --}}
    <div class="bg-gradient-to-b from-gray-50 to-white min-h-screen">

        <!-- Hero Section with Modern Design -->
        <section class="pt-32 pb-20 relative overflow-hidden bg-gradient-to-r from-emerald-600 to-teal-600">
            <div class="absolute inset-0 bg-black/10"></div>

            <!-- Animated Background Elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/5 rounded-full blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10">
                <div class="text-center">
                    <!-- Logo with Animation -->
                    <div class="inline-flex items-center justify-center w-24 h-24 mb-8 float-animation">
                        <div class="w-full h-full bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <img src="{{ asset('image/Logo-Healife.png') }}" alt="Vitalife Logo" class="w-16 h-16 object-contain p-2 bg-white rounded-xl">
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white mb-6">
                        About Healife
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xl lg:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed mb-8">
                        Your trusted companion for wellness tourism and healthy lifestyle journeys
                    </p>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto mt-12">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl font-black text-white mb-2">100+</div>
                            <div class="text-white/80 text-sm font-medium">Wellness Centers</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl font-black text-white mb-2">50+</div>
                            <div class="text-white/80 text-sm font-medium">Partner Venues</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl font-black text-white mb-2">1K+</div>
                            <div class="text-white/80 text-sm font-medium">Happy Customers</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl font-black text-white mb-2">24/7</div>
                            <div class="text-white/80 text-sm font-medium">AI Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission & Vision Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Mission -->
                    <div class="group">
                        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-3xl p-10 shadow-xl hover:shadow-2xl transition-all border border-emerald-100">
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-3xl font-black text-gray-900">Our Mission</h3>
                                    <div class="w-20 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full mt-2"></div>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed text-lg">
                                Vitalife is dedicated to <span class="font-semibold text-emerald-600">enhancing wellness tourism experiences</span> by providing comprehensive access to premium spa, yoga, and fitness facilities. We connect travelers with wellness services while ensuring their health and safety through professional consultations and transparent booking systems.
                            </p>
                        </div>
                    </div>

                    <!-- Vision -->
                    <div class="group">
                        <div class="bg-gradient-to-br from-white to-teal-50 rounded-3xl p-10 shadow-xl hover:shadow-2xl transition-all border border-teal-100">
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-3xl font-black text-gray-900">Our Vision</h3>
                                    <div class="w-20 h-1 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-full mt-2"></div>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed text-lg">
                                To become the <span class="font-semibold text-teal-600">leading wellness tourism platform</span> that empowers individuals to maintain a healthy lifestyle while exploring new destinations, creating a harmonious balance between travel, fitness, and well-being for a healthier future.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Core Values Section -->
        <section class="py-20 bg-gradient-to-br from-gray-50 to-emerald-50">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center mb-16">
                    <div class="inline-block px-4 py-2 bg-emerald-100 rounded-full mb-4">
                        <span class="text-emerald-700 font-bold text-sm">OUR VALUES</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">What Drives Us</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">The principles that guide everything we do</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Value 1 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all border border-gray-100">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Health First</h3>
                        <p class="text-gray-600 text-center leading-relaxed">Your wellness is our priority. We ensure every service meets the highest health and safety standards.</p>
                    </div>

                    <!-- Value 2 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all border border-gray-100">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Community</h3>
                        <p class="text-gray-600 text-center leading-relaxed">Building a supportive community of wellness enthusiasts and trusted partners.</p>
                    </div>

                    <!-- Value 3 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all border border-gray-100">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Innovation</h3>
                        <p class="text-gray-600 text-center leading-relaxed">Leveraging technology to provide seamless booking experiences and AI-powered support.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center mb-16">
                    <div class="inline-block px-4 py-2 bg-emerald-100 rounded-full mb-4">
                        <span class="text-emerald-700 font-bold text-sm">FEATURES</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">What We Offer</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Comprehensive wellness services at your fingertips</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1: Spa & Wellness -->
                    <div class="feature-card bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-lg hover:border-emerald-300 transition-all">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Spa & Wellness</h3>
                        <p class="text-gray-600 leading-relaxed">Discover premium spa experiences and relaxation centers with exclusive treatments and therapies.</p>
                        <a href="{{ route('spa.index') }}" class="inline-flex items-center text-emerald-600 font-semibold mt-4 hover:text-emerald-700">
                            Explore Spas
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Feature 2: Yoga & Meditation -->
                    <div class="feature-card bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-lg hover:border-emerald-300 transition-all">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Yoga & Meditation</h3>
                        <p class="text-gray-600 leading-relaxed">Find yoga studios and meditation centers to maintain your inner peace and mindfulness practice.</p>
                        <a href="{{ route('yoga.index') }}" class="inline-flex items-center text-emerald-600 font-semibold mt-4 hover:text-emerald-700">
                            Explore Yoga
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Feature 3: Gym & Fitness -->
                    <div class="feature-card bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-lg hover:border-emerald-300 transition-all">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Gym & Fitness</h3>
                        <p class="text-gray-600 leading-relaxed">Access state-of-the-art gym facilities with modern equipment to keep your fitness routine on track.</p>
                        <a href="{{ route('gym.index') }}" class="inline-flex items-center text-emerald-600 font-semibold mt-4 hover:text-emerald-700">
                            Explore Gyms
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Feature 4: Easy Booking -->
                    <div class="feature-card bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-lg hover:border-emerald-300 transition-all">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Booking</h3>
                        <p class="text-gray-600 leading-relaxed">Simple and transparent online reservation system with instant confirmation for all wellness services.</p>
                    </div>

                    <!-- Feature 5: Vouchers & Deals -->
                    <div class="feature-card bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-lg hover:border-emerald-300 transition-all">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Vouchers & Deals</h3>
                        <p class="text-gray-600 leading-relaxed">Exclusive vouchers and special promotional offers to save more on your wellness journey.</p>
                        <a href="{{ route('voucher') }}" class="inline-flex items-center text-emerald-600 font-semibold mt-4 hover:text-emerald-700">
                            View Vouchers
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Feature 6: AI Chatbot -->
                    <div class="feature-card bg-white rounded-2xl p-8 border-2 border-gray-100 shadow-lg hover:border-emerald-300 transition-all">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">AI Chatbot Support</h3>
                        <p class="text-gray-600 leading-relaxed">24/7 intelligent chatbot assistance powered by AI for instant answers and personalized recommendations.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="py-20 bg-gradient-to-br from-emerald-50 to-teal-50">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div>
                        <div class="inline-block px-4 py-2 bg-white rounded-full mb-6 shadow-sm">
                            <span class="text-emerald-700 font-bold text-sm">WHY VITALIFE</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">Why Choose Vitalife?</h2>
                        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                            We're more than just a booking platform. We're your wellness travel companion, committed to making healthy living accessible wherever you go.
                        </p>

                        <div class="space-y-6">
                            <!-- Benefit 1 -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-bold text-gray-900 mb-1">Verified Partners</h4>
                                    <p class="text-gray-600">All our wellness centers are thoroughly vetted and verified for quality and safety.</p>
                                </div>
                            </div>

                            <!-- Benefit 2 -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-bold text-gray-900 mb-1">Best Price Guarantee</h4>
                                    <p class="text-gray-600">Get the best deals and exclusive discounts on wellness services.</p>
                                </div>
                            </div>

                            <!-- Benefit 3 -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-bold text-gray-900 mb-1">Flexible Booking</h4>
                                    <p class="text-gray-600">Easy cancellation and rescheduling options for your convenience.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Image -->
                    <div class="relative">
                        <div class="aspect-w-4 aspect-h-3 rounded-3xl overflow-hidden shadow-2xl">
                            <img src="{{ asset('image/Logo-Healife.png') }}" alt="Vitalife Platform" class="w-full h-full object-contain p-12 bg-gradient-to-br from-emerald-100 to-teal-100">
                        </div>
                        <!-- Floating Card -->
                        <div class="absolute -bottom-8 -left-8 bg-white rounded-2xl p-6 shadow-2xl">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-2xl font-black text-gray-900">4.8/5.0</div>
                                    <div class="text-sm text-gray-600">Customer Rating</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 relative overflow-hidden bg-gradient-to-r from-emerald-600 to-teal-600">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

            <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                    Ready to Start Your Wellness Journey?
                </h2>
                <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-3xl mx-auto">
                    Discover amazing spa, yoga, and fitness experiences with exclusive deals and personalized recommendations
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('spa.index') }}" class="inline-flex items-center justify-center px-10 py-4 bg-white text-emerald-600 font-bold rounded-xl hover:bg-emerald-50 transition-all shadow-xl hover:shadow-2xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Explore Wellness Centers
                    </a>
                    <a href="{{ route('voucher') }}" class="inline-flex items-center justify-center px-10 py-4 bg-emerald-700 text-white font-bold rounded-xl hover:bg-emerald-800 transition-all shadow-xl hover:shadow-2xl transform hover:scale-105 border-2 border-white">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        Get Exclusive Vouchers
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="mt-12 pt-12 border-t border-white/20">
                    <p class="text-white/80 text-sm mb-6">Trusted by wellness enthusiasts worldwide</p>
                    <div class="flex flex-wrap justify-center items-center gap-8">
                        <div class="text-white/90 font-semibold">🏆 Best Platform 2024</div>
                        <div class="text-white/90 font-semibold">⭐ 4.8/5 Rating</div>
                        <div class="text-white/90 font-semibold">🔒 Secure Booking</div>
                        <div class="text-white/90 font-semibold">💚 100% Verified</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chatbot Widget -->
        <x-chatbot-widget defaultCategory="General Information" />
    </div>
</x-app-layout>
