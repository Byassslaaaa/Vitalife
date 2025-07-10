@props(['active' => false])

<aside class="flex-shrink-0 bg-black transition-all duration-300 ease-in-out" x-data="{ expanded: false }"
    :class="expanded ? 'w-64' : 'w-16'">
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-center h-16 bg-black">
            <button @click="expanded = !expanded" class="text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto">
            <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="ml-2" x-show="expanded">Dashboard</span>
            </x-sidebar-link>

            <!-- Tambahkan link sidebar lainnya di sini -->
        </nav>
    </div>
</aside>
