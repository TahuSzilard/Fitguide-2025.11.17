<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Mail\OrderPlaced;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('store.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
        $shipping = 0.0;
        $total = $subtotal + $shipping;

        return view('checkout.show', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    public function place(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('store.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'full_name'    => 'required|string|max:255',
            'address_line1'=> 'required|string|max:255',
            'address_line2'=> 'nullable|string|max:255',
            'city'         => 'required|string|max:255',
            'postal_code'  => 'required|string|max:20',
            'country'      => 'required|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
            $shipping = 0.0;
            $total = $subtotal + $shipping;

            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'currency' => 'EUR',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'full_name' => $validated['full_name'],
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'],
            ]);

            foreach ($cart as $item) {
                $product = Product::findOrFail($item['product_id']);

                if (!$product->is_active || $product->stock < $item['qty']) {
                    throw new \Exception("Product {$product->name} not available in requested qty.");
                }

                $product->decrement('stock', $item['qty']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'unit_price' => $product->price,
                    'qty' => $item['qty'],
                    'line_total' => $product->price * $item['qty'],
                ]);
            }

            // ⭐ Pontok hozzáadása: minden darab után 10 pont
            $earnedPoints = collect($cart)->sum(fn ($item) => $item['qty'] * 10);
            Auth::user()->increment('points', $earnedPoints);

            DB::commit();

            Mail::to(Auth::user()->email)->send(
                new OrderPlaced($cart, $subtotal, $shipping, $total, $order)
            );

            $request->session()->forget('cart');

            return redirect()->route('store.index')->with(
                'success',
                "Order placed successfully! +{$earnedPoints} points earned 🎉"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }
}
