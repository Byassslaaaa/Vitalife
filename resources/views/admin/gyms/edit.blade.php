@extends('layouts.admin')

@section('judul-halaman', 'Edit Gym')

@section('content')
    <div class="max-w-5xl mx-auto p-4 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Edit Data Gym: {{ $gym->nama }}</h2>
            <a href="{{ route('admin.gyms.show', $gym->id_gym) }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali ke Detail
            </a>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.gyms.update', $gym->id_gym) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Gym</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $gym->nama) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            @error('nama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_open" class="block text-sm font-medium text-gray-700">Status Operasional</label>
                            <select name="is_open" id="is_open" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                <option value="1" {{ old('is_open', $gym->is_open) == '1' ? 'selected' : '' }}>Buka</option>
                                <option value="0" {{ old('is_open', $gym->is_open) == '0' ? 'selected' : '' }}>Tutup</option>
                            </select>
                            @error('is_open')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>{{ old('alamat', $gym->alamat) }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Services (3 Services with Images) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Services Gym (3 Services Utama dengan Foto)</h3>
                    
                    @for($i = 0; $i < 3; $i++)
                        @php
                            $service = isset($gym->services[$i]) ? $gym->services[$i] : null;
                        @endphp
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white">
                            <h4 class="font-medium text-gray-800 mb-3">Service {{ $i + 1 }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="services_{{ $i }}_name" class="block text-sm font-medium text-gray-700">Nama Service</label>
                                    <input type="text" name="services[{{ $i }}][name]" id="services_{{ $i }}_name" 
                                           value="{{ old('services.'.$i.'.name', $service['name'] ?? '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           placeholder="Contoh: Personal Training" required>
                                    @error('services.'.$i.'.name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="services_{{ $i }}_image" class="block text-sm font-medium text-gray-700">Foto Service (Circular)</label>
                                    <input type="file" name="services[{{ $i }}][image]" id="services_{{ $i }}_image" 
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                           onchange="previewServiceImage(event, {{ $i }})">
                                    @error('services.'.$i.'.image')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <div id="serviceImagePreview{{ $i }}" class="mt-2">
                                        @if(isset($service['image']) && $service['image'])
                                            <img src="{{ asset($service['image']) }}" alt="Current image" class="w-16 h-16 object-cover rounded-full border-2 border-gray-300">
                                        @else
                                            <img src="/placeholder.svg" alt="Preview" class="w-16 h-16 object-cover rounded-full bg-gray-200 hidden border-2 border-gray-300">
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Foto akan ditampilkan dalam bentuk lingkaran. Kosongkan jika tidak ingin mengubah foto.</p>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="services_{{ $i }}_description" class="block text-sm font-medium text-gray-700">Deskripsi Service</label>
                                    <textarea name="services[{{ $i }}][description]" id="services_{{ $i }}_description" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                              placeholder="Jelaskan detail service ini..." required>{{ old('services.'.$i.'.description', $service['description'] ?? '') }}</textarea>
                                    @error('services.'.$i.'.description')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Main Gym Image -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Foto Utama Gym</h3>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Gambar Gym</label>
                        <div class="mt-1 flex items-center">
                            <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                @if($gym->image)
                                    <img src="{{ asset($gym->image) }}" alt="Current gym image" class="h-full w-full object-cover">
                                @else
                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </span>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                onchange="previewImage(event)">
                        </div>
                        <div id="imagePreview" class="mt-2">
                            @if($gym->image)
                                <img src="{{ asset($gym->image) }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg">
                            @else
                                <img src="/placeholder.svg" alt="Preview" class="w-32 h-32 object-cover rounded-lg bg-gray-200 hidden">
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Upload foto interior atau eksterior gym (JPG, PNG, GIF, max 2MB). Kosongkan jika tidak ingin mengubah foto.</p>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t pt-6">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.gyms.show', $gym->id_gym) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Update Data Gym
                        </button>
                    </div>
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

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['nama', 'alamat', 'is_open'];
            let isValid = true;
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            // Validate services
            for (let i = 0; i < 3; i++) {
                const nameInput = document.getElementById(`services_${i}_name`);
                const descInput = document.getElementById(`services_${i}_description`);
                
                if (!nameInput.value.trim() || !descInput.value.trim()) {
                    isValid = false;
                    if (!nameInput.value.trim()) nameInput.classList.add('border-red-500');
                    if (!descInput.value.trim()) descInput.classList.add('border-red-500');
                } else {
                    nameInput.classList.remove('border-red-500');
                    descInput.classList.remove('border-red-500');
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi');
            }
        });

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
    </script>

    <style>
        .border-red-500 {
            border-color: #ef4444 !important;
        }
    </style>
@endsection
