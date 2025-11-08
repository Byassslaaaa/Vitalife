<footer class="py-8" style ="background-color: #BED9FE;">
    <div class="w-full mx-auto px-8">
        <div class="bg-gray-800 text-white rounded-3xl p-8" style="background-color: #374151;">
            <!-- Main Content -->
            <div class="flex justify-between items-start">
                <!-- Left Section - Logo, Description, Contact & About -->
                <div class="flex-1 max-w-md pr-18 pl-10">
                    <!-- Logo -->
                    <div class="flex items-center mb-6 mt-2 ">
                        <img src="{{ asset('image/Logo_healife.png') }}" alt="HeaLife Logo" class="w-38 h-12 mr-3">
                    </div>

                    <!-- Description -->
                    <p class="text-gray-300 text-xs leading-relaxed mb-8" style="font-size: 15px; line-height: 1.4;">
                        The HeaLife project is a mobile application development project aimed at enhancing
                        health and wellness tourism in Indonesia. It helps users find the best yoga and spa
                        centers, events, consult with doctors, and track their wellness progress.
                    </p>

                    <!-- Contact Us and About Us -->
                    <div class="space-y-3">
                        <div>
                            <h4
                                class="text-white font-semibold text-base cursor-pointer hover:text-gray-300 transition-colors">
                                Contact Us</h4>
                        </div>
                        <div>
                            <h4
                                class="text-white font-semibold text-base cursor-pointer hover:text-gray-300 transition-colors">
                                About Us</h4>
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
                                    <a href="/spa"
                                        class="text-gray-300 text-sm hover:text-white transition-colors">SPA</a>
                                @else
                                    <span class="text-gray-300 text-sm">SPA</span>
                                @endauth
                            </li>
                            <li>
                                @auth
                                    <a href="/yoga"
                                        class="text-gray-300 text-sm hover:text-white transition-colors">YOGA</a>
                                @else
                                    <span class="text-gray-300 text-sm">YOGA</span>
                                @endauth
                            </li>
                            <li>
                                @auth
                                    <a href="/event"
                                        class="text-gray-300 text-sm hover:text-white transition-colors">GYM</a>
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
                                    <a href="/event"
                                        class="text-gray-300 text-sm hover:text-white transition-colors">EVENT</a>
                                @else
                                    <span class="text-gray-300 text-sm">EVENT</span>
                                @endauth
                            </li>
                            <li>
                                @auth
                                    <a href="/?scroll=voucher"
                                        class="text-gray-300 text-sm hover:text-white transition-colors">Voucher</a>
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
                                <a href="#"
                                    class="text-gray-300 text-sm hover:text-white transition-colors">Instagram</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="text-gray-300 text-sm hover:text-white transition-colors">Tiktok</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="text-gray-300 text-sm hover:text-white transition-colors">Youtube</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="mt-12 flex justify-center items-end pl-10">
                <!-- Copyright -->
                <div>
                    <p class="text-xs text-gray-400" style="font-size: 15px; line-height: 1.4;">
                        © All Right reserved | Owned by HeaLife
                    </p>
                </div>
            </div>

        </div>
    </div>
