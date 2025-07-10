@extends('layouts.admin')

@section('judul-halaman', 'Detail Voucher')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold mb-4">Voucher Details</h2>
                        <div class="mb-4">
                            <img src="{{ asset($voucher->image) }}" alt="Voucher Image"
                                class="w-64 h-64 object-cover rounded-lg shadow-md">
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Description</h3>
                            <p>{{ $voucher->description }}</p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Discount Percentage</h3>
                            <p>{{ $voucher->discount_percentage }}%</p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Batas Penggunaan</h3>
                            <p>{{ $voucher->usage_limit ?? 'Tidak ada batas' }}</p>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Jumlah Penggunaan</h3>
                            <p>{{ $voucher->usage_count }}</p>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Status</h3>
                            <p>{{ $voucher->is_used ? 'Sudah digunakan' : 'Belum digunakan' }}</p>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Tanggal Kadaluarsa</h3>
                            <p>{{ $voucher->expired_at}}</p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Code</h3>
                            <p>{{ $voucher->code }}</p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Created At</h3>
                            <p>{{ $voucher->created_at->format('d M Y H:i:s') }}</p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Updated At</h3>
                            <p>{{ $voucher->updated_at->format('d M Y H:i:s') }}</p>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('admin.vouchers.edit', $voucher) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this voucher?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete
                            </button>
                        </form>
                        <a href="{{ route('admin.vouchers.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
