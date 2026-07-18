<?php

namespace App\Repositories;

interface CartRepositoryInterface
{
    public function getCartForUserOrSession($userId = null, $sessionId = null);
    public function addItem($cart, $productId, $variantId = null, $quantity = 1);
    public function updateQuantity($cart, $itemId, $quantity);
    public function removeItem($cart, $itemId);
    public function clear($cart);
    public function mergeGuestCartToUser($sessionId, $userId);
}
