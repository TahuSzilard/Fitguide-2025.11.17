<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $category = $request->input('category', 'all');
        $status   = $request->input('status', 'all');

        $products = Product::query()
            ->with('productType')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })
            ->when($category !== 'all', function ($q) use ($category) {
                $q->where('product_type_id', $category);
            })
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('is_active', $status === 'active');
            })
            ->orderBy('id', 'asc')
            ->paginate(20)
            ->withQueryString(); 

        $productTypes = ProductType::orderBy('name')->get();

        return view('admin.products.index', compact( 'products','productTypes','search','category','status'));
    }
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
    public function edit(Product $product)
    {
        $productTypes = ProductType::all();

        return view('admin.products.edit', compact('product', 'productTypes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'product_type_id' => 'required|exists:product_types,id',
        ]);

        $product->update($request->all());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }
    public function create()
{
    $productTypes = ProductType::orderBy('name')->get();
    return view('admin.products.create', compact('productTypes'));
}

public function store(Request $request)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'product_type_id' => 'required|exists:product_types,id',
        'is_active' => 'required|boolean',
        'image' => 'nullable|image|max:4096'
    ]);

    $imageName = null;

    if ($request->hasFile('image')) {
        $imageName = time().'-'.$request->image->getClientOriginalName();
        $request->image->move(public_path('images'), $imageName);
    }

    Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'product_type_id' => $request->product_type_id,
        'is_active' => $request->is_active,
        'image' => $imageName,
    ]);

    return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
}

}
