@extends('layouts.admin')

@section('judul-halaman', 'Detail Yoga Service')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Detail Yoga Service</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.yoga-services.edit', $service->id) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Edit Service
                </a>
                <a href="{{ route('admin.yoga-services.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Service Info Card -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $service->name }}</h2>
                                <div class="flex items-center space-x-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        {{ ucfirst($service->category) }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $service->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                    @if ($service->difficulty_level)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst(str_replace('_', ' ', $service->difficulty_level)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if ($service->image)
                                <img src="{{ asset($service->image) }}" alt="{{ $service->name }}"
                                    class="w-24 h-24 rounded-lg object-cover">
                            @endif
                        </div>

                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500">Harga</p>
                                        <p class="text-lg font-bold text-green-600">Rp
                                            {{ number_format($service->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            @if ($service->duration)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm text-gray-500">Durasi</p>
                                            <p class="text-lg font-bold text-blue-600">{{ $service->duration }} menit</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($service->max_participants)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm text-gray-500">Max Peserta</p>
                                            <p class="text-lg font-bold text-purple-600">{{ $service->max_participants }}
                                                orang</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $service->description }}</p>
                        </div>

                        <!-- Benefits -->
                        @if ($service->benefits)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Manfaat</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $service->benefits }}</p>
                            </div>
                        @endif

                        <!-- Requirements -->
                        @if ($service->requirements)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Persyaratan</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $service->requirements }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Yoga Info -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Yoga</h3>
                        @if ($service->yoga)
                            <div class="flex items-center mb-4">
                                @if ($service->yoga->image)
                                    <img src="{{ asset($service->yoga->image) }}" alt="{{ $service->yoga->nama }}"
                                        class="w-12 h-12 rounded-full object-cover mr-3">
                                @else
                                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $service->yoga->nama }}</h4>
                                    <p class="text-sm text-gray-500">ID: {{ $service->yoga->id_yoga }}</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">{{ $service->yoga->alamat }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $service->yoga->noHP }}</span>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-600">
                                        Rp {{ number_format($service->yoga->harga, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('admin.yogas.show', $service->yoga->id_yoga) }}"
                                    class="w-full bg-purple-500 hover:bg-purple-600 text-white text-center py-2 px-4 rounded font-medium">
                                    Lihat Detail Yoga
                                </a>
                            </div>
                        @else
                            <p class="text-gray-500">Yoga tidak ditemukan</p>
                        @endif
                    </div>
                </div>

                <!-- Service Meta -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tambahan</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Dibuat pada</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $service->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Terakhir diupdate</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $service->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">ID Service</p>
                                <p class="text-sm font-medium text-gray-900">{{ $service->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
                        <div class="space-y-3">
                            <form action="{{ route('admin.yoga-services.toggle-status', $service->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full {{ $service->is_active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white text-center py-2 px-4 rounded font-medium">
                                    {{ $service->is_active ? 'Nonaktifkan Service' : 'Aktifkan Service' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.yoga-services.destroy', $service->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus service ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded font-medium">
                                    Hapus Service
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
