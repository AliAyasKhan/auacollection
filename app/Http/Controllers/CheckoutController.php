<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    protected $checkoutService;
    protected $cartService;

    public function __construct(
        CheckoutService $checkoutService,
        CartService $cartService
    ) {
        $this->checkoutService = $checkoutService;
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getCart();
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $couponCode = $request->query('coupon');
        $summary = $this->checkoutService->getCheckoutSummary($couponCode);

        return view('store.checkout', compact('cart', 'summary', 'couponCode'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $summary = $this->checkoutService->getCheckoutSummary($request->coupon_code);

        if ($request->wantsJson()) {
            if ($request->coupon_code && $summary['discount'] <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired coupon code, or minimum spend limit not met.'
                ]);
            }

            return response()->json([
                'success' => true,
                'summary' => $summary,
                'message' => 'Coupon code applied successfully.'
            ]);
        }

        if ($request->coupon_code && $summary['discount'] <= 0) {
            return redirect()->route('checkout.index')->with('error', 'Invalid or expired coupon code.');
        }

        return redirect()->route('checkout.index', ['coupon' => $request->coupon_code])
            ->with('success', 'Coupon code applied successfully.');
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:COD,Bank Transfer,Easypaisa,JazzCash',
            'proof_image' => 'nullable|image|max:2048', // 2MB max
            'notes' => 'nullable|string|max:1000',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        // Upload Payment proof screenshot if manual payment
        $proofImagePath = null;
        if ($request->hasFile('proof_image')) {
            $proofImagePath = $request->file('proof_image')->store('payments', 'public');
        }

        $shippingData = $request->only([
            'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postal_code', 'notes'
        ]);

        $result = $this->checkoutService->placeOrder(
            $shippingData,
            $request->payment_method,
            $request->coupon_code,
            $proofImagePath
        );

        if (!$result['success']) {
            return back()->withInput()->with('error', $result['message']);
        }

        return redirect()->route('checkout.success', ['order_number' => $result['order']->order_number]);
    }

    public function success($orderNumber)
    {
        return view('store.success', compact('orderNumber'));
    }
}
