<aside class="aside bg-black w-48 min-h-screen fixed left-0 top-0 flex flex-col">
    <!-- Fixed logo section -->
    < <!-- Event -->"py-6 px-4 sticky top-0 bg-black z-10">
        <div class="flex items-center justify-start">
            <!-- VITALIFE Logo -->
            <div class="flex items-center space-x-2">
                <!-- Leaf Icon -->
                <div class="w-8 h-8 rounded-full border-2 border-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c1.19 0 2.34-.21 3.41-.6.3-.11.49-.4.49-.72 0-.43-.35-.78-.78-.78-.25 0-.47.12-.61.3-.85.29-1.76.44-2.71.44-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8c0 .95-.15 1.86-.44 2.71-.18.14-.3.36-.3.61 0 .43.35.78.78.78.32 0 .61-.19.72-.49.39-1.07.6-2.22.6-3.41C22 6.48 17.52 2 12 2z" />
                        <path
                            d="M12 6c-3.31 0-6 2.69-6 6 0 1.66.67 3.16 1.76 4.24l1.41-1.41C8.67 14.33 8.2 13.2 8.2 12c0-2.1 1.7-3.8 3.8-3.8s3.8 1.7 3.8 3.8c0 1.2-.47 2.33-.97 2.83l1.41 1.41C17.33 15.16 18 13.66 18 12c0-3.31-2.69-6-6-6z" />
                        <circle cx="12" cy="12" r="2" />
                    </svg>
                </div>
                <!-- VITALIFE Text -->
                <span class="text-white font-bold text-lg tracking-wide">VITALIFE</span>
            </div>
        </div>
        </div>

        <!-- Scrollable content -->
        <div class="flex-grow overflow-y-auto py-4 px-4 scrollbar-hide">
            <nav class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="p-3 rounded-lg flex items-center {{ Request::routeIs('admin.dashboard') ? 'bg-white text-black' : 'text-white hover:bg-gray-700' }} transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <!-- SPA Management Section -->
                <div class="space-y-1">
                    <a href="{{ route('admin.spas.index') }}"
                        class="p-3 rounded-lg flex items-center {{ Request::routeIs('admin.spas.*') || Request::routeIs('admin.spa-details.*') ? 'bg-white text-black' : 'text-white hover:bg-gray-700' }} transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                        </svg>
                        <span class="text-sm font-medium">SPA</span>
                    </a>

                    @if (Request::routeIs('admin.spas.*') || Request::routeIs('admin.spa-details.*'))
                        <div class="ml-6 space-y-1">
                            <a href="{{ route('admin.spas.services.index', Request::segment(3) ?? 1) }}"
                                class="p-2 pl-4 rounded-lg flex items-center text-xs {{ Request::routeIs('admin.spas.services.*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }} transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                <span>Services</span>
                            </a>

                            <a href="{{ route('admin.spa-details.index') }}"
                                class="p-2 pl-4 rounded-lg flex items-center text-xs {{ Request::routeIs('admin.spa-details.*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }} transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Detail Pages</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Yoga Management Section -->
                <div class="space-y-1">
                    <a href="{{ route('admin.yogas.index') }}"
                        class="p-3 rounded-lg flex items-center {{ Request::routeIs('admin.yogas.*') || Request::routeIs('admin.yoga-details.*') ? 'bg-white text-black' : 'text-white hover:bg-gray-700' }} transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z" />
                        </svg>
                        <span class="text-sm font-medium">Yoga</span>
                    </a>

                    @if (Request::routeIs('admin.yogas.*') || Request::routeIs('admin.yoga-details.*'))
                        <div class="ml-6 space-y-1">
                            <a href="{{ route('admin.yoga-details.index') }}"
                                class="p-2 pl-4 rounded-lg flex items-center text-xs {{ Request::routeIs('admin.yoga-details.*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }} transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Detail Pages</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- GYM Management Section -->
                <div class="space-y-1">
                    <a href="{{ route('admin.gyms.index') }}"
                        class="p-3 rounded-lg flex items-center {{ Request::routeIs('admin.gyms.*') || Request::routeIs('admin.gym-details.*') || Request::routeIs('admin.services.*') ? 'bg-white text-black' : 'text-white hover:bg-gray-700' }} transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.57 14.86L22 13.43 20.57 12 17 15.57 8.43 7 12 3.43 10.57 2 9.14 3.43 7.71 2 5.57 4.14 4.14 2.71 2.71 4.14l1.43 1.43L2 7.71l1.43 1.43L2 10.57 3.43 12 7 8.43 15.57 17 12 20.57 13.43 22l1.43-1.43L16.29 22l2.14-2.14 1.43 1.43 1.43-1.43-1.43-1.43L22 16.29l-1.43-1.43z" />
                        </svg>
                        <span class="text-sm font-medium">GYM</span>
                    </a>

                    @if (Request::routeIs('admin.gyms.*') || Request::routeIs('admin.gym-details.*') || Request::routeIs('admin.services.*'))
                        <div class="ml-6 space-y-1">
                            <a href="{{ route('admin.gym-details.index') }}"
                                class="p-2 pl-4 rounded-lg flex items-center text-xs {{ Request::routeIs('admin.gym-details.*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }} transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Detail Pages</span>
                            </a>

                            <a href="{{ route('admin.services.index') }}"
                                class="p-2 pl-4 rounded-lg flex items-center text-xs {{ Request::routeIs('admin.services.*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }} transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                <span>Services</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Event -->
                <a href="{{ route('admin.event.index') }}"
                    class="p-3 rounded-lg flex items-center {{ Request::routeIs('admin.event.*') ? 'bg-white text-black' : 'text-white hover:bg-gray-700' }} transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M13.49 5.48c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-3.6 13.9l1-4.4 2.1 2v6h2v-7.5l-2.1-2 .6-3c1.3 1.5 3.3 2.5 5.5 2.5v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1l-5.2 2.2v4.7h2v-3.4l1.8-.7-1.6 8.1-4.9-1-.4 2 7 1.4z" />
                    </svg>
                    <span class="text-sm font-medium">Event</span>
                </a>

                <!-- Account User -->
                <a href="{{ route('admin.accountuser') }}"
                    class="p-3 rounded-lg flex items-center {{ Request::routeIs('admin.accountuser') ? 'bg-white text-black' : 'text-white hover:bg-gray-700' }} transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">Account User</span>
                </a>

                <!-- Feedback User -->
                <a href="{{ route('admin.feedback') }}"
                    class="p-3 rounded-lg flex items-center {{ Request::routeIs('admin.feedback') ? 'bg-white text-black' : 'text-white hover:bg-gray-700' }} transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M3 6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-6.616l-2.88 2.592C8.537 20.461 7 19.776 7 18.477V17H5a2 2 0 0 1-2-2V6Zm4 2a1 1 0 0 0 0 2h5a1 1 0 1 0 0-2H7Zm8 0a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2h-2Zm-8 3a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2H7Zm5 0a1 1 0 1 0 0 2h5a1 1 0 1 0 0-2h-5Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">Feedback User</span>
                </a>

                <!-- Chat Messages -->
                <a href="{{ route('admin.chat') }}"
                    class="p-3 rounded-lg flex items-center {{ Request::routeIs('admin.chat') ? 'bg-white text-black' : 'text-white hover:bg-gray-700' }} transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M3 6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-6.616l-2.88 2.592C8.537 20.461 7 19.776 7 18.477V17H5a2 2 0 0 1-2-2V6Z" />
                    </svg>
                    <span class="text-sm font-medium">Chat Messages</span>
                    <span id="unread-chat-count"
                        class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden">0</span>
                </a>

                <!-- Vouchers -->
                <a href="{{ route('admin.vouchers.index') }}"
                    class="p-3 rounded-lg flex items-center {{ Request::routeIs('admin.vouchers.*') ? 'bg-white text-black' : 'text-white hover:bg-gray-700' }} transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v4c1.1 0 2 .9 2 2s-.9 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5zM13 12c0-.6-.4-1-1-1s-1 .4-1 1 .4 1 1 1 1-.4 1-1z" />
                    </svg>
                    <span class="text-sm font-medium">Vouchers</span>
                </a>
            </nav>
        </div>

        <!-- Logout -->
        <div class="py-4 px-4 sticky bottom-0 bg-black border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full p-3 rounded-lg flex items-center text-gray-400 hover:text-white hover:bg-gray-700 transition-colors duration-200"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
                    </svg>
                    <span class="text-sm font-medium">Logout</span>
                </button>
            </form>
        </div>
</aside>

<!-- Custom Styles -->
<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
