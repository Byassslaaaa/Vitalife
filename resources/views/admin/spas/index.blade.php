@extends('layouts.admin')

@section('judul-halaman', 'Spa Management')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Daftar Spa</h1>
            <a href="{{ route('admin.spas.create') }}" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded">
                Tambah Spa Baru
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @php
            $spas = \App\Models\Spa::all() ?? collect([]);
        @endphp

        @if ($spas->count() > 0)
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-pink-600">{{ $spas->count() }}</h3>
                    <p class="text-gray-600 text-sm">Total Spa</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-green-600">{{ $spas->where('is_open', true)->count() }}</h3>
                    <p class="text-gray-600 text-sm">Spa Buka</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-red-600">{{ $spas->where('is_open', false)->count() }}</h3>
                    <p class="text-gray-600 text-sm">Spa Tutup</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-indigo-600">
                        @php
                            $serviceCount = 0;
                            foreach ($spas as $spa) {
                                if ($spa->services && is_array($spa->services)) {
                                    $serviceCount += count($spa->services);
                                }
                            }
                        @endphp
                        {{ $serviceCount }}
                    </h3>
                    <p class="text-gray-600 text-sm">Total Services</p>
                </div>
            </div>

            <!-- Spa Cards Grid -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Spa</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($spas as $spa)
                            <div class="bg-gray-50 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                                <!-- Spa Header -->
                                <div class="flex items-center mb-4">
                                    @if ($spa->image)
                                        <img src="{{ asset($spa->image) }}" alt="{{ $spa->nama }}"
                                            class="w-12 h-12 rounded-full object-cover mr-3">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $spa->nama }}</h3>
                                        <p class="text-sm text-gray-500">ID: {{ $spa->id_spa }}</p>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="mb-4">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-gray-400 mt-1 mr-2 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600">{{ Str::limit($spa->alamat, 60) }}</p>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $spa->noHP }}</span>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $spa->is_open ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $spa->is_open ? 'Buka' : 'Tutup' }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">Services</p>
                                        <p class="text-sm font-medium text-indigo-600">
                                            @php
                                                $totalServices = 0;
                                                if ($spa->services && is_array($spa->services)) {
                                                    $totalServices += count($spa->services);
                                                }
                                            @endphp
                                            {{ $totalServices }} layanan
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2 mb-2">
                                    <a href="#"
                                        class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 px-3 rounded text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Lihat
                                    </a>
                                    <a href="#"
                                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data spa</h3>
                    <p class="text-gray-500 mb-6">Tambahkan spa terlebih dahulu untuk mulai mengelola layanan spa.</p>
                    <a href="{{ route('admin.spas.create') }}"
                        class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Spa Pertama
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
@endsection
