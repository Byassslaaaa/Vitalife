@extends('layouts.admin')

@section('judul-halaman', 'Detail Yoga')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detail Yoga</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.yogas.edit', $yoga->id_yoga) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('admin.yogas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 mb-4 md:mb-0">
                        @if($yoga->image)
                            <img src="{{ asset($yoga->image) }}" alt="{{ $yoga->nama }}" class="w-full h-auto rounded-lg">
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                                <span class="text-gray-500">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="md:w-2/3 md:pl-6">
                        <h2 class="text-xl font-bold mb-4">{{ $yoga->nama }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 font-semibold">Harga:</p>
                                <p>Rp {{ number_format($yoga->harga, 0, ',', '.') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-600 font-semibold">No. HP:</p>
                                <p>{{ $yoga->noHP }}</p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <p class="text-gray-600 font-semibold">Alamat:</p>
                                <p>{{ $yoga->alamat }}</p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <p class="text-gray-600 font-semibold">Waktu Buka:</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                        <div class="bg-gray-100 p-2 rounded">
                                            <p class="font-medium">{{ $hari }}:</p>
                                            <p>{{ isset($yoga->waktuBuka[$hari]) ? $yoga->waktuBuka[$hari] : 'Tutup' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <p class="text-gray-600 font-semibold">Maps:</p>
                            @if($yoga->maps)

            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Lokasi</h3>
                <div class="w-full h-96 rounded-lg overflow-hidden">
                    {!! $yoga->maps !!}
                </div>
            </div>
        @endif
    </div>
@endsection
