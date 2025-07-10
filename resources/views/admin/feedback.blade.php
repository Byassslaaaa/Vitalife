@extends('layouts.admin')

@section('judul-halaman', 'Feedback Pengguna')

@section('content')
    <div class="max-w-7xl mx-auto p-4 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold mb-4">Daftar Feedback Pengguna</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($feedbacks as $feedback)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $feedback->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $feedback->email }}</td>
                            <td class="px-6 py-4">{{ $feedback->message }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $feedback->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $feedbacks->links() }}
        </div>
    </div>
@endsection
