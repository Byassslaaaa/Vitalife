@extends('layouts.admin')

@section('judul-halaman', ($isEdit ? 'Edit ' : 'Tambah ') . ucfirst($modelName))

@section('content')
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $isEdit ? 'Edit' : 'Tambah' }} {{ ucfirst($modelName) }}</h1>
                <p class="text-gray-600 text-sm mt-1">{{ $isEdit ? 'Perbarui' : 'Tambahkan' }} data {{ $modelName }} baru
                </p>
            </div>
            <a href="{{ route($routePrefix . '.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Terdapat kesalahan!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Form -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" id="mainForm">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="p-6">
                    @yield('form-content')
                </div>

                <!-- Form Actions -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route($routePrefix . '.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded transition-colors text-center">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded transition-colors">
                            {{ $isEdit ? 'Perbarui' : 'Simpan' }} {{ ucfirst($modelName) }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @yield('additional-scripts')
@endsection

@section('additional-scripts')
    <script>
        // Basic form validation
        document.getElementById('mainForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi!');
            }
        });

        // Image preview functionality
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Initialize image preview for all file inputs
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const previewId = this.getAttribute('data-preview');
                    if (previewId) {
                        previewImage(this, previewId);
                    }
                });
            });
        });
    </script>
@endsection
