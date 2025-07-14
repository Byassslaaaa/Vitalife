@extends('layouts.admin')

@section('judul-halaman', 'Manajemen Gym')

@section('content')
    <?php // Fallback data loading jika controller bermasalah
    if (!isset($gyms) || !isset($statistics)) {
        try {
            $gyms = \App\Models\Gym::with(['gymDetail'])->get();
            $statistics = [
                'total_count' => $gyms->count(),
                'active_count' => $gyms->where('is_open', true)->count(),
                'inactive_count' => $gyms->where('is_open', false)->count(),
                'active_percentage' => $gyms->count() > 0 ? round(($gyms->where('is_open', true)->count() / $gyms->count()) * 100, 1) : 0,
            ];
        } catch (Exception $e) {
            $gyms = collect([]);
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
            <h1 class="text-2xl font-bold">Manajemen Gym</h1>
            <a href="{{ route('admin.gyms.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Tambah Gym Baru
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($gyms->count() > 0)
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-blue-600">{{ $statistics['total_count'] }}</h3>
                    <p class="text-gray-600 text-sm">Total Gym</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-green-600">{{ $statistics['active_count'] }}</h3>
                    <p class="text-gray-600 text-sm">Gym Buka</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-red-600">{{ $statistics['inactive_count'] }}</h3>
                    <p class="text-gray-600 text-sm">Gym Tutup</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-purple-600">{{ $gyms->where('gymDetail')->count() }}</h3>
                    <p class="text-gray-600 text-sm">Detail Lengkap</p>
                </div>
            </div>

            <!-- Gym Cards Grid -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Gym</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($gyms as $gym)
                            <div class="bg-gray-50 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                                <!-- Gym Header -->
                                <div class="flex items-center mb-4">
                                    @if ($gym->image)
                                        <img src="{{ asset($gym->image) }}" alt="{{ $gym->nama }}"
                                            class="w-12 h-12 rounded-full object-cover mr-3">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $gym->nama }}</h3>
                                        <p class="text-sm text-gray-500">ID: {{ $gym->id_gym }}</p>
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
                                        <p class="text-sm text-gray-600">{{ Str::limit($gym->alamat, 60) }}</p>
                                    </div>
                                </div>

                                <!-- Services Info -->
                                @if ($gym->services && is_array($gym->services))
                                    <div class="mb-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach (array_slice($gym->services, 0, 2) as $service)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $service['name'] ?? 'Service' }}
                                                </span>
                                            @endforeach
                                            @if (count($gym->services) > 2)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    +{{ count($gym->services) - 2 }} lainnya
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Status Badge -->
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        @if ($gym->is_open)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4"></path>
                                                </svg>
                                                Buka
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tutup
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">Detail</p>
                                        @if ($gym->gymDetail)
                                            <p class="text-sm font-medium text-green-600">Lengkap</p>
                                        @else
                                            <p class="text-sm font-medium text-yellow-600">Perlu Setup</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.gyms.show', $gym->id_gym) }}"
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
                                    <a href="{{ route('admin.gyms.edit', $gym->id_gym) }}"
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

                                <!-- Toggle Status & Delete (Separate Row) -->
                                <div class="mt-2 flex space-x-2">
                                    <form action="{{ route('admin.gyms.toggle-status', $gym->id_gym) }}" method="POST"
                                        class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full bg-{{ $gym->is_open ? 'red' : 'green' }}-500 hover:bg-{{ $gym->is_open ? 'red' : 'green' }}-600 text-white py-2 px-3 rounded text-sm font-medium transition-colors">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                @if ($gym->is_open)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                                    </path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @endif
                                            </svg>
                                            {{ $gym->is_open ? 'Tutup' : 'Buka' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.gyms.destroy', $gym->id_gym) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus gym {{ $gym->nama }}?');"
                                        class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded text-sm font-medium transition-colors">
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

            <!-- Quick Links -->
            <div class="mt-6 bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Total: <span class="font-medium">{{ $gyms->count() }}</span> gym terdaftar
                        <span class="ml-4 text-green-600">{{ $gyms->where('is_open', true)->count() }} buka</span>
                        <span class="ml-2 text-red-600">{{ $gyms->where('is_open', false)->count() }} tutup</span>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.gym-details.index') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Kelola Detail Halaman →
                        </a>
                        <a href="{{ route('admin.services.index') }}"
                            class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                            Kelola Layanan →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if (method_exists($gyms, 'links'))
                <div class="mt-6 flex justify-center">
                    {{ $gyms->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data gym</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan gym pertama Anda.</p>
                    <a href="{{ route('admin.gyms.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Gym Baru
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Enhanced confirmation dialog
        document.querySelectorAll('form[onsubmit*="confirm"]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const gymName = this.getAttribute('onsubmit').match(/gym (.+?)\?/)[1];
                if (confirm(
                        `Apakah Anda yakin ingin menghapus gym "${gymName}"?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.`
                    )) {
                    this.submit();
                }
            });
        });
    </script>
@endsection
