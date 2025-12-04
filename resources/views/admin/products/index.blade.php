@extends('admin.layout')

@section('content')
<div class="p-8">

    <h1 class="text-4xl font-bold mb-6">Products</h1>

    {{-- SEARCH + FILTERS + ADD --}}
    <div class="flex flex-wrap gap-4 mb-6">

        {{-- Search --}}
        <form id="searchForm" method="GET" class="flex gap-4 flex-1">
            <input type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search products"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white shadow-sm focus:ring-2 focus:ring-blue-500">

            {{-- Category Filter --}}
            <select name="category"
                onchange="document.getElementById('searchForm').submit()"
                class="px-4 py-2 rounded-lg border border-gray-300 bg-white shadow-sm focus:ring-2 focus:ring-blue-500">

                <option value="all">All Categories</option>
                @foreach($productTypes as $type)
                    <option value="{{ $type->id }}" {{ $category == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select name="status"
                onchange="document.getElementById('searchForm').submit()"
                class="px-4 py-2 rounded-lg border border-gray-300 bg-white shadow-sm focus:ring-2 focus:ring-blue-500">
                <option value="all" {{ $status=='all' ? 'selected' : '' }}>All</option>
                <option value="active" {{ $status=='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $status=='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </form>

        <a href="{{ route('admin.products.create') }}"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Add Product
        </a>


    </div>


    {{-- TABLE --}}
    <div class="bg-white shadow rounded-xl overflow-hidden">

        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="py-3 px-4">Image</th>
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3 px-4">Price</th>
                    <th class="py-3 px-4">Stock</th>
                    <th class="py-3 px-4">Category</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Actions</th>
                </tr>
            </thead>

            <tbody>
            @forelse($products as $p)
                <tr class="border-b hover:bg-gray-50 transition align-middle">

                    {{-- IMAGE --}}
                    <td class="py-3 px-4 align-middle">
                        @if($p->image)
                            <img src="{{ asset('images/' . $p->image) }}"
                                 class="w-10 h-10 rounded object-cover">
                        @else
                            <i class="fa-regular fa-image text-gray-400 text-xl"></i>
                        @endif
                    </td>

                    {{-- NAME --}}
                    <td class="py-3 px-4 align-middle font-medium">{{ $p->name }}</td>

                    {{-- PRICE --}}
                    <td class="py-3 px-4 align-middle">€{{ number_format($p->price, 2) }}</td>

                    {{-- STOCK --}}
                    <td class="py-3 px-4 align-middle">{{ $p->stock }}</td>

                    {{-- CATEGORY --}}
                    <td class="py-3 px-4 align-middle">{{ $p->productType->name ?? '-' }}</td>

                    {{-- STATUS --}}
                    <td class="py-3 px-4 align-middle">
                        @if($p->is_active)
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">Active</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">Inactive</span>
                        @endif
                    </td>

        {{-- ACTIONS --}}
        <td class="py-3 px-4">
            <div class="flex items-center gap-3">

                
                <a href="{{ route('admin.products.edit', $p->id) }}"
                class="text-blue-600 hover:text-blue-800"
                title="Edit">
                    <i class="fa-solid fa-pen-to-square text-lg relative top-[2px]"></i>
                </a>

                <form action="{{ route('admin.products.destroy', $p->id) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:text-red-800"
                            title="Delete">
                        <i class="fa-solid fa-trash text-lg relative top-[2px]"></i>
                    </button>
                </form>

            </div>
        </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-6 text-center text-gray-500">
                        No products found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>

{{-- PAGINATION --}}
<div class="mt-6 flex justify-center">
    {{ $products->onEachSide(1)->links('vendor.pagination.admin') }}
</div>

<div class="mt-2 text-center text-sm text-gray-600">
    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
</div>



</div>
@endsection
