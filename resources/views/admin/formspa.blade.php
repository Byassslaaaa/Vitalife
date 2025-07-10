@extends('layouts.admin')

@section('judul-halaman', 'Form SPA')

@section('content')
    <div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Input Data Spa Lengkap</h2>
        
        <form action="{{ route('spa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Basic Spa Information -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">Informasi Dasar Spa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Spa *</label>
                        <input type="text" name="nama" id="nama" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="noHP" class="block text-sm font-medium text-gray-700">Nomor HP *</label>
                        <input type="tel" name="noHP" id="noHP" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat *</label>
                        <textarea name="alamat" id="alamat" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="maps" class="block text-sm font-medium text-gray-700">Link Embed Maps</label>
                        <input type="url" name="maps" id="maps"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="https://www.google.com/maps/embed?...">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_open" value="1" checked
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Spa sedang buka</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Opening Hours -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">Jam Operasional</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <div>
                            <label for="waktuBuka_{{ strtolower($hari) }}" class="block text-sm font-medium text-gray-700">{{ $hari }}</label>
                            <input type="text" name="waktuBuka[{{ $hari }}]" id="waktuBuka_{{ strtolower($hari) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="09:00-21:00 atau Tutup">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Main Services -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">Layanan Utama</h3>
                <div id="services-container">
                    <div class="service-item grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <input type="text" name="services[0][name]" placeholder="Nama Layanan"
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <textarea name="services[0][description]" placeholder="Deskripsi Layanan" rows="2"
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <input type="file" name="services[0][image]" accept="image/*"
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <button type="button" onclick="addService()" 
                    class="mt-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Tambah Layanan
                </button>
            </div>

            <!-- Main Image -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">Gambar Utama Spa</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img id="main-image-preview" src="/placeholder.svg?height=100&width=100" 
                            alt="Preview" class="w-24 h-24 object-cover rounded-lg border-2 border-gray-300">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="image" id="main-image" accept="image/*" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            onchange="previewMainImage(event)">
                        <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Spa Details Section -->
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                <h3 class="text-xl font-semibold mb-4 text-blue-800">Detail Spa (Opsional)</h3>
                
                <!-- Contact Person -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="contact_person_name" class="block text-sm font-medium text-gray-700">Nama Contact Person</label>
                        <input type="text" name="spa_detail[contact_person_name]" id="contact_person_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="contact_person_phone" class="block text-sm font-medium text-gray-700">Telepon Contact Person</label>
                        <input type="tel" name="spa_detail[contact_person_phone]" id="contact_person_phone"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- About Spa -->
                <div class="mb-6">
                    <label for="about_spa" class="block text-sm font-medium text-gray-700">Tentang Spa</label>
                    <textarea name="spa_detail[about_spa]" id="about_spa" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Ceritakan tentang spa Anda..."></textarea>
                </div>

                <!-- Gallery Images -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Galeri Foto</label>
                    <input type="file" name="spa_detail[gallery_images][]" multiple accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-sm text-gray-500">Pilih beberapa foto untuk galeri spa</p>
                </div>

                <!-- Facilities -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas</label>
                    <div id="facilities-container">
                        <input type="text" name="spa_detail[facilities][]" placeholder="Contoh: AC, WiFi, Parking"
                            class="mb-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="button" onclick="addFacility()" 
                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                        Tambah Fasilitas
                    </button>
                </div>

                <!-- Treatment Rooms -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ruang Treatment</label>
                    <div id="rooms-container">
                        <div class="room-item grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <input type="text" name="spa_detail[treatment_rooms][0][name]" placeholder="Nama Ruangan"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <input type="text" name="spa_detail[treatment_rooms][0][capacity]" placeholder="Kapasitas"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <textarea name="spa_detail[treatment_rooms][0][description]" placeholder="Deskripsi" rows="2"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    <button type="button" onclick="addRoom()" 
                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                        Tambah Ruangan
                    </button>
                </div>

                <!-- Therapist Info -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Informasi Terapis</label>
                    <div id="therapist-container">
                        <div class="therapist-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <input type="text" name="spa_detail[therapist_info][0][name]" placeholder="Nama Terapis"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <input type="text" name="spa_detail[therapist_info][0][specialization]" placeholder="Spesialisasi"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <input type="text" name="spa_detail[therapist_info][0][experience]" placeholder="Pengalaman"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <input type="file" name="spa_detail[therapist_info][0][photo]" accept="image/*"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    <button type="button" onclick="addTherapist()" 
                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                        Tambah Terapis
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="window.history.back()" 
                    class="px-6 py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                    Batal
                </button>
                <button type="submit" 
                    class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200 font-semibold">
                    Simpan Data Spa
                </button>
            </div>
        </form>
    </div>

    <script>
        let serviceCount = 1;
        let roomCount = 1;
        let therapistCount = 1;

        function previewMainImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('main-image-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function addService() {
            const container = document.getElementById('services-container');
            const serviceItem = document.createElement('div');
            serviceItem.className = 'service-item grid grid-cols-1 md:grid-cols-3 gap-4 mb-4';
            serviceItem.innerHTML = `
                <input type="text" name="services[${serviceCount}][name]" placeholder="Nama Layanan"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <textarea name="services[${serviceCount}][description]" placeholder="Deskripsi Layanan" rows="2"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <div class="flex space-x-2">
                    <input type="file" name="services[${serviceCount}][image]" accept="image/*"
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="button" onclick="this.parentElement.parentElement.remove()" 
                        class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">×</button>
                </div>
            `;
            container.appendChild(serviceItem);
            serviceCount++;
        }

        function addFacility() {
            const container = document.getElementById('facilities-container');
            const facilityItem = document.createElement('div');
            facilityItem.className = 'flex space-x-2 mb-2';
            facilityItem.innerHTML = `
                <input type="text" name="spa_detail[facilities][]" placeholder="Nama Fasilitas"
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="button" onclick="this.parentElement.remove()" 
                    class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">×</button>
            `;
            container.appendChild(facilityItem);
        }

        function addRoom() {
            const container = document.getElementById('rooms-container');
            const roomItem = document.createElement('div');
            roomItem.className = 'room-item grid grid-cols-1 md:grid-cols-3 gap-4 mb-4';
            roomItem.innerHTML = `
                <input type="text" name="spa_detail[treatment_rooms][${roomCount}][name]" placeholder="Nama Ruangan"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <input type="text" name="spa_detail[treatment_rooms][${roomCount}][capacity]" placeholder="Kapasitas"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div class="flex space-x-2">
                    <textarea name="spa_detail[treatment_rooms][${roomCount}][description]" placeholder="Deskripsi" rows="2"
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" 
                        class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">×</button>
                </div>
            `;
            container.appendChild(roomItem);
            roomCount++;
        }

        function addTherapist() {
            const container = document.getElementById('therapist-container');
            const therapistItem = document.createElement('div');
            therapistItem.className = 'therapist-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4';
            therapistItem.innerHTML = `
                <input type="text" name="spa_detail[therapist_info][${therapistCount}][name]" placeholder="Nama Terapis"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <input type="text" name="spa_detail[therapist_info][${therapistCount}][specialization]" placeholder="Spesialisasi"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <input type="text" name="spa_detail[therapist_info][${therapistCount}][experience]" placeholder="Pengalaman"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div class="flex space-x-2">
                    <input type="file" name="spa_detail[therapist_info][${therapistCount}][photo]" accept="image/*"
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="button" onclick="this.parentElement.parentElement.remove()" 
                        class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">×</button>
                </div>
            `;
            container.appendChild(therapistItem);
            therapistCount++;
        }
    </script>
@endsection