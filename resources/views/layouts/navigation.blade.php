<nav x-data="{ open: false }"
    class="bg-gray-800 border-b border-gray-700 rounded-bl-lg rounded-br-lg fixed top-0 left-0 right-0 z-50 shadow-lg"
    style="background-color: #374151;">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo1 class="block w-full h-full object-contain" />
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:flex sm:items-center">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                    class="text-gray-300 hover:text-white transition duration-150 ease-in-out font-medium {{ request()->routeIs('dashboard') ? 'text-white' : '' }}">
                    {{ __('Dashboard') }}
                </x-nav-link>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-300 hover:text-white focus:outline-none focus:text-white transition duration-150 ease-in-out {{ request()->routeIs('spa.index') || request()->routeIs('yoga.index') || request()->routeIs('gym.index') ? 'text-white' : '' }}"
                            style="height: 64px; display: flex; align-items: center;">
                            {{ __('Feature') }}
                            <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="bg-gray-800 border border-gray-700 rounded-md shadow-lg">
                            <x-dropdown-link :href="route('spa.index')" :active="request()->routeIs('spa.index')"
                                class="text-gray-300 hover:text-white hover:bg-gray-700 transition-colors">
                                {{ __('SPA') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('yoga.index')" :active="request()->routeIs('yoga.index')"
                                class="text-gray-300 hover:text-white hover:bg-gray-700 transition-colors">
                                {{ __('Yoga') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('gym.index')" :active="request()->routeIs('gym.index')"
                                class="text-gray-300 hover:text-white hover:bg-gray-700 transition-colors">
                                {{ __('GYM') }}
                            </x-dropdown-link>
                        </div>
                    </x-slot>
                </x-dropdown>

                <x-nav-link :href="route('voucher')" :active="request()->routeIs('voucher')"
                    class="text-gray-300 hover:text-white transition duration-150 ease-in-out font-medium {{ request()->routeIs('voucher') ? 'text-white' : '' }}">
                    {{ __('Voucher') }}
                </x-nav-link>
            </div>

            <!-- Login Button -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white hover:bg-gray-600 transition duration-150 ease-in-out"
                    style="background-color: #374151;">
                    Login
                </a>
            </div>



            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 bg-gray-800 border-t border-gray-700" style="background-color: #374151;">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-gray-300 hover:text-white hover:bg-gray-700">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Features Submenu -->
            <div class="pl-4">
                <div class="text-sm text-gray-400 font-medium mb-2">Features</div>
                <x-responsive-nav-link :href="route('spa.index')" :active="request()->routeIs('spa.index')"
                    class="text-gray-300 hover:text-white hover:bg-gray-700">
                    {{ __('SPA') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('yoga.index')" :active="request()->routeIs('yoga.index')"
                    class="text-gray-300 hover:text-white hover:bg-gray-700">
                    {{ __('Yoga') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('gym.index')" :active="request()->routeIs('gym.index')"
                    class="text-gray-300 hover:text-white hover:bg-gray-700">
                    {{ __('GYM') }}
                </x-responsive-nav-link>
            </div>

            <x-responsive-nav-link :href="route('voucher')" :active="request()->routeIs('voucher')"
                class="text-gray-300 hover:text-white hover:bg-gray-700">
                {{ __('Voucher') }}
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Login -->
        <div class="pt-4 pb-1 border-t border-gray-700" style="background-color: #374151;">
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('login')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                    {{ __('Login') }}
                </x-responsive-nav-link>
            </div>
        </div>


    </div>
</nav>
