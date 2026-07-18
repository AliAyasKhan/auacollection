<?php

namespace App\Services;

use App\Repositories\OrderRepositoryInterface;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutService
{
    protected $orderRepository;
    protected $cartService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CartService $cartService
    ) {
        $this->orderRepository = $orderRepository;
        $this->cartService = $cartService;
    }

    public function getCheckoutSummary($couponCode = null)
    {
        $totals = $this->cartService->getCartTotals();
        $subtotal = $totals['subtotal'];
        
        $discount = 0.00;
        $coupon = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('status', true)->first();
            if ($coupon && $coupon->isValidFor($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $taxPercentage = (float) Setting::get('tax_percentage', 5.00);
        $shippingCharges = (float) Setting::get('shipping_charges', 250.00);

        // Apply discount first, then calculate tax
        $discountedSubtotal = max(0, $subtotal - $discount);
        $tax = ($discountedSubtotal * $taxPercentage) / 100;
        
        $total = $discountedSubtotal + $tax + $shippingCharges;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon' => $coupon,
            'tax' => $tax,
            'tax_percentage' => $taxPercentage,
            'shipping_charges' => $shippingCharges,
            'total' => $total,
        ];
    }

    public function placeOrder(array $shippingData, $paymentMethod, $couponCode = null, $proofImage = null)
    {
        $cart = $this->cartService->getCart();
        if ($cart->items->isEmpty()) {
            return ['success' => false, 'message' => 'Your cart is empty.'];
        }

        // 1. Verify Stock levels before placing order
        foreach ($cart->items as $item) {
            if ($item->product_variant_id) {
                $variant = ProductVariant::find($item->product_variant_id);
                if (!$variant || $variant->stock < $item->quantity) {
                    return [
                        'success' => false, 
                        'message' => "Product '{$item->product->name}' in variant size/color is out of stock or does not have sufficient quantity."
                    ];
                }
            } else {
                $product = Product::find($item->product_id);
                if (!$product || $product->stock < $item->quantity) {
                    return [
                        'success' => false, 
                        'message' => "Product '{$item->product->name}' is out of stock or does not have sufficient quantity."
                    ];
                }
            }
        }

        // 2. Calculate final totals
        $summary = $this->getCheckoutSummary($couponCode);

        // 3. Prepare Order Data
        $orderData = [
            'user_id' => Auth::id(),
            'order_number' => 'AUA-' . strtoupper(Str::random(8)) . '-' . date('Ymd'),
            'subtotal' => $summary['subtotal'],
            'discount' => $summary['discount'],
            'shipping_charges' => $summary['shipping_charges'],
            'tax' => $summary['tax'],
            'total' => $summary['total'],
            'status' => 'Pending',
            'notes' => $shippingData['notes'] ?? null,
        ];

        // 4. Prepare Order Items Data
        $itemsData = [];
        foreach ($cart->items as $item) {
            $price = $item->product->active_price;
            if ($item->variant) {
                $price += $item->variant->additional_price;
            }

            $variantName = '';
            if ($item->variant) {
                $variantName = ' (';
                if ($item->variant->color) {
                    $variantName .= $item->variant->color->name;
                }
                if ($item->variant->color && $item->variant->size) {
                    $variantName .= ' / ';
                }
                if ($item->variant->size) {
                    $variantName .= $item->variant->size->code;
                }
                $variantName .= ')';
            }

            $itemsData[] = [
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'name' => $item->product->name . $variantName,
                'SKU' => $item->variant ? ($item->variant->SKU ?? $item->product->SKU) : $item->product->SKU,
                'price' => $price,
                'quantity' => $item->quantity,
            ];
        }

        // 5. Prepare Shipping Data
        $shippingDetails = [
            'customer_id' => Auth::id(),
            'first_name' => $shippingData['first_name'],
            'last_name' => $shippingData['last_name'],
            'email' => $shippingData['email'],
            'phone' => $shippingData['phone'],
            'address' => $shippingData['address'],
            'city' => $shippingData['city'],
            'postal_code' => $shippingData['postal_code'],
            'country' => $shippingData['country'] ?? 'Pakistan',
        ];

        // 6. Prepare Payment Data
        $paymentData = [
            'payment_method' => $paymentMethod,
            'status' => 'Pending',
            'proof_image' => $proofImage,
        ];

        // If Cash on delivery, status is Pending COD.
        // If manual bank transfer/Easypaisa, they upload screenshot.
        
        // 7. Write to database using Repository transaction
        $order = $this->orderRepository->create($orderData, $itemsData, $shippingDetails, $paymentData);

        // 8. Clear Cart
        $this->cartService->clearCart();

        // 9. Send Notification (Database Notification)
        try {
            $user = Auth::user();
            // Send standard laravel database notification
            $user->notify(new \App\Notifications\OrderPlacedNotification($order));
        } catch (\Exception $e) {
            // Log or ignore notification failure in development
        }

        return [
            'success' => true,
            'order' => $order,
            'message' => 'Your order has been placed successfully!'
        ];
    }
}
