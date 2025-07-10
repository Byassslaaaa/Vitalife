@extends('layouts.admin')

@section('judul-halaman', 'Manajemen Detail Yoga')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Manajemen Detail Yoga</h1>
            <a href="{{ route('admin.yogas.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Tambah Yoga Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($yogas->count() > 0)
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="w-1/3 py-4 px-5 text-center">
                                <div class="text-3xl font-bold text-blue-500">{{ $yogas->count() }}</div>
                                <div class="text-gray-600">Total Yoga</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="w-1/3 py-4 px-5 text-center">
                                <div class="text-3xl font-bold text-green-500">{{ $yogas->where('detailConfig')->count() }}</div>
                                <div class="text-gray-600">Detail Terkonfigurasi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="w-1/3 py-4 px-5 text-center">
                                <div class="text-3xl font-bold text-yellow-500">{{ $yogas->where('detailConfig', null)->count() }}</div>
                                <div class="text-gray-600">Perlu Konfigurasi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="w-1/3 py-4 px-5 text-center">
                                <div class="text-3xl font-bold text-purple-500">{{ $yogas->where('is_active', true)->count() }}</div>
                                <div class="text-gray-600">Yoga Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yoga Cards Grid -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Detail Yoga</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($yogas as $yoga)
                            <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                <!-- Yoga Header -->
                                <div class="flex items-center mb-3">
                                    @if($yoga->image)
                                        <img src="{{ asset($yoga->image) }}" alt="{{ $yoga->nama }}"
                                             class="w-10 h-10 rounded-full object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $yoga->nama }}</h3>
                                        <p class="text-sm text-gray-500">ID: {{ $yoga->id_yoga }}</p>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="mb-3">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-gray-400 mt-1 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600">{{ Str::limit($yoga->alamat, 60) }}</p>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex justify-between items-center mb-3">
                                    <div>
                                        @if($yoga->detailConfig)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                                </svg>
                                                Terkonfigurasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">Status</p>
                                        <p class="text-sm font-medium text-indigo-600">Ready to Book</p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.yoga-details.show', $yoga->id_yoga) }}"
                                       class="flex-1 bg-indigo-500 hover:bg-indigo-600 text-white text-center py-2 rounded text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('admin.yoga-details.edit', $yoga->id_yoga) }}"
                                       class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                </div>

                                <!-- Preview Button (Separate Row) -->
                                <div class="mt-2">
                                    <a href="{{ route('admin.yoga-details.preview', $yoga->id_yoga) }}"
                                       target="_blank"
                                       class="w-full bg-green-500 hover:bg-green-600 text-white text-center py-2 rounded text-sm font-medium transition-colors block">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Preview Detail Page
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if(method_exists($yogas, 'links'))
                <div class="mt-6 flex justify-center">
                    {{ $yogas->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data yoga</h3>
                    <p class="text-gray-500 mb-6">Tambahkan yoga terlebih dahulu untuk mengelola detail halaman.</p>
                    <a href="{{ route('admin.yogas.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Yoga Baru
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
