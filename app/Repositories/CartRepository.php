<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;

class CartRepository implements CartRepositoryInterface
{
    public function getCartForUserOrSession($userId = null, $sessionId = null)
    {
        if ($userId) {
            $cart = Cart::firstOrCreate(['user_id' => $userId]);
        } else {
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        // Load items with relationships
        return Cart::with([
            'items.product.primaryImage', 
            'items.product.category',
            'items.variant.color', 
            'items.variant.size'
        ])->find($cart->id);
    }

    public function addItem($cart, $productId, $variantId = null, $quantity = 1)
    {
        // Check if product variant exists if variantId is provided
        $product = Product::findOrFail($productId);
        $price = $product->active_price;

        if ($variantId) {
            $variant = ProductVariant::findOrFail($variantId);
            $price += $variant->additional_price;
        }

        // Check if this item is already in the cart
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        return $item;
    }

    public function updateQuantity($cart, $itemId, $quantity)
    {
        $item = CartItem::where('cart_id', $cart->id)->findOrFail($itemId);

        if ($quantity <= 0) {
            $item->delete();
            return null;
        }

        $item->quantity = $quantity;
        $item->save();
        return $item;
    }

    public function removeItem($cart, $itemId)
    {
        $item = CartItem::where('cart_id', $cart->id)->findOrFail($itemId);
        return $item->delete();
    }

    public function clear($cart)
    {
        return CartItem::where('cart_id', $cart->id)->delete();
    }

    public function mergeGuestCartToUser($sessionId, $userId)
    {
        $guestCart = Cart::where('session_id', $sessionId)->first();
        if (!$guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $userId]);

        $guestItems = CartItem::where('cart_id', $guestCart->id)->get();
        foreach ($guestItems as $guestItem) {
            // Check if user cart already has this item
            $existingItem = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $guestItem->product_id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $guestItem->quantity;
                $existingItem->save();
                $guestItem->delete();
            } else {
                $guestItem->cart_id = $userCart->id;
                $guestItem->save();
            }
        }

        $guestCart->delete();
    }
}
