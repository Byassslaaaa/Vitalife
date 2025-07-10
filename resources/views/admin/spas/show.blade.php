@extends('layouts.admin')

@section('judul-halaman', 'Detail Spa')

@section('content')
    <div class="max-w-5xl mx-auto p-4 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Detail Spa</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.spas.edit', $spa->id_spa) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('admin.spas.services.index', $spa->id_spa) }}" 
                   class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                    Kelola Services
                </a>
                <a href="{{ route('admin.spas.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                @if($spa->image)
                    <div class="mb-4">
                        <img src="{{ asset($spa->image) }}" alt="{{ $spa->nama }}" 
                             class="w-full h-auto object-cover rounded-lg shadow-md">
                    </div>
                @else
                    <div class="mb-4 bg-gray-200 h-64 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500">Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Nama Spa</h3>
                    <p class="mt-1 text-gray-600">{{ $spa->nama }}</p>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900">Status</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $spa->is_open ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $spa->is_open ? 'Buka' : 'Tutup' }}
                    </span>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900">Alamat</h3>
                    <p class="mt-1 text-gray-600">{{ $spa->alamat }}</p>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900">No. HP</h3>
                    <p class="mt-1 text-gray-600">{{ $spa->noHP }}</p>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900">Waktu Buka</h3>
                    <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @if($spa->waktuBuka && is_array($spa->waktuBuka))
                            @foreach($spa->waktuBuka as $hari => $waktu)
                                <div class="flex">
                                    <span class="font-medium w-24">{{ $hari }}:</span>
                                    <span class="text-gray-600">{{ $waktu }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">Waktu buka belum diatur</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        @if($spa->services && is_array($spa->services) && count($spa->services) > 0)
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Services Utama</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($spa->services as $service)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            @if(isset($service['image']))
                                <img src="{{ asset($service['image']) }}" alt="{{ $service['name'] }}" 
                                     class="w-16 h-16 object-cover rounded-full mx-auto mb-3">
                            @endif
                            <h4 class="font-medium text-center">{{ $service['name'] ?? 'Service' }}</h4>
                            <p class="text-sm text-gray-600 text-center mt-2">{{ $service['description'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Additional Services from SpaService model -->
        @if($spa->spaServices && $spa->spaServices->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Services Tambahan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($spa->spaServices as $service)
                        <div class="bg-white border border-gray-200 p-4 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium">{{ $service->name }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $service->description }}</p>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">{{ $service->duration }}</span>
                                <span class="font-medium text-pink-600">{{ $service->formatted_price }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($spa->maps)
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Lokasi</h3>
                <div class="w-full h-96 rounded-lg overflow-hidden">
                    {!! $spa->maps !!}
                </div>
            </div>
        @endif
    </div>
@endsection
