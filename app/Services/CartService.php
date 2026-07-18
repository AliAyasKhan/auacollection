<?php

namespace App\Services;

use App\Repositories\CartRepositoryInterface;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    // Helper to get or generate Guest Session ID
    protected function getSessionId()
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', 'sess_' . uniqid('', true));
        }
        return Session::get('cart_session_id');
    }

    public function getCart()
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : $this->getSessionId();
        return $this->cartRepository->getCartForUserOrSession($userId, $sessionId);
    }

    public function addItem($productId, $variantId = null, $quantity = 1)
    {
        $cart = $this->getCart();

        // Stock check
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if (!$variant || $variant->stock < $quantity) {
                return ['success' => false, 'message' => 'Requested quantity exceeds available stock.'];
            }
        } else {
            $product = Product::find($productId);
            if (!$product || $product->stock < $quantity) {
                return ['success' => false, 'message' => 'Requested quantity exceeds available stock.'];
            }
        }

        $this->cartRepository->addItem($cart, $productId, $variantId, $quantity);

        return ['success' => true, 'message' => 'Item added to cart successfully.'];
    }

    public function updateQuantity($itemId, $quantity)
    {
        $cart = $this->getCart();
        
        // Find cart item to check stock
        $item = $cart->items->firstWhere('id', $itemId);
        if (!$item) {
            return ['success' => false, 'message' => 'Item not found in cart.'];
        }

        // Validate stock
        if ($item->product_variant_id) {
            $variant = ProductVariant::find($item->product_variant_id);
            if (!$variant || $variant->stock < $quantity) {
                return ['success' => false, 'message' => 'Requested quantity exceeds available stock.'];
            }
        } else {
            $product = Product::find($item->product_id);
            if (!$product || $product->stock < $quantity) {
                return ['success' => false, 'message' => 'Requested quantity exceeds available stock.'];
            }
        }

        $this->cartRepository->updateQuantity($cart, $itemId, $quantity);
        return ['success' => true, 'message' => 'Cart updated.'];
    }

    public function removeItem($itemId)
    {
        $cart = $this->getCart();
        $this->cartRepository->removeItem($cart, $itemId);
        return ['success' => true, 'message' => 'Item removed from cart.'];
    }

    public function clearCart()
    {
        $cart = $this->getCart();
        $this->cartRepository->clear($cart);
        return ['success' => true, 'message' => 'Cart cleared.'];
    }

    public function getCartTotals()
    {
        $cart = $this->getCart();
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart->items as $item) {
            $price = $item->product->active_price;
            if ($item->variant) {
                $price += $item->variant->additional_price;
            }
            $subtotal += $price * $item->quantity;
            $totalItems += $item->quantity;
        }

        return [
            'subtotal' => $subtotal,
            'total_items' => $totalItems,
        ];
    }

    public function handleLoginMerge()
    {
        $sessionId = Session::get('cart_session_id');
        $userId = Auth::id();

        if ($sessionId && $userId) {
            $this->cartRepository->mergeGuestCartToUser($sessionId, $userId);
            Session::forget('cart_session_id');
        }
    }
}
