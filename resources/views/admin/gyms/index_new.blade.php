@extends('admin.layouts.crud-index')

@section('main-content')
    @if ($gyms->count() > 0)
        <!-- Gym Cards Grid -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Gym</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($gyms as $gym)
                        <div
                            class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <!-- Gym Image -->
                            @if ($gym->image)
                                <div class="relative h-48 bg-gray-200 rounded-t-lg overflow-hidden">
                                    <img src="{{ asset($gym->image) }}" alt="{{ $gym->nama }}"
                                        class="w-full h-full object-cover">
                                    <!-- Status Badge -->
                                    <div class="absolute top-3 right-3">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $gym->is_open ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $gym->is_open ? 'Buka' : 'Tutup' }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="p-4">
                                <!-- Gym Info -->
                                <div class="mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $gym->nama }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ Str::limit($gym->alamat, 40) }}
                                    </p>
                                    @if ($gym->noHP)
                                        <p class="text-sm text-gray-600">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            {{ $gym->noHP }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Services Preview -->
                                @if ($gym->services && is_array($gym->services))
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Layanan:</p>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach (array_slice($gym->services, 0, 3) as $service)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                    {{ $service['name'] }}
                                                </span>
                                            @endforeach
                                            @if (count($gym->services) > 3)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                                    +{{ count($gym->services) - 3 }} lainnya
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-2 pt-3 border-t border-gray-100">
                                    <a href="{{ route('admin.gyms.show', $gym->id_gym) }}"
                                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.gyms.edit', $gym->id_gym) }}"
                                        class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                                        Edit
                                    </a>
                                    <button onclick="deleteGym({{ $gym->id_gym }}, '{{ $gym->nama }}')"
                                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded text-sm transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H7m2 0v-4h6v4m-6 0a1 1 0 01-1-1v-1a1 1 0 011-1h4a1 1 0 011 1v1a1 1 0 01-1 1m-4 0a1 1 0 01-1-1v-1a1 1 0 011-1h4a1 1 0 011 1v1a1 1 0 01-1 1">
                </path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data gym</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan gym pertama Anda.</p>
            <div class="mt-6">
                <a href="{{ route('admin.gyms.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Gym
                </a>
            </div>
        </div>
    @endif
@endsection

@section('additional-scripts')
    <script>
        function deleteGym(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus gym "${name}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/gyms/${id}`;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = '{{ csrf_token() }}';
                form.appendChild(tokenInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection

@push('page-variables')
    @php
        $modelName = 'gym';
        $routePrefix = 'admin.gyms';
        $pageTitle = 'Manajemen Gym';
        $additionalFilters = [
            [
                'name' => 'location',
                'label' => 'Lokasi',
                'type' => 'text',
                'placeholder' => 'Cari berdasarkan alamat...',
            ],
        ];
    @endphp
@endpush
