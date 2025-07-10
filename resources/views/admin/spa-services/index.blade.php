@extends('layouts.admin')

@section('judul-halaman', 'Layanan ' . $spa->nama)

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Layanan {{ $spa->nama }}</h1>
                <p class="mt-2 text-gray-600">Kelola layanan yang tersedia di spa ini</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.spas.index') }}" 
                    class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Spa
                </a>
                <a href="{{ route('admin.spas.services.create', $spa->id_spa) }}" 
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Tambah Layanan
                </a>
            </div>
        </div>
    </div>

    <!-- Spa Info Card -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center space-x-4">
            <img src="{{ $spa->image ? asset($spa->image) : '/placeholder.svg?height=80&width=80' }}" 
                alt="{{ $spa->nama }}" class="w-20 h-20 object-cover rounded-lg">
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900">{{ $spa->nama }}</h2>
                <p class="text-gray-600">{{ $spa->alamat }}</p>
                <p class="text-gray-600">{{ $spa->noHP }}</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-indigo-600">{{ $services->total() }}</div>
                <div class="text-sm text-gray-500">Total Layanan</div>
            </div>
        </div>
    </div>

    <!-- Services List -->
    @if($services->count() > 0)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Daftar Layanan</h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($services as $service)
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $service->image ? asset($service->image) : '/placeholder.svg?height=60&width=60' }}" 
                                    alt="{{ $service->name }}" class="w-15 h-15 object-cover rounded-lg">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h4>
                                    <p class="text-gray-600 text-sm">{{ Str::limit($service->description, 100) }}</p>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span class="text-lg font-bold text-indigo-600">{{ $service->formatted_price }}</span>
                                        <span class="text-sm text-gray-500">{{ $service->duration }} menit</span>
                                        @if($service->category)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $service->category }}</span>
                                        @endif
                                        <span class="px-2 py-1 text-xs rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.spas.services.show', [$spa->id_spa, $service->id]) }}" 
                                    class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.spas.services.edit', [$spa->id_spa, $service->id]) }}" 
                                    class="text-indigo-600 hover:text-indigo-800" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="toggleServiceStatus({{ $service->id }})" 
                                    class="text-yellow-600 hover:text-yellow-800" title="Toggle Status">
                                    <i class="fas fa-toggle-{{ $service->is_active ? 'on' : 'off' }}"></i>
                                </button>
                                <button onclick="deleteService({{ $service->id }})" 
                                    class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $services->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-spa text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Layanan</h3>
            <p class="text-gray-600 mb-6">Spa ini belum memiliki layanan. Tambahkan layanan pertama sekarang.</p>
            <a href="{{ route('admin.spas.services.create', $spa->id_spa) }}" 
                class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-plus mr-2"></i>Tambah Layanan Pertama
            </a>
        </div>
    @endif
</div>

<script>
function toggleServiceStatus(serviceId) {
    if (confirm('Apakah Anda yakin ingin mengubah status layanan ini?')) {
        fetch(`/admin/spas/{{ $spa->id_spa }}/services/${serviceId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status layanan.');
        });
    }
}

function deleteService(serviceId) {
    if (confirm('Apakah Anda yakin ingin menghapus layanan ini? Tindakan ini tidak dapat dibatalkan.')) {
        fetch(`/admin/spas/{{ $spa->id_spa }}/services/${serviceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus layanan.');
        });
    }
}
</script>
@endsection
