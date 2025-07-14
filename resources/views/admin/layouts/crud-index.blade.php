@extends('layouts.admin')

@section('judul-halaman', ucfirst($modelName) . ' Management')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $pageTitle ?? 'Daftar ' . ucfirst($modelName) }}</h1>
                <p class="text-gray-600 text-sm mt-1">Kelola data {{ $modelName }} sistem</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                @if (isset($additionalButtons))
                    @foreach ($additionalButtons as $button)
                        <a href="{{ $button['url'] }}"
                            class="{{ $button['class'] ?? 'bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded transition-colors' }}">
                            {{ $button['text'] }}
                        </a>
                    @endforeach
                @endif
                <a href="{{ route($routePrefix . '.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah {{ ucfirst($modelName) }}
                </a>
            </div>
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

        @if (isset($statistics) && !empty($statistics))
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-blue-600">{{ $statistics['total'] ?? 0 }}</h3>
                    <p class="text-gray-600 text-sm">Total {{ ucfirst($modelName) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-green-600">{{ $statistics['active'] ?? 0 }}</h3>
                    <p class="text-gray-600 text-sm">{{ ucfirst($modelName) }} Aktif</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-red-600">{{ $statistics['inactive'] ?? 0 }}</h3>
                    <p class="text-gray-600 text-sm">{{ ucfirst($modelName) }} Tidak Aktif</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <h3 class="text-2xl font-bold text-purple-600">{{ $statistics['percentage_active'] ?? 0 }}%</h3>
                    <p class="text-gray-600 text-sm">Persentase Aktif</p>
                </div>
            </div>
        @endif

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h3 class="text-lg font-semibold mb-4">Filter & Pencarian</h3>
            <form method="GET" action="{{ route($routePrefix . '.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari {{ $modelName }}..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                @if (isset($additionalFilters))
                    @foreach ($additionalFilters as $filter)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $filter['label'] }}</label>
                            @if ($filter['type'] === 'select')
                                <select name="{{ $filter['name'] }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="">{{ $filter['placeholder'] ?? 'Pilih ' . $filter['label'] }}
                                    </option>
                                    @foreach ($filter['options'] as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ request($filter['name']) == $value ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="{{ $filter['type'] ?? 'text' }}" name="{{ $filter['name'] }}"
                                    value="{{ request($filter['name']) }}"
                                    placeholder="{{ $filter['placeholder'] ?? '' }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @endif
                        </div>
                    @endforeach
                @endif
                <div class="flex items-end space-x-2">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                        Filter
                    </button>
                    <a href="{{ route($routePrefix . '.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Main Content -->
        @yield('main-content')

    </div>

    @yield('additional-scripts')
@endsection
