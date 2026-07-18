@extends('layouts.admin')

@section('title', 'Manage Orders - AUA Collection')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif mb-1 fw-bold text-uppercase letter-spacing-1">Customer Orders</h4>
        <p class="text-muted small mb-0">Monitor purchases, payment verification and shipment processes</p>
    </div>
</div>

<div class="card card-luxury p-4">
    <!-- Status Tabs / Filter Row -->
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div class="d-flex flex-wrap gap-1">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-dark' }}">All</a>
            @foreach(['Pending', 'Confirmed', 'Packing', 'Ready To Ship', 'Shipped', 'Out For Delivery', 'Delivered', 'Cancelled'] as $st)
                <a href="{{ route('admin.orders.index', ['status' => $st]) }}" class="btn btn-sm {{ request('status') === $st ? 'btn-dark' : 'btn-outline-dark' }}">{{ $st }}</a>
            @endforeach
        </div>
        
        <div style="min-width: 250px;">
            <form action="{{ route('admin.orders.index') }}" method="GET">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Order #, name, email..." value="{{ request('search') }}">
                    <button class="btn btn-dark btn-sm" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('admin.orders.index', request()->only('status')) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover table-luxury align-middle">
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Customer details</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Payment Details</th>
                    <th>Order Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="font-monospace fw-bold text-dark">{{ $order->order_number }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $order->user ? $order->user->name : 'Guest User' }}</div>
                            <span class="text-muted small">{{ $order->user ? $order->user->email : '' }}</span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                        <td class="fw-semibold text-dark">Rs. {{ number_format($order->total, 2) }}</td>
                        <td>
                            @if($order->payment)
                                <span class="badge badge-luxury bg-light border text-dark">{{ $order->payment->payment_method }}</span>
                                @if($order->payment->status === 'Pending')
                                    <span class="badge bg-warning text-dark badge-luxury small ms-1">Verify</span>
                                @elseif($order->payment->status === 'Approved')
                                    <span class="badge bg-success text-white badge-luxury small ms-1">Approved</span>
                                @else
                                    <span class="badge bg-danger text-white badge-luxury small ms-1">Rejected</span>
                                @endif
                            @else
                                <span class="text-muted small">N/A</span>
                            @endif
                        </td>
                        <td>
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
                            <span class="badge bg-{{ $color }} text-uppercase fs-7 badge-luxury">{{ $order->status }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-luxury-dark btn-sm">VIEW</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                            No orders found under this criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection
