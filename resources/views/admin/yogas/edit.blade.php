@extends('layouts.admin')

@section('judul-halaman', 'Edit Yoga')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Edit Yoga</h1>
            <a href="{{ route('admin.yogas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <form action="{{ route('admin.yogas.update', $yoga->id_yoga) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $yoga->nama) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                        <input type="number" name="harga" id="harga" value="{{ old('harga', $yoga->harga) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('harga')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('alamat', $yoga->alamat) }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="noHP" class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                        <input type="text" name="noHP" id="noHP" value="{{ old('noHP', $yoga->noHP) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('noHP')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <label for="maps" class="block text-sm font-medium text-gray-700 mb-2">Link Embed Maps</label>
                        <textarea name="maps" id="maps" rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('maps', $yoga->maps) }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">
                            Masukkan URL embed Google Maps. Contoh: https://www.google.com/maps/embed?pb=... <br>
                            Atau URL Google Maps biasa, sistem akan mencoba mengkonversinya.
                        </p>
                        @error('maps')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        
                        @if($yoga->maps)
                            <div class="mt-4 border rounded-lg overflow-hidden">
                                <p class="text-sm font-medium text-gray-700 p-2 bg-gray-50">Preview Maps:</p>
                                <div id="edit-map-preview" class="w-full h-[200px] bg-gray-100 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <p class="text-gray-500 mb-3">Memuat peta...</p>
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-blue-50 border-t border-blue-100 text-sm text-blue-700">
                                    <p class="font-medium">Panduan URL Maps:</p>
                                    <ol class="list-decimal list-inside mt-1 ml-2">
                                        <li>Buka Google Maps dan cari lokasi</li>
                                        <li>Klik tombol "Bagikan"</li>
                                        <li>Pilih tab "Sematkan peta"</li>
                                        <li>Salin URL dari kode iframe (src="...")</li>
                                        <li>Atau salin seluruh kode iframe</li>
                                    </ol>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const mapsInput = document.getElementById('maps');
                                    const previewContainer = document.getElementById('edit-map-preview');
                                    
                                    function updateMapPreview(url) {
                                        if (!previewContainer) return;
                                        
                                        // Clear previous content
                                        previewContainer.innerHTML = '';
                                        
                                        try {
                                            // Create new iframe
                                            const iframe = document.createElement('iframe');
                                            iframe.src = url;
                                            iframe.width = "100%";
                                            iframe.height = "100%";
                                            iframe.style.border = "none";
                                            iframe.allowFullscreen = true;
                                            iframe.loading = "lazy";
                                            iframe.referrerPolicy = "no-referrer-when-downgrade";
                                            
                                            // Handle errors
                                            iframe.onerror = function() {
                                                showMapError();
                                            };
                                            
                                            // Set a timeout to check if the iframe loaded properly
                                            let iframeLoaded = false;
                                            iframe.onload = function() {
                                                iframeLoaded = true;
                                                // Additional check for common error pages
                                                try {
                                                    if (this.contentDocument && 
                                                        (this.contentDocument.title.includes("404") || 
                                                         this.contentDocument.title.includes("Error") ||
                                                         this.contentDocument.body.innerHTML.includes("404") ||
                                                         this.contentDocument.body.innerHTML.includes("not found"))) {
                                                        showMapError();
                                                    }
                                                } catch (e) {
                                                    // If we can't access contentDocument, it might be due to CORS
                                                    // This is actually a good sign as it means the iframe loaded from a different domain
                                                }
                                            };
                                            
                                            // Add iframe to container
                                            previewContainer.appendChild(iframe);
                                            
                                            // Set a timeout to check if the iframe loaded
                                            setTimeout(function() {
                                                if (!iframeLoaded) {
                                                    showMapError();  {
                                                if (!iframeLoaded) {
                                                    showMapError();
                                                }
                                            }, 5000); // 5 second timeout
                                            
                                        } catch (e) {
                                            console.error("Error updating map preview:", e);
                                            showMapError();
                                        }
                                    }
                                    
                                    function showMapError() {
                                        previewContainer.innerHTML = `
                                            <div class="flex flex-col items-center justify-center h-full p-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <p class="text-red-500 text-sm mb-3">Tidak dapat memuat peta dengan URL ini</p>
                                                <p class="text-gray-500 text-xs">Pastikan URL adalah embed URL Google Maps yang valid</p>
                                            </div>
                                        `;
                                    }
                                    
                                    // Initialize with current value
                                    if (mapsInput && mapsInput.value) {
                                        updateMapPreview(mapsInput.value);
                                    }
                                    
                                    // Update on change
                                    if (mapsInput) {
                                        mapsInput.addEventListener('input', function() {
                                            updateMapPreview(this.value);
                                        });
                                    }
                                });
                            </script>
                        @endif
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Buka</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <div class="flex items-center space-x-2">
                                    <label for="waktuBuka_{{ strtolower($hari) }}" class="w-20 text-sm font-medium text-gray-700">{{ $hari }}</label>
                                    <input type="text" name="waktuBuka[{{ $hari }}]" id="waktuBuka_{{ strtolower($hari) }}"
                                        value="{{ old('waktuBuka.'.$hari, isset($yoga->waktuBuka[$hari]) ? $yoga->waktuBuka[$hari] : '') }}"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="contoh: 09.00–20.00">
                                </div>
                            @endforeach
                        </div>
                        @error('waktuBuka')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                        <div class="flex items-start space-x-4">
                            @if($yoga->image)
                                <div class="w-32 h-32 overflow-hidden rounded-lg">
                                    <img src="{{ asset($yoga->image) }}" alt="{{ $yoga->nama }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="image" id="image" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar</p>
                                @error('image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
