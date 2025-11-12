@extends('layouts.admin')

@section('judul-halaman', 'Tambah Admin')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Tambah Admin Baru</h2>
                    <a href="{{ route('admin.admins.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            placeholder="Contoh: 081234567890"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('password') border-red-500 @enderror"
                            required>
                        <p class="text-gray-500 text-xs mt-1">Minimal 8 karakter</p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>
                    </div>

                    <!-- Level Admin -->
                    <div class="mb-4">
                        <label for="role_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Level Admin <span class="text-red-500">*</span>
                        </label>
                        <select name="role_level" id="role_level"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('role_level') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Level Admin</option>
                            <option value="admin" {{ old('role_level') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ old('role_level') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        <p class="text-gray-500 text-xs mt-1">Super Admin memiliki akses penuh ke semua fitur</p>
                        @error('role_level')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('status') border-red-500 @enderror"
                            required>
                            <option value="active" {{ old('status') === 'active' || old('status') === null ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permissions Section -->
                    <div class="mb-6 p-4 border border-gray-300 rounded-lg" id="permissions-section">
                        <h3 class="text-lg font-semibold mb-3">Hak Akses (Permissions)</h3>
                        <p class="text-sm text-gray-600 mb-4">Pilih fitur yang dapat dikelola oleh admin ini. Super Admin memiliki akses ke semua fitur.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($availablePermissions as $key => $label)
                                <div class="flex items-center">
                                    <input type="checkbox"
                                        name="permissions[{{ $key }}]"
                                        id="permission_{{ $key }}"
                                        value="1"
                                        {{ old('permissions.' . $key) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="permission_{{ $key }}" class="ml-2 text-sm text-gray-700">
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                            placeholder="Catatan tambahan tentang admin ini..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Opsional: Informasi tambahan, tugas khusus, dll.</p>
                    </div>

                    <!-- Profile Photo -->
                    <div class="mb-4">
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Profil
                        </label>
                        <input type="file" name="profile_photo" id="profile_photo"
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="mt-1 block w-full @error('profile_photo') border-red-500 @enderror"
                            onchange="previewImage(event)">
                        <p class="text-gray-500 text-xs mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                        @error('profile_photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Preview Image -->
                        <div id="imagePreview" class="mt-3 hidden">
                            <img id="preview" src="" alt="Preview" class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-6 gap-3">
                        <a href="{{ route('admin.admins.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('imagePreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            imagePreview.classList.remove('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// Toggle permissions section based on role level
document.addEventListener('DOMContentLoaded', function() {
    const roleLevelSelect = document.getElementById('role_level');
    const permissionsSection = document.getElementById('permissions-section');

    roleLevelSelect.addEventListener('change', function() {
        if (this.value === 'super_admin') {
            permissionsSection.classList.add('opacity-50', 'pointer-events-none');
            permissionsSection.querySelector('p').textContent = 'Super Admin memiliki akses penuh ke semua fitur secara otomatis.';
        } else {
            permissionsSection.classList.remove('opacity-50', 'pointer-events-none');
            permissionsSection.querySelector('p').textContent = 'Pilih fitur yang dapat dikelola oleh admin ini. Super Admin memiliki akses ke semua fitur.';
        }
    });
});
</script>
@endsection
