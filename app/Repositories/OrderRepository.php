<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $orderData, array $itemsData, array $shippingData, array $paymentData)
    {
        return DB::transaction(function () use ($orderData, $itemsData, $shippingData, $paymentData) {
            // Generate order number if not set
            if (empty($orderData['order_number'])) {
                $orderData['order_number'] = 'AUA-' . strtoupper(Str::random(8)) . '-' . date('Ymd');
            }

            // Create Order
            $order = Order::create($orderData);

            // Create Order Items and Deduct Inventory
            foreach ($itemsData as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'name' => $item['name'],
                    'SKU' => $item['SKU'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                // Reduce Inventory
                if (!empty($item['product_variant_id'])) {
                    $variant = ProductVariant::find($item['product_variant_id']);
                    if ($variant) {
                        $variant->decrement('stock', $item['quantity']);
                    }
                } else {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock', $item['quantity']);
                    }
                }
            }

            // Create Shipping Address
            $shippingData['order_id'] = $order->id;
            ShippingAddress::create($shippingData);

            // Create Payment
            $paymentData['order_id'] = $order->id;
            $paymentData['amount'] = $orderData['total'];
            Payment::create($paymentData);

            return $order;
        });
    }

    public function find($id)
    {
        return Order::with(['items.product.primaryImage', 'items.variant.color', 'items.variant.size', 'payment', 'shippingAddress', 'user'])->findOrFail($id);
    }

    public function findByOrderNumber($orderNumber)
    {
        return Order::with(['items.product.primaryImage', 'items.variant.color', 'items.variant.size', 'payment', 'shippingAddress', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();
    }

    public function getUserOrders($userId, $perPage = 10)
    {
        return Order::with(['items', 'payment'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function getAllOrdersPaginated(array $filters = [], $perPage = 15)
    {
        $query = Order::with(['user', 'payment', 'shippingAddress'])->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        return $query->paginate($perPage);
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();

        // If status is cancelled, restore stock
        if ($status === 'Cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                    }
                } else {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }
        }

        return $order;
    }

    public function addProofOfPayment($orderId, $filePath, $paymentMethod)
    {
        $order = Order::findOrFail($orderId);
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment) {
            $payment->proof_image = $filePath;
            $payment->payment_method = $paymentMethod;
            $payment->status = 'Pending';
            $payment->save();
        }

        return $order;
    }

    public function getStatistics()
    {
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01');

        $todaySales = Order::whereDate('created_at', $today)
            ->whereNotIn('status', ['Cancelled', 'Returned'])
            ->sum('total');

        $monthlyRevenue = Order::whereDate('created_at', '>=', $monthStart)
            ->whereNotIn('status', ['Cancelled', 'Returned'])
            ->sum('total');

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'Pending')->count();
        $completedOrders = Order::where('status', 'Delivered')->count();
        
        $totalCustomers = User::role('customer')->count();
        $totalProducts = Product::count();
        
        // Low Stock Count
        $lowStockLimit = 5;
        $lowStockProducts = Product::where('stock', '<=', $lowStockLimit)->count();
        $lowStockVariants = ProductVariant::where('stock', '<=', $lowStockLimit)->count();
        $totalLowStock = $lowStockProducts + $lowStockVariants;

        $latestOrders = Order::with(['user'])->limit(5)->latest()->get();

        // Top Selling Products
        $topSelling = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'DESC')
            ->limit(5)
            ->with(['product.primaryImage'])
            ->get();

        // Order Statistics for last 7 days
        $salesData = [];
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dates[] = date('d M', strtotime($date));
            $salesData[] = (float) Order::whereDate('created_at', $date)
                ->whereNotIn('status', ['Cancelled', 'Returned'])
                ->sum('total');
        }

        return [
            'today_sales' => $todaySales,
            'monthly_revenue' => $monthlyRevenue,
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'total_customers' => $totalCustomers,
            'total_products' => $totalProducts,
            'total_low_stock' => $totalLowStock,
            'latest_orders' => $latestOrders,
            'top_selling' => $topSelling,
            'chart_dates' => $dates,
            'chart_sales' => $salesData,
        ];
    }
}
