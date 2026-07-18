<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepositoryInterface;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AccountController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $user = Auth::user();
        return view('store.account', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|confirmed|' . Rules\Password::default(),
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->new_password) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function orders()
    {
        $orders = $this->orderRepository->getUserOrders(Auth::id(), 10);
        return view('store.orders', compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
        $order = $this->orderRepository->findByOrderNumber($orderNumber);
        
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('store.order_detail', compact('order'));
    }

    public function trackOrder(Request $request)
    {
        $orderNumber = $request->query('order_number');
        $order = null;
        $error = null;

        if ($orderNumber) {
            try {
                $order = Order::where('order_number', $orderNumber)->firstOrFail();
            } catch (\Exception $e) {
                $error = "Order number not found. Please double check.";
            }
        }

        return view('store.track', compact('order', 'orderNumber', 'error'));
    }

    public function downloadInvoice($orderNumber)
    {
        $order = $this->orderRepository->findByOrderNumber($orderNumber);
        
        // Ensure user owns this order or is admin
        if ($order->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        return view('store.invoice', compact('order'));
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => true, // Auto approve for simplicity or set to false for moderation
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
