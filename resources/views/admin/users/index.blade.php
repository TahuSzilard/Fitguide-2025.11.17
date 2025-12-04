@extends('admin.layout')

@section('content')
<div class="p-8">

    {{-- PAGE TITLE --}}
    <h1 class="text-4xl font-bold mb-8">Users</h1>

    {{-- SEARCH + ROLE FILTER --}}
    <form method="GET" class="flex flex-col md:flex-row gap-4 mb-6">

        {{-- Search Input --}}
        <div class="relative w-full md:w-1/3">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Search users..."
                   class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        {{-- Role Select --}}
        <div>
        <select name="role"
            class="w-48 px-4 py-2 rounded-lg border border-gray-300 bg-white shadow-sm focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $role=='all' ? 'selected' : '' }}>All Roles</option>
                <option value="admin" {{ $role=='admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $role=='user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        {{-- Search Button --}}
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
            Search
        </button>

    </form>


    {{-- USERS TABLE --}}
    <div class="bg-white shadow rounded-xl overflow-hidden">

        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b text-gray-600 text-sm">
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-7">Role</th>
                    <th class="py-3 px-4">Registered</th>
                    <th class="py-3 px-4">Actions</th>
                </tr>
            </thead>

            <tbody class="text-sm">
                @forelse ($users as $user)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-3 px-4">{{ $user->id }}</td>
                    <td class="py-3 px-4">{{ $user->name }}</td>
                    <td class="py-3 px-4">{{ $user->email }}</td>
                    <td class="py-3 px-4">
                        @if ($user->role === 'admin')
                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">Admin</span>
                        @else
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">User</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ $user->created_at->format('M d, Y') }}</td>

                   <td class="py-3 px-4 flex items-center gap-3">

                        {{-- EDIT --}}
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                        class="text-blue-600 hover:text-blue-800"
                        title="Edit User">
                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                        </a>

                        {{-- DELETE --}}
                        <form action="{{ route('admin.users.destroy', $user->id) }}" 
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this user?')"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-800"
                                    title="Delete User">
                                <i class="fa-solid fa-trash text-lg"></i>
                            </button>
                        </form>

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-6 text-center text-gray-500">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection
