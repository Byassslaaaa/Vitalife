@extends('layouts.admin')

@section('judul-halaman', 'Tambah Yoga')

@section('content')
    <div class="max-w-5xl mx-auto p-4 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Tambah Data Yoga Baru</h2>
            <a href="{{ route('admin.yogas.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali ke Daftar
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

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.yogas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Yoga *</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            @error('nama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Opsional)</label>
                            <input type="number" name="harga" id="harga" value="{{ old('harga') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Contoh: 50000" min="0" step="1000">
                            @error('harga')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="noHP" class="block text-sm font-medium text-gray-700">No. HP</label>
                            <input type="text" name="noHP" id="noHP" value="{{ old('noHP') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Contoh: 08123456789">
                            @error('noHP')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_open" class="block text-sm font-medium text-gray-700">Status Operasional</label>
                            <select name="is_open" id="is_open"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="1" {{ old('is_open', '1') == '1' ? 'selected' : '' }}>Buka</option>
                                <option value="0" {{ old('is_open') == '0' ? 'selected' : '' }}>Tutup</option>
                            </select>
                            @error('is_open')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="class_type_image" class="block text-sm font-medium text-gray-700">Foto Kelas Yoga</label>
                        <input type="file" name="class_type_image" id="class_type_image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                            onchange="previewClassTypeImage(event)">
                        @error('class_type_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div id="classTypeImagePreview" class="mt-2">
                            <img src="/placeholder.svg" alt="Preview" class="w-32 h-32 object-cover rounded-lg bg-gray-200 hidden border-2 border-gray-300">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Upload foto kelas yoga (JPG, PNG, max 2MB)</p>
                    </div>
                </div>

                    <div class="mt-4">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat *</label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Waktu Buka -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Waktu Buka</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                            <div class="flex items-center space-x-2">
                                <label for="waktuBuka_{{ strtolower($hari) }}" class="w-20 text-sm font-medium text-gray-700">{{ $hari }}</label>
                                <input type="text" name="waktuBuka[{{ $hari }}]" id="waktuBuka_{{ strtolower($hari) }}"
                                    value="{{ old('waktuBuka.'.$hari) }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="09:00-20:00">
                            </div>
                        @endforeach
                    </div>
                    @error('waktuBuka')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <small class="text-gray-500 mt-2 block">Format: HH:MM-HH:MM, contoh: 09:00-20:00</small>
                </div>

                <!-- Services (3 Services with Images) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Services Yoga (3 Services Utama dengan Foto)</h3>

                    @for($i = 0; $i < 3; $i++)
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white">
                            <h4 class="font-medium text-gray-800 mb-3">Service {{ $i + 1 }}</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="services_{{ $i }}_name" class="block text-sm font-medium text-gray-700">Nama Service *</label>
                                    <input type="text" name="services[{{ $i }}][name]" id="services_{{ $i }}_name"
                                           value="{{ old('services.'.$i.'.name') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           placeholder="Contoh: Hatha Yoga" required>
                                    @error('services.'.$i.'.name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="services_{{ $i }}_image" class="block text-sm font-medium text-gray-700">Foto Service (Circular)</label>
                                    <input type="file" name="services[{{ $i }}][image]" id="services_{{ $i }}_image"
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                                           onchange="previewServiceImage(event, {{ $i }})">
                                    @error('services.'.$i.'.image')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <div id="serviceImagePreview{{ $i }}" class="mt-2">
                                        <img src="/placeholder.svg" alt="Preview" class="w-16 h-16 object-cover rounded-full bg-gray-200 hidden border-2 border-gray-300">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Foto akan ditampilkan dalam bentuk lingkaran</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="services_{{ $i }}_description" class="block text-sm font-medium text-gray-700">Deskripsi Service *</label>
                                    <textarea name="services[{{ $i }}][description]" id="services_{{ $i }}_description" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                              placeholder="Jelaskan detail service ini..." required>{{ old('services.'.$i.'.description') }}</textarea>
                                    @error('services.'.$i.'.description')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="services_{{ $i }}_price" class="block text-sm font-medium text-gray-700">Harga (Opsional)</label>
                                    <input type="number" name="services[{{ $i }}][price]" id="services_{{ $i }}_price"
                                           value="{{ old('services.'.$i.'.price') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           placeholder="Contoh: 75000" min="0" step="1000">
                                    @error('services.'.$i.'.price')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1">Masukkan harga dalam Rupiah (tanpa titik atau koma)</p>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Maps -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Lokasi</h3>
                    <div>
                        <label for="maps" class="block text-sm font-medium text-gray-700">Link Embed Maps</label>
                        <textarea name="maps" id="maps" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Tempel link embed Google Maps Anda di sini">{{ old('maps') }}</textarea>
                        @error('maps')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Main Yoga Image -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Foto Utama Yoga</h3>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Gambar Yoga</label>
                        <div class="mt-1 flex items-center">
                            <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                onchange="previewImage(event)">
                        </div>
                        <div id="imagePreview" class="mt-2">
                            <img src="/placeholder.svg" alt="Preview" class="w-32 h-32 object-cover rounded-lg bg-gray-200 hidden">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Upload foto studio atau sesi yoga (JPG, PNG, GIF, max 2MB)</p>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t pt-6">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.yogas.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-800 focus:outline-none focus:border-purple-800 focus:ring focus:ring-purple-300 disabled:opacity-25 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Data Yoga
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
            const requiredFields = ['nama', 'alamat'];
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
                alert('Harap lengkapi semua field yang wajib diisi (nama, alamat, dan 3 services dengan nama & deskripsi)');
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
