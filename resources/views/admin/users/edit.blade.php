@extends('admin.layout')

@section('content')
<div class="p-8 max-w-xl mx-auto bg-white shadow rounded-xl">

    <h1 class="text-3xl font-bold mb-8">Edit User</h1>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <label class="block font-semibold mb-1">Name</label>
        <input type="text" name="name" value="{{ $user->name }}"
               class="w-full mb-4 px-4 py-2 border rounded-lg">

        {{-- Email --}}
        <label class="block font-semibold mb-1">Email</label>
        <input type="email" name="email" value="{{ $user->email }}"
               class="w-full mb-4 px-4 py-2 border rounded-lg">

               <select name="role" class="w-full mb-6 px-4 py-2 border rounded-lg">
                @if ($user->id === auth()->id())
                    <option value="admin" selected>Admin</option>
                @else
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                @endif
            </select>

            <button class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mt-6">
                Save Changes
            </button>

    </form>

</div>
@endsection
