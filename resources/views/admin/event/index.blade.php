@extends('layouts.admin')

@section('judul-halaman', 'Event')

@section('content')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Daftar</h1>
            <a href="{{ route('admin.event.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Tambah event Baru
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">

            <div class="overflow-x-auto">
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Nama Event</th>
                            <th class="py-2 px-4 border-b">Deskripsi</th>
                            <th class="py-2 px-4 border-b">Alamat</th>
                            <th class="py-2 px-4 border-b">Tanggal</th>
                            <th class="py-2 px-4 border-b">Harga</th>
                            <th class="py-2 px-4 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($events as $event)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ is_array($event->nama) ? implode(', ', $event->nama) : $event->nama }}</td>
                                <td class="py-2 px-4 border-b">{{ is_array($event->deskripsi) ? implode(', ', $event->deskripsi) : $event->deskripsi }}</td>
                                <td class="py-2 px-4 border-b">{{ is_array($event->alamat) ? implode(', ', $event->alamat) : $event->alamat }}</td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="text-gray-500">{{ ($event->tanggal)->format('d M Y')}}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500">
                                    Rp {{ number_format($event->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium">
                                    <a href="{{ route('admin.event.edit', ['id_event' => $event->id_event]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.event.destroy', ['id_event' => $event->id_event]) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-2 px-4 border-b text-center">Tidak ada data event</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection