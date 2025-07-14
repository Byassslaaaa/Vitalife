@extends('layouts.admin')

@section('judul-halaman', 'Yoga')

@section('content')
    <?php // Fallback data loading jika controller bermasalah
    if (!isset($yogas) || !isset($statistics)) {
        try {
            $yogas = \App\Models\Yoga::with(['detailConfig', 'yogaServices'])->get();
            $statistics = [
                'total_count' => $yogas->count(),
                'active_count' => $yogas->count(), // Semua yoga dianggap aktif
                'inactive_count' => 0,
                'active_percentage' => 100,
            ];
        } catch (Exception $e) {
            $yogas = collect([]);
            $statistics = [
                'total_count' => 0,
                'active_count' => 0,
                'inactive_count' => 0,
                'active_percentage' => 0,
            ];
        }
    }
    ?>
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Daftar Yoga</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.yoga-services.index') }}"
                    class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded">
                    Kelola Services
                </a>
                <a href="{{ route('admin.yogas.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Tambah Yoga Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($yogas->count() > 0)
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-purple-600">{{ $statistics['total_count'] }}</h3>
                    <p class="text-gray-600 text-sm">Total Yoga</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-green-600">{{ $statistics['active_count'] }}</h3>
                    <p class="text-gray-600 text-sm">Yoga Aktif</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-blue-600">
                        {{ $yogas->avg('harga') ? 'Rp ' . number_format($yogas->avg('harga'), 0, ',', '.') : 'N/A' }}</h3>
                    <p class="text-gray-600 text-sm">Rata-rata Harga</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-indigo-600">{{ $yogas->where('detailConfig')->count() }}</h3>
                    <p class="text-gray-600 text-sm">Detail Lengkap</p>
                </div>
            </div>

            <!-- Yoga Cards Grid -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Yoga</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($yogas as $yoga)
                            <div class="bg-gray-50 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                                <!-- Yoga Header -->
                                <div class="flex items-center mb-4">
                                    @if ($yoga->image)
                                        <img src="{{ asset($yoga->image) }}"
                                            alt="{{ is_array($yoga->nama) ? implode(', ', $yoga->nama) : $yoga->nama }}"
                                            class="w-12 h-12 rounded-full object-cover mr-3">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">
                                            {{ is_array($yoga->nama) ? implode(', ', $yoga->nama) : $yoga->nama }}</h3>
                                        <p class="text-sm text-gray-500">ID: {{ $yoga->id_yoga }}</p>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        <span class="text-lg font-bold text-green-600">
                                            Rp
                                            {{ number_format(is_array($yoga->harga) ? $yoga->harga[0] : $yoga->harga, 0, ',', '.') }}
                                        </span>
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
                                        <p class="text-sm text-gray-600">
                                            {{ Str::limit(is_array($yoga->alamat) ? implode(', ', $yoga->alamat) : $yoga->alamat, 60) }}
                                        </p>
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
                                        <span
                                            class="text-sm text-gray-600">{{ is_array($yoga->noHP) ? implode(', ', $yoga->noHP) : $yoga->noHP }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.yogas.show', $yoga->id_yoga) }}"
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
                                    <a href="{{ route('admin.yogas.edit', $yoga->id_yoga) }}"
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

                                <!-- Delete Button (Separate Row) -->
                                <div class="mt-2">
                                    <form action="{{ route('admin.yogas.destroy', $yoga->id_yoga) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus yoga ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white text-center py-2 px-3 rounded text-sm font-medium transition-colors">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
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
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data yoga</h3>
                    <p class="text-gray-500 mb-6">Tambahkan yoga terlebih dahulu untuk mulai mengelola layanan yoga.</p>
                    <a href="{{ route('admin.yogas.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Yoga Pertama
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
