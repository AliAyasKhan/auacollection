@extends('layouts.admin')

@section('title', 'Order Details - AUA Collection')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-link text-dark p-0 text-decoration-none mb-2"><i class="bi bi-arrow-left"></i> Back to Orders</a>
    <h4 class="font-serif fw-bold text-uppercase letter-spacing-1">Order Details: {{ $order->order_number }}</h4>
</div>

<div class="row">
    <!-- Main Left: Items & Delivery Address -->
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card card-luxury p-4 mb-4">
            <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Purchased Items</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="small text-uppercase text-muted border-bottom">
                            <th colspan="2">Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td style="width: 60px;">
                                    <img src="{{ $item->product ? $item->product->image_url : asset('assets/images/placeholder.jpg') }}" alt="{{ $item->name }}" class="rounded-2" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $item->name }}</div>
                                    @if($item->variant)
                                        <div class="text-muted small mt-1">
                                            @if($item->variant->color) Color: {{ $item->variant->color->name }} @endif
                                            @if($item->variant->size) | Size: {{ $item->variant->size->name }} @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="font-monospace small">{{ $item->SKU }}</td>
                                <td>Rs. {{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end fw-bold text-dark">Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="row justify-content-end mt-3 border-top pt-3">
                <div class="col-md-5">
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Subtotal:</span>
                        <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2 small text-danger">
                            <span>Discount:</span>
                            <span>- Rs. {{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Shipping Charges:</span>
                        <span>Rs. {{ number_format($order->shipping_charges, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Tax:</span>
                        <span>Rs. {{ number_format($order->tax, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold text-dark fs-5">
                        <span>Grand Total:</span>
                        <span class="text-gold">Rs. {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="card card-luxury p-4">
            <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Shipping Information</h5>
            @if($order->shippingAddress)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <span class="text-muted small d-block">RECIPIENT NAME</span>
                        <strong class="text-dark">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</strong>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="text-muted small d-block">CONTACT PHONE</span>
                        <strong class="text-dark">{{ $order->shippingAddress->phone }}</strong>
                    </div>
                    <div class="col-12 mb-3">
                        <span class="text-muted small d-block">STREET ADDRESS</span>
                        <strong class="text-dark">{{ $order->shippingAddress->address_line1 }} {{ $order->shippingAddress->address_line2 }}</strong>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-muted small d-block">CITY</span>
                        <strong class="text-dark">{{ $order->shippingAddress->city }}</strong>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-muted small d-block">POSTAL CODE</span>
                        <strong class="text-dark">{{ $order->shippingAddress->postal_code }}</strong>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-muted small d-block">COUNTRY / STATE</span>
                        <strong class="text-dark">{{ $order->shippingAddress->state }}, {{ $order->shippingAddress->country }}</strong>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">No shipping address found.</p>
            @endif
        </div>
    </div>

    <!-- Sidebars: Status Timeline & Payment Verify -->
    <div class="col-lg-4">
        <!-- Order Status Admin Panel -->
        <div class="card card-luxury p-4 mb-4">
            <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Update Order Status</h5>
            
            <div class="mb-3">
                <span class="text-muted small d-block mb-2">CURRENT STATUS</span>
                @php
                    $statusColors = [
                        'Pending' => 'secondary',
                        'Confirmed' => 'primary',
                        'Packing' => 'info text-dark',
                        'Ready To Ship' => 'warning text-dark',
                        'Shipped' => 'warning text-dark',
                        'Out For Delivery' => 'warning text-dark',
                        'Delivered' => 'success',
                        'Cancelled' => 'danger',
                    ];
                    $color = $statusColors[$order->status] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $color }} text-uppercase px-3 py-2 fs-6 badge-luxury d-inline-block w-100 text-center">{{ $order->status }}</span>
            </div>

            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="status" class="form-label small fw-bold">Select Status Step</label>
                    <select name="status" id="status" class="form-select">
                        @foreach(['Pending', 'Confirmed', 'Packing', 'Ready To Ship', 'Shipped', 'Out For Delivery', 'Delivered', 'Cancelled'] as $st)
                            <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-luxury-dark w-100">UPDATE STATUS</button>
            </form>
        </div>

        <!-- Payment Verification Panel -->
        <div class="card card-luxury p-4">
            <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase border-bottom pb-2">Payment Details</h5>
            
            @if($order->payment)
                <div class="mb-3">
                    <span class="text-muted small d-block">PAYMENT METHOD</span>
                    <strong class="text-dark">{{ $order->payment->payment_method }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">PAYMENT STATUS</span>
                    @if($order->payment->status === 'Pending')
                        <span class="badge bg-warning text-dark badge-luxury">Pending Verification</span>
                    @elseif($order->payment->status === 'Approved')
                        <span class="badge bg-success text-white badge-luxury">Approved / Paid</span>
                    @else
                        <span class="badge bg-danger text-white badge-luxury">Rejected / Unpaid</span>
                    @endif
                </div>

                @if($order->payment->notes)
                    <div class="mb-3 p-3 bg-light rounded-3 small">
                        <span class="text-muted d-block mb-1">Verification Notes:</span>
                        {{ $order->payment->notes }}
                    </div>
                @endif

                <!-- Screenshot View -->
                @if($order->payment->proof_image)
                    <div class="mb-4">
                        <span class="text-muted small d-block mb-2">UPLOADED PROOF SCREENSHOT</span>
                        <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank">
                            <img src="{{ File::exists(public_path('storage/' . $order->payment->proof_image)) ? asset('storage/' . $order->payment->proof_image) : asset('assets/images/placeholder.jpg') }}" class="img-thumbnail w-100 rounded-3" style="max-height: 250px; object-fit: contain;">
                        </a>
                        <span class="text-muted fs-7 text-center d-block mt-1">Click image to enlarge</span>
                    </div>

                    @if($order->payment->status === 'Pending')
                        <button type="button" class="btn btn-luxury-gold w-100 mb-2" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal">VERIFY PAYMENT</button>
                    @endif
                @else
                    <p class="text-muted small mb-0">No payment receipt proof has been uploaded by the customer yet.</p>
                @endif
            @else
                <p class="text-muted mb-0">No payment record found.</p>
            @endif
        </div>
    </div>
</div>

<!-- Verify Payment Modal -->
@if($order->payment && $order->payment->status === 'Pending')
    <div class="modal fade" id="verifyPaymentModal" tabindex="-1" aria-labelledby="verifyPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-serif fw-bold text-uppercase" id="verifyPaymentModalLabel">Verify Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.payments.verify', $order->payment->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold d-block">Verification Decision</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_status" id="decisionApprove" value="Approved" checked>
                                <label class="form-check-label text-success fw-bold" for="decisionApprove">Approve Payment</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_status" id="decisionReject" value="Rejected">
                                <label class="form-check-label text-danger fw-bold" for="decisionReject">Reject Payment</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label small fw-bold">Internal Notes / Reason</label>
                            <textarea name="notes" id="notes" rows="4" class="form-control" placeholder="Input check numbers, verification IDs or rejection reasons..."></textarea>
                        </div>
                        
                        <p class="text-muted small">
                            <i class="bi bi-info-circle"></i> Approving will automatically set the order status to <strong>Confirmed</strong>. Rejecting will set it to <strong>Cancelled</strong> and restore inventory counts.
                        </p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-luxury-dark">Submit Decision</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@endsection
