<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /** Kosár lekérése sessionből */
    private function getCart(Request $request): array
    {
        return $request->session()->get('cart', []);
    }

    /** Kosár mentése sessionbe */
    private function saveCart(Request $request, array $cart): void
    {
        $request->session()->put('cart', $cart);
    }

    /** Kosár oldal megjelenítése */
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $shipping = 0.0;
        $total = $subtotal + $shipping;

        return response()
            ->view('cart.index', compact('cart', 'subtotal', 'shipping', 'total'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /** Termék hozzáadása a kosárhoz */
    public function add(Request $request, Product $product)
    {
        // Csak akkor tiltson, ha explicit inaktív
        if (!is_null($product->is_active) && !$product->is_active) {
            return $request->expectsJson()
                ? response()->json(['ok' => false, 'msg' => 'Product inactive.'], 422)
                : back();
        }

        $qty = max(1, (int)$request->input('qty', 1));

        // Készletellenőrzés, ha van stock mező
        if (!is_null($product->stock)) {
            if ($product->stock <= 0) {
                return $request->expectsJson()
                    ? response()->json(['ok' => false, 'msg' => 'Out of stock.'], 422)
                    : back();
            }
            if ($qty > $product->stock) {
                return $request->expectsJson()
                    ? response()->json(['ok' => false, 'msg' => 'Not enough stock.'], 422)
                    : back();
            }
        }

        // Kosár frissítése
        $cart = $this->getCart($request);

        if (isset($cart[$product->id])) {
            $newQty = $cart[$product->id]['qty'] + $qty;
            if (!is_null($product->stock) && $newQty > $product->stock) {
                return $request->expectsJson()
                    ? response()->json(['ok' => false, 'msg' => 'Not enough stock.'], 422)
                    : back();
            }
            $cart[$product->id]['qty'] = $newQty;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => (float)$product->price,
                'qty'        => $qty,
            ];
        }

        $this->saveCart($request, $cart);
        $count = collect($cart)->sum('qty');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok'    => true,
                'count' => $count,
            ]);
        }

        return back();
    }

    /** Kosár frissítése (mennyiség módosítása) */
    public function update(Request $request, Product $product)
    {
        $cart = $this->getCart($request);

        if (!isset($cart[$product->id])) {
            return $request->expectsJson()
                ? response()->json(['ok' => false, 'msg' => 'Product not in cart.'], 404)
                : back();
        }

        $qty = max(1, (int)$request->input('qty', 1));
        if (isset($product->stock) && $qty > $product->stock) {
            return $request->expectsJson()
                ? response()->json(['ok' => false, 'msg' => 'Not enough stock.'], 422)
                : back();
        }

        $cart[$product->id]['qty'] = $qty;
        $this->saveCart($request, $cart);

        $lineTotal = $cart[$product->id]['price'] * $qty;
        $subtotal  = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $shipping  = 0.0;
        $total     = $subtotal + $shipping;
        $count     = collect($cart)->sum('qty');

        if ($request->expectsJson()) {
            return response()->json([
                'ok'        => true,
                'qty'       => $qty,
                'lineTotal' => number_format($lineTotal, 2, '.', ''),
                'subtotal'  => number_format($subtotal, 2, '.', ''),
                'shipping'  => number_format($shipping, 2, '.', ''),
                'total'     => number_format($total, 2, '.', ''),
                'count'     => $count,
            ]);
        }

        return back();
    }

    /** Termék eltávolítása a kosárból */
    public function remove(Request $request, Product $product)
    {
        $cart = $this->getCart($request);
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            $this->saveCart($request, $cart);
        }

        $count = collect($cart)->sum('qty');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok'    => true,
                'count' => $count,
            ]);
        }

        return back();
    }

    /** Kosár teljes ürítése */
    public function clear(Request $request)
    {
        $this->saveCart($request, []);
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'count' => 0]);
        }
        return back();
    }

    /** Kosárban lévő termékek száma (AJAX) */
    public function count(Request $request)
    {
        $count = collect($this->getCart($request))->sum('qty');
        return response()->json(['count' => $count]);
    }
}
