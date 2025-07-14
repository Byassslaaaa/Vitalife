@extends('layouts.admin')

@section('judul-halaman', 'Edit Yoga Service')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Edit Yoga Service</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.yoga-services.show', $service->id) }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Lihat Detail
                </a>
                <a href="{{ route('admin.yoga-services.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Kembali
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Terjadi kesalahan!</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form action="{{ route('admin.yoga-services.update', $service->id) }}" method="POST"
                enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Yoga Selection -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Yoga *</label>
                        <select name="yoga_id" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Yoga</option>
                            @foreach ($yogas as $yoga)
                                <option value="{{ $yoga->id_yoga }}"
                                    {{ old('yoga_id', $service->yoga_id) == $yoga->id_yoga ? 'selected' : '' }}>
                                    {{ $yoga->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Service Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Service *</label>
                        <input type="text" name="name" value="{{ old('name', $service->name) }}" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Hatha Yoga Beginner Class">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                        <select name="category" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            <option value="hatha" {{ old('category', $service->category) == 'hatha' ? 'selected' : '' }}>
                                Hatha Yoga</option>
                            <option value="vinyasa"
                                {{ old('category', $service->category) == 'vinyasa' ? 'selected' : '' }}>Vinyasa Yoga
                            </option>
                            <option value="ashtanga"
                                {{ old('category', $service->category) == 'ashtanga' ? 'selected' : '' }}>Ashtanga Yoga
                            </option>
                            <option value="yin" {{ old('category', $service->category) == 'yin' ? 'selected' : '' }}>Yin
                                Yoga</option>
                            <option value="restorative"
                                {{ old('category', $service->category) == 'restorative' ? 'selected' : '' }}>Restorative
                                Yoga</option>
                            <option value="hot" {{ old('category', $service->category) == 'hot' ? 'selected' : '' }}>Hot
                                Yoga</option>
                            <option value="prenatal"
                                {{ old('category', $service->category) == 'prenatal' ? 'selected' : '' }}>Prenatal Yoga
                            </option>
                            <option value="meditation"
                                {{ old('category', $service->category) == 'meditation' ? 'selected' : '' }}>Meditation
                            </option>
                            <option value="breathing"
                                {{ old('category', $service->category) == 'breathing' ? 'selected' : '' }}>Breathing
                                Exercises</option>
                            <option value="other" {{ old('category', $service->category) == 'other' ? 'selected' : '' }}>
                                Lainnya</option>
                        </select>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp) *</label>
                        <input type="number" name="price" value="{{ old('price', $service->price) }}" required
                            min="0"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="150000">
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (menit)</label>
                        <input type="number" name="duration" value="{{ old('duration', $service->duration) }}"
                            min="0"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="60">
                    </div>

                    <!-- Difficulty Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Level Kesulitan</label>
                        <select name="difficulty_level"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Level</option>
                            <option value="beginner"
                                {{ old('difficulty_level', $service->difficulty_level) == 'beginner' ? 'selected' : '' }}>
                                Beginner</option>
                            <option value="intermediate"
                                {{ old('difficulty_level', $service->difficulty_level) == 'intermediate' ? 'selected' : '' }}>
                                Intermediate</option>
                            <option value="advanced"
                                {{ old('difficulty_level', $service->difficulty_level) == 'advanced' ? 'selected' : '' }}>
                                Advanced</option>
                            <option value="all_levels"
                                {{ old('difficulty_level', $service->difficulty_level) == 'all_levels' ? 'selected' : '' }}>
                                All Levels</option>
                        </select>
                    </div>

                    <!-- Max Participants -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Peserta</label>
                        <input type="number" name="max_participants"
                            value="{{ old('max_participants', $service->max_participants) }}" min="1"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="20">
                    </div>

                    <!-- Current Image Display -->
                    @if ($service->image)
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                            <img src="{{ asset($service->image) }}" alt="{{ $service->name }}"
                                class="w-32 h-32 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                        <textarea name="description" required rows="4"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Deskripsikan detail service yoga ini...">{{ old('description', $service->description) }}</textarea>
                    </div>

                    <!-- Benefits -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Manfaat</label>
                        <textarea name="benefits" rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Tuliskan manfaat dari service ini...">{{ old('benefits', $service->benefits) }}</textarea>
                    </div>

                    <!-- Requirements -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Persyaratan</label>
                        <textarea name="requirements" rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Tuliskan persyaratan untuk mengikuti service ini...">{{ old('requirements', $service->requirements) }}</textarea>
                    </div>

                    <!-- New Image -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $service->image ? 'Ganti Gambar Service' : 'Gambar Service' }}
                        </label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">
                            Format: JPG, PNG, JPEG. Maksimal 2MB.
                            {{ $service->image ? 'Kosongkan jika tidak ingin mengganti gambar.' : '' }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900">Service Aktif</label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.yoga-services.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded font-medium">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-medium">
                        Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
