@extends('layouts.admin')

@section('judul-halaman', 'Edit Service')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center mb-6 gap-2">
            <h1 class="text-2xl font-bold">Edit Service untuk {{ $spa->nama }}</h1>
            <a href="{{ route('admin.spas.services.index', $spa->id_spa) }}" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-center transition">
                Kembali ke Services
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <form action="{{ route('admin.spas.services.update', [$spa->id_spa, $service->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Service</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 transition">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 transition">{{ old('description', $service->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category" id="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 transition">
                            <option value="">Pilih Kategori</option>
                            <option value="Massage" {{ old('category', $service->category) == 'Massage' ? 'selected' : '' }}>Massage</option>
                            <option value="Facial" {{ old('category', $service->category) == 'Facial' ? 'selected' : '' }}>Facial</option>
                            <option value="Body Treatment" {{ old('category', $service->category) == 'Body Treatment' ? 'selected' : '' }}>Body Treatment</option>
                            <option value="Aromatherapy" {{ old('category', $service->category) == 'Aromatherapy' ? 'selected' : '' }}>Aromatherapy</option>
                            <option value="Reflexology" {{ old('category', $service->category) == 'Reflexology' ? 'selected' : '' }}>Reflexology</option>
                        </select>
                    </div>
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Durasi</label>
                        <input type="text" name="duration" id="duration" value="{{ old('duration', $service->duration) }}"
                            placeholder="contoh: 60 menit"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 transition">
                    </div>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $service->price) }}" required min="0" step="1000"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 transition">
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Foto Service</label>
                    @if($service->image)
                        <div class="mb-2">
                            <img src="{{ asset($service->image) }}" alt="Service Image" 
                                 class="w-16 h-16 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 transition">
                    <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto</p>
                </div>

                <div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Aktif (service akan tersedia untuk booking)
                        </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-2">
                    <button type="submit" class="w-full sm:w-auto bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded transition">
                        Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
