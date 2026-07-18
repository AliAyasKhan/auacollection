<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepositoryInterface;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'search']);
        $orders = $this->orderRepository->getAllOrdersPaginated($filters, 15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = $this->orderRepository->find($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $this->orderRepository->updateStatus($id, $request->status);

        return back()->with('success', 'Order status updated to ' . $request->status);
    }

    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:Approved,Rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->status = $request->payment_status;
        $payment->notes = $request->notes;
        $payment->save();

        if ($request->payment_status === 'Approved') {
            // Automatically advance order status to Confirmed or Payment Verified
            $this->orderRepository->updateStatus($payment->order_id, 'Confirmed');
            $msg = 'Payment approved and order automatically confirmed!';
        } else {
            $this->orderRepository->updateStatus($payment->order_id, 'Cancelled');
            $msg = 'Payment rejected and order automatically cancelled.';
        }

        return back()->with('success', $msg);
    }
}
