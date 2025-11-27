<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Termékek listázása opcionális típus szűréssel.
     * ?type=all|supplements|snacks|equipment|clothing|accessories|packages-gift
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');

        $products = Product::with('productType')
            ->when($type !== 'all', fn($q) =>
                $q->whereHas('productType', fn($t) => $t->where('slug', $type))
            )
            ->orderBy('name')
            ->paginate(24)
            ->appends(['type' => $type]); // lapozó megőrzi a szűrőt

        // AJAX eset: csak a terméklista résznézetet adjuk vissza
        if ($request->ajax()) {
            return view('store.partials.products', compact('products', 'type'))->render();
        }

        $productTypes = ProductType::all();

        return view('store.index', compact('products', 'productTypes'))
            ->with('active', $type);
    }
}
