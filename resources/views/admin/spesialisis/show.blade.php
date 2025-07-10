@extends('layouts.admin')

@section('judul-halaman', 'Detail Spesialis')

@section('content')
    <div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Detail Spesialis</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.spesialisis.edit', $spesialis->id_spesialis) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('admin.spesialisis.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <div class="bg-gray-100 p-4 rounded-lg">
                    <img src="{{ asset($spesialis->image) }}" alt="{{ $spesialis->nama }}" 
                         class="w-full h-auto rounded-lg object-cover">
                </div>
            </div>
            
            <div class="md:col-span-2">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <table class="w-full">
                        <tr>
                            <td class="py-2 font-semibold">Nama:</td>
                            <td>{{ $spesialis->nama }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">Spesialisasi:</td>
                            <td>{{ $spesialis->spesialisasi }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">Harga:</td>
                            <td>Rp {{ number_format($spesialis->harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">Tempat Tugas:</td>
                            <td>{{ $spesialis->tempatTugas }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">Alamat:</td>
                            <td>{{ $spesialis->alamat }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">No. HP:</td>
                            <td>{{ $spesialis->noHP }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection