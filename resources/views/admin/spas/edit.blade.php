@extends('layouts.admin')

@section('judul-halaman', 'Edit Spa')

@section('content')
    <div class="max-w-5xl mx-auto p-4 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Edit Spa</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.spas.update', $spa->id_spa) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Spa</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $spa->nama) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                        </div>

                        <div>
                            <label for="is_open" class="block text-sm font-medium text-gray-700">Status Operasional</label>
                            <select name="is_open" id="is_open" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                <option value="1" {{ old('is_open', $spa->is_open) == '1' ? 'selected' : '' }}>Buka</option>
                                <option value="0" {{ old('is_open', $spa->is_open) == '0' ? 'selected' : '' }}>Tutup</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>{{ old('alamat', $spa->alamat) }}</textarea>
                    </div>

                    <div class="mt-4">
                        <label for="noHP" class="block text-sm font-medium text-gray-700">No. HP</label>
                        <input type="text" name="noHP" id="noHP" value="{{ old('noHP', $spa->noHP) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>
                    </div>
                </div>

                <!-- Opening Hours -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Waktu Buka</h3>
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <div class="flex items-center space-x-2 mt-2">
                            <label for="waktuBuka_{{ strtolower($hari) }}" class="w-20">{{ $hari }}</label>
                            <input type="text" name="waktuBuka[{{ $hari }}]"
                                id="waktuBuka_{{ strtolower($hari) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="contoh: 09:00-20:00"
                                value="{{ old('waktuBuka.'.$hari, $spa->waktuBuka[$hari] ?? '') }}"
                                required>
                        </div>
                    @endforeach
                    <small class="text-gray-500 mt-2 block">Format: HH:MM-HH:MM, contoh: 09:00-20:00</small>
                </div>

                <!-- Services -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Services Utama (3 Services)</h3>
                    
                    @for($i = 0; $i < 3; $i++)
                        @php
                            $service = isset($spa->services[$i]) ? $spa->services[$i] : null;
                        @endphp
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white">
                            <h4 class="font-medium text-gray-800 mb-3">Service {{ $i + 1 }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="services_{{ $i }}_name" class="block text-sm font-medium text-gray-700">Nama Service</label>
                                    <input type="text" name="services[{{ $i }}][name]" id="services_{{ $i }}_name" 
                                           value="{{ old('services.'.$i.'.name', $service['name'] ?? '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           placeholder="Contoh: Massage Therapy" required>
                                </div>
                                
                                <div>
                                    <label for="services_{{ $i }}_image" class="block text-sm font-medium text-gray-700">Foto Service</label>
                                    @if(isset($service['image']))
                                        <div class="mb-2">
                                            <img src="{{ asset($service['image']) }}" alt="Service Image" 
                                                 class="w-16 h-16 object-cover rounded-full">
                                        </div>
                                    @endif
                                    <input type="file" name="services[{{ $i }}][image]" id="services_{{ $i }}_image" 
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                           onchange="previewServiceImage(event, {{ $i }})">
                                    <div id="serviceImagePreview{{ $i }}" class="mt-2">
                                        <img src="/placeholder.svg" alt="Preview" class="w-16 h-16 object-cover rounded-full bg-gray-200 hidden border-2 border-gray-300">
                                    </div>
                                    <small class="text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar</small>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="services_{{ $i }}_description" class="block text-sm font-medium text-gray-700">Deskripsi Service</label>
                                    <textarea name="services[{{ $i }}][description]" id="services_{{ $i }}_description" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                              placeholder="Jelaskan detail service ini..." required>{{ old('services.'.$i.'.description', $service['description'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Maps -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Lokasi</h3>
                    <div>
                        <label for="maps" class="block text-sm font-medium text-gray-700">Link Google Maps</label>
                        <input type="text" name="maps" id="maps" value="{{ old('maps', $spa->maps) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Masukkan URL Google Maps atau alamat lokasi">
                        <small class="text-gray-500 mt-1 block">
                            Anda dapat memasukkan URL Google Maps atau embed code.
                        </small>
                        
                        @if($spa->maps)
                        <div class="mt-4 border rounded p-2">
                            <p class="text-sm font-medium mb-2">Preview Maps:</p>
                            <div class="w-full h-48 rounded overflow-hidden">
                                {!! $spa->maps !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Main Image -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Foto Utama Spa</h3>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Gambar Spa</label>
                        @if($spa->image)
                            <div class="mb-2">
                                <img src="{{ asset($spa->image) }}" alt="Spa Image" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        @endif
                        <input type="file" name="image" id="image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            onchange="previewImage(event)">
                        <div id="imagePreview" class="mt-2">
                            <img src="/placeholder.svg" alt="Preview" class="w-32 h-32 object-cover rounded-lg bg-gray-200 hidden">
                        </div>
                        <small class="text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Update Spa</button>
                    <a href="{{ route('admin.spas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('#imagePreview img');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewServiceImage(event, index) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector(`#serviceImagePreview${index} img`);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
