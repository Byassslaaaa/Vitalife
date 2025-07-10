<footer class="py-8" style ="background-color: #BED9FE;">
    <div class="w-full mx-auto px-8">
        <div class="bg-gray-800 text-white rounded-3xl p-8" x-data="{ open: false }" style="background-color: #374151;">
            <!-- Main Content -->
            <div class="flex justify-between items-start">
                <!-- Left Section - Logo, Description, Contact & About -->
                <div class="flex-1 max-w-md pr-18 pl-10">
                    <!-- Logo -->
                    <div class="flex items-center mb-6 mt-2 ">
                        <img src="../image/LOGO_1.png" alt="Vitalife Logo" class="w-38 h-12 mr-3">
                    </div>

                    <!-- Description -->
                    <p class="text-gray-300 text-xs leading-relaxed mb-8" style="font-size: 15px; line-height: 1.4;">
                        The Vitalife project is a mobile application development project aimed at enhancing 
                        health and wellness tourism in Indonesia. It helps users find the best yoga and spa 
                        centers, events, consult with doctors, and track their wellness progress.
                    </p>

                    <!-- Contact Us and About Us -->
                    <div class="space-y-3">
                        <div>
                            <h4 class="text-white font-semibold text-base cursor-pointer hover:text-gray-300 transition-colors">Contact Us</h4>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-base cursor-pointer hover:text-gray-300 transition-colors">About Us</h4>
                        </div>
                    </div>
                </div>

                <!-- Right Section - Three Columns -->

            <div class="flex justify-center space-x-16 mt-4">
                <!-- Feature Column -->
                <div class="w-40">
                    <h4 class="font-semibold text-white mb-4 text-base">Feature</h4>
                    <ul class="space-y-2">
                        <li>
                            @auth
                                <a href="/spa" class="text-gray-300 text-sm hover:text-white transition-colors">SPA</a>
                            @else
                                <span class="text-gray-300 text-sm">SPA</span>
                            @endauth
                        </li>
                        <li>
                            @auth
                                <a href="/yoga" class="text-gray-300 text-sm hover:text-white transition-colors">YOGA</a>
                            @else
                                <span class="text-gray-300 text-sm">YOGA</span>
                            @endauth
                        </li>
                        <li>
                            @auth
                                <a href="/event" class="text-gray-300 text-sm hover:text-white transition-colors">GYM</a>
                            @else
                                <span class="text-gray-300 text-sm">GYM</span>
                            @endauth
                        </li>
                    </ul>
                </div>

                <!-- Other Column -->
                <div class="w-40">
                    <h4 class="font-semibold text-white mb-4 text-base">Other</h4>
                    <ul class="space-y-2">
                        <li>
                            @auth
                                <a href="/event" class="text-gray-300 text-sm hover:text-white transition-colors">EVENT</a>
                            @else
                                <span class="text-gray-300 text-sm">EVENT</span>
                            @endauth
                        </li>
                        <li>
                            @auth
                                <a href="/?scroll=voucher" class="text-gray-300 text-sm hover:text-white transition-colors">Voucher</a>
                            @else
                                <span class="text-gray-300 text-sm">Voucher</span>
                            @endauth
                        </li>
                    </ul>
                </div>

                <!-- Social Media Column -->
                <div class="w-40 ">
                    <h4 class="font-semibold text-white mb-4 text-base">Social Media</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-gray-300 text-sm hover:text-white transition-colors">Instagram</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 text-sm hover:text-white transition-colors">Tiktok</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 text-sm hover:text-white transition-colors">Youtube</a>
                        </li>
                    </ul>
                </div>
            </div>
            </div>

            <!-- Bottom Section -->
            <div class="mt-12 flex justify-between items-end pl-10">
                <!-- Left - Copyright -->
                <div>
                    <p class="text-xs text-gray-400" style="font-size: 15px; line-height: 1.4;">
                        © All Right reserved | Owned by Vitalife
                    </p>
                </div>

                <!-- Right - Partner Section -->
                <div class="text-right mr-10">
                    <p class="text-xs text-gray-300 mb-3"style="font-size: 15px; line-height: 1.4;">
                        If you are interested in becoming a partner, click the button below:
                    </p>
                    <button @click="open = true" 
                        class="bg-white hover:bg-gray-100 text-gray-800 px-4 py-2 rounded-full font-medium transition-colors text-sm">
                        Join as a Partner
                    </button>
                </div>
            </div>

            <!-- Partner Modal -->
            <div x-show="open" x-transition
                class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
                <div class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full m-4 relative z-10">
                    <div class="bg-white px-6 pt-6 pb-5">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-lg font-medium text-gray-900">Choose Partner Type</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <!-- Spa -->
                            <a href="#" class="group text-center">
                                <div class="border rounded-lg p-4 transform group-hover:scale-105 group-hover:shadow-lg transition-all">
                                    <img src="{{ asset('image/massage.png') }}" class="w-16 h-16 mx-auto mb-3" alt="Spa">
                                    <p class="text-gray-800 font-semibold">Spa</p>
                                </div>
                            </a>
                            <!-- Yoga -->
                            <a href="#" class="group text-center">
                                <div class="border rounded-lg p-4 transform group-hover:scale-105 group-hover:shadow-lg transition-all">
                                    <img src="{{ asset('image/lotus.png') }}" class="w-16 h-16 mx-auto mb-3" alt="Yoga">
                                    <p class="text-gray-800 font-semibold">Yoga</p>
                                </div>
                            </a>
                            <!-- Event -->
                            <a href="#" class="group text-center">
                                <div class="border rounded-lg p-4 transform group-hover:scale-105 group-hover:shadow-lg transition-all">
                                    <img src="{{ asset('image/event-list.png') }}" class="w-16 h-16 mx-auto mb-3" alt="Event">
                                    <p class="text-gray-800 font-semibold">Event</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




