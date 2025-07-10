@extends('layouts.admin')

@section('judul-halaman', 'Account User')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold">Account User</h2>
                    <a href="{{ route('admin.account.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create Account
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">NAME</th>
                                <th class="px-4 py-2 text-left">EMAIL</th>
                                <th class="px-4 py-2 text-left">ROLE</th>
                                <th class="px-4 py-2 text-left">REGISTERED AT</th>
                                <th class="px-4 py-2 text-left">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">{{ ucfirst($user->role) }}</td>
                                <td class="px-4 py-2">{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.account.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                    <form action="{{ route('admin.account.destroy', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this account?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection