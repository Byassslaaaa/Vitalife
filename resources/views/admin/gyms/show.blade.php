@extends('layouts.admin')

@section('judul-halaman', 'Detail Gym')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detail Gym: {{ $gym->nama }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.gyms.edit', $gym->id_gym) }}" 
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit Gym
                </a>
                <a href="{{ route('admin.gyms.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
                <div class="flex items-center space-x-4">
                    @if($gym->image)
                        <img src="{{ asset($gym->image) }}" alt="{{ $gym->nama }}" 
                             class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold">{{ $gym->nama }}</h2>
                        <p class="text-blue-100">ID: {{ $gym->id_gym }}</p>
                        <div class="flex items-center mt-2">
                            @if($gym->is_open)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                    Buka
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                    Tutup
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Alamat
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $gym->alamat }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status Operasional
                        </h3>
                        <div class="flex items-center">
                            @if($gym->is_open)
                                <div class="flex items-center text-green-600">
                                    <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                    <span class="font-medium">Sedang Buka</span>
                                </div>
                            @else
                                <div class="flex items-center text-red-600">
                                    <div class="w-3 h-3 bg-red-400 rounded-full mr-2"></div>
                                    <span class="font-medium">Sedang Tutup</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Main Image -->
                @if($gym->image)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Utama Gym</h3>
                    <div class="rounded-lg overflow-hidden shadow-md">
                        <img src="{{ asset($gym->image) }}" alt="{{ $gym->nama }}" 
                             class="w-full h-64 object-cover">
                    </div>
                </div>
                @endif

                <!-- Services Section -->
                @if($gym->services && is_array($gym->services))
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Services Tersedia ({{ count($gym->services) }})
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($gym->services as $index => $service)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                <!-- Service Image -->
                                @if(isset($service['image']) && $service['image'])
                                    <div class="flex justify-center mb-4">
                                        <img src="{{ asset($service['image']) }}" 
                                             alt="{{ $service['name'] ?? 'Service ' . ($index + 1) }}" 
                                             class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                                    </div>
                                @else
                                    <div class="flex justify-center mb-4">
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Service Details -->
                                <div class="text-center">
                                    <h4 class="font-semibold text-gray-900 mb-2">
                                        {{ $service['name'] ?? 'Service ' . ($index + 1) }}
                                    </h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">
                                        {{ $service['description'] ?? 'Tidak ada deskripsi' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Metadata -->
                <div class="border-t pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Dibuat pada:</span> 
                            {{ $gym->created_at ? $gym->created_at->format('d M Y, H:i') : 'Tidak tersedia' }}
                        </div>
                        <div>
                            <span class="font-medium">Terakhir diupdate:</span> 
                            {{ $gym->updated_at ? $gym->updated_at->format('d M Y, H:i') : 'Tidak tersedia' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between items-center">
            <div class="flex space-x-2">
                <form action="{{ route('admin.gyms.toggle-status', $gym->id_gym) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="bg-{{ $gym->is_open ? 'red' : 'green' }}-500 hover:bg-{{ $gym->is_open ? 'red' : 'green' }}-700 text-white font-bold py-2 px-4 rounded">
                        {{ $gym->is_open ? 'Tutup Gym' : 'Buka Gym' }}
                    </button>
                </form>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('admin.gyms.edit', $gym->id_gym) }}" 
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit Gym
                </a>
                <form action="{{ route('admin.gyms.destroy', $gym->id_gym) }}" method="POST" class="inline" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus gym ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Hapus Gym
                    </button>
                </form>
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
