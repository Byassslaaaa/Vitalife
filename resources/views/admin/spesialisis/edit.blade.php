@extends('layouts.admin')

@section('judul-halaman', 'Edit Spesialis')

@section('content')
    <div class="max-w-5xl mx-auto p-4 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold mb-4">Edit Spesialis</h2>
        <form action="{{ route('admin.spesialisis.update', $spesialis->id_spesialis) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Spesialis</label>
                    <input type="text" name="nama" id="nama" value="{{ $spesialis->nama }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required>
                </div>

                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" name="harga" id="harga" value="{{ $spesialis->harga }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required>
                </div>

                <div>
                    <label for="spesialisasi" class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                    <select name="spesialisasi" id="spesialisasi"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required>
                        <option value="">Pilih Spesialisasi</option>
                        <option value="Anatomy" {{ $spesialis->spesialisasi == 'Anatomy' ? 'selected' : '' }}>Anatomy</option>
                        <option value="Primary Care" {{ $spesialis->spesialisasi == 'Primary Care' ? 'selected' : '' }}>Primary Care</option>
                        <option value="Cardiology" {{ $spesialis->spesialisasi == 'Cardiology' ? 'selected' : '' }}>Cardiology</option>
                        <option value="Skin & Genitals" {{ $spesialis->spesialisasi == 'Skin & Genitals' ? 'selected' : '' }}>Skin & Genitals</option>
                        <option value="Human Senses" {{ $spesialis->spesialisasi == 'Human Senses' ? 'selected' : '' }}>Human Senses</option>
                        <option value="Piscologist" {{ $spesialis->spesialisasi == 'Piscologist' ? 'selected' : '' }}>Piscologist</option>
                        <option value="Physiotherapi" {{ $spesialis->spesialisasi == 'Physiotherapi' ? 'selected' : '' }}>Physiotherapi</option>
                        <option value="Pregnancy" {{ $spesialis->spesialisasi == 'Pregnancy' ? 'selected' : '' }}>Pregnancy</option>
                    </select>
                </div>

                <div>
                    <label for="tempatTugas" class="block text-sm font-medium text-gray-700">Tempat Tugas</label>
                    <input type="text" name="tempatTugas" id="tempatTugas" value="{{ $spesialis->tempatTugas }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required>{{ $spesialis->alamat }}</textarea>
                </div>

                <div>
                    <label for="noHP" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                    <input type="tel" name="noHP" id="noHP" value="{{ $spesialis->noHP }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <div class="mt-1 flex items-center">
                        <img src="{{ asset($spesialis->image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-full mr-4">
                        <input type="file" name="image" id="image" accept="image/*"
                            class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                        Update Data
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection