@extends('layouts.admin')

@section('judul-halaman', 'Edit Voucher')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" name="image" id="image"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <img src="{{ asset($voucher->image) }}" alt="Current Voucher Image"
                                class="mt-2 w-32 h-32 object-cover">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ $voucher->description }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Discount Percentage</label>
                            <input type="number" name="discount_percentage" id="discount_percentage" min="0" max="100" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                value="{{ $voucher->discount_percentage ?? old('discount_percentage') }}">
                        </div>
                        <div class="mb-4">
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700">Batas Penggunaan</label>
                            <input type="number" name="usage_limit" id="usage_limit" min="1"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                value="{{ $voucher->usage_limit ?? old('usage_limit') }}">
                        </div>

                        <div class="mb-4">
                            <label for="expired_at" class="block text-sm font-medium text-gray-700">Tanggal Kadaluarsa</label>
                            <input type="date" name="expired_at" id="expired_at"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                value="{{ $voucher->expired_at ? $voucher->expired_at->format('Y-m-d') : old('expired_at') }}">
                        </div>
                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                            <input type="text" name="code" id="code" value="{{ $voucher->code }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
