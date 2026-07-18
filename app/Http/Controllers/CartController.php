<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $totals = $this->cartService->getCartTotals();
        return view('store.cart', compact('cart', 'totals'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $result = $this->cartService->addItem(
            $request->product_id,
            $request->product_variant_id,
            $request->quantity
        );

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $result = $this->cartService->updateQuantity($itemId, $request->quantity);

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function remove(Request $request, $itemId)
    {
        $result = $this->cartService->removeItem($itemId);

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    public function clear(Request $request)
    {
        $result = $this->cartService->clearCart();

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }
}
