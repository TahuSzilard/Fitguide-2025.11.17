@extends('admin.layout')

@section('content')
<div class="p-8">

    <h1 class="text-3xl font-bold mb-6">Edit Product</h1>

    <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
        @csrf
        @method('PUT')

        <label class="block mb-3">
            <span>Name</span>
            <input type="text" name="name" class="w-full p-2 border rounded"
                   value="{{ $product->name }}" required>
        </label>

        <label class="block mb-3">
            <span>Price</span>
            <input type="number" name="price" step="0.01"
                   class="w-full p-2 border rounded"
                   value="{{ $product->price }}" required>
        </label>

        <label class="block mb-3">
            <span>Stock</span>
            <input type="number" name="stock"
                   class="w-full p-2 border rounded"
                   value="{{ $product->stock }}" required>
        </label>

        <label class="block mb-6">
            <span>Category</span>
            <select name="product_type_id" class="w-full p-2 border rounded">
                @foreach($productTypes as $type)
                    <option value="{{ $type->id }}"
                        {{ $product->product_type_id == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </label>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
            Save Changes
        </button>

    </form>
</div>
@endsection
