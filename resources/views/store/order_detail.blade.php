@extends('layouts.store')

@section('title', 'Order ' . $order->order_number . ' - AUA Collection')

@section('content')

    <!-- Header -->
    <section class="py-4 bg-light">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="font-serif mb-0 fs-3">Order Details</h1>
            <a href="{{ route('account.invoice', $order->order_number) }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-3 py-1.5 px-3"><i class="bi bi-printer me-2"></i>PRINT INVOICE</a>
        </div>
    </section>

    <!-- Content -->
    <section class="py-5">
        <div class="container">
            
            <div class="row g-4">
                
                <!-- Account Sidebar Navigation -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 dashboard-sidebar">
                        <div class="text-center mb-4 pb-3 border-bottom">
                            <h5 class="mb-1 font-heading fs-6">{{ auth()->user()->name }}</h5>
                            <span class="text-muted small text-truncate d-block">{{ auth()->user()->email }}</span>
                        </div>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('account.dashboard') }}"><i class="bi bi-person-fill me-2"></i>Profile Settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('account.orders') }}"><i class="bi bi-bag-fill me-2"></i>My Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('account.track') }}"><i class="bi bi-geo-alt-fill me-2"></i>Track Order</a>
                            </li>
                            <li class="nav-item mt-3 pt-3 border-top">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-danger text-start nav-link w-100 p-0 ps-3 border-0"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="col-md-9">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light">
                            <div>
                                <span class="text-muted small d-block mb-1">ORDER NUMBER</span>
                                <h5 class="font-monospace fw-bold mb-0 text-dark">{{ $order->order_number }}</h5>
                            </div>
                            <div class="text-end">
                                <span class="text-muted small d-block mb-1">STATUS</span>
                                <span class="badge bg-{{ $order->status_color }} py-2 px-3">{{ strtoupper($order->status) }}</span>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive mb-4">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr class="small text-muted font-heading">
                                        <th class="border-0">GARMENT</th>
                                        <th class="border-0">SKU</th>
                                        <th class="border-0">PRICE</th>
                                        <th class="border-0 text-center">QTY</th>
                                        <th class="border-0 text-end">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div style="width: 50px; height: 60px; overflow: hidden; background-color: var(--color-gray-100);" class="rounded-2">
                                                        @if($item->product->primaryImage && $item->product->primaryImage->image_path)
                                                            <img src="{{ $item->product->image_url }}" class="w-100 h-100 object-fit-cover" alt="">
                                                        @else
                                                            <div class="image-placeholder w-100 h-100 fs-7 pt-2">#</div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="fs-7 mb-0 text-dark fw-bold">{{ $item->name }}</h6>
                                                        <span class="text-muted small">{{ $item->product->brand->name ?? 'AUA Label' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="font-monospace text-muted">{{ $item->SKU }}</td>
                                            <td>{{ $storeCurrencySymbol }}{{ number_format($item->price) }}</td>
                                            <td class="text-center font-monospace">{{ $item->quantity }}</td>
                                            <td class="text-end fw-bold text-dark">{{ $storeCurrencySymbol }}{{ number_format($item->price * $item->quantity) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pricing Breakdown -->
                        <div class="row justify-content-end mb-4">
                            <div class="col-md-5">
                                <div class="bg-light p-3 rounded-3 small">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal:</span>
                                        <span class="fw-semibold text-dark">{{ $storeCurrencySymbol }}{{ number_format($order->subtotal) }}</span>
                                    </div>
                                    @if($order->discount > 0)
                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Coupon Discount:</span>
                                            <span>-{{ $storeCurrencySymbol }}{{ number_format($order->discount) }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Shipping Charges:</span>
                                        <span class="fw-semibold text-dark">{{ $storeCurrencySymbol }}{{ number_format($order->shipping_charges) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">GST / Tax:</span>
                                        <span class="fw-semibold text-dark">{{ $storeCurrencySymbol }}{{ number_format($order->tax) }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between text-dark fw-bold">
                                        <span>Grand Total:</span>
                                        <span>{{ $storeCurrencySymbol }}{{ number_format($order->total) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info details row -->
                        <div class="row g-4 pt-3 border-top border-light">
                            <!-- Shipping -->
                            <div class="col-md-6">
                                <h6 class="font-heading fs-7 text-dark fw-bold mb-3">SHIPPING ADDRESS</h6>
                                <div class="small text-muted lh-base">
                                    <p class="mb-1 text-dark fw-semibold">{{ $order->shippingAddress->full_name }}</p>
                                    <p class="mb-1">{{ $order->shippingAddress->address }}</p>
                                    <p class="mb-1">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postal_code }}</p>
                                    <p class="mb-1">Phone: {{ $order->shippingAddress->phone }}</p>
                                    <p class="mb-0">Email: {{ $order->shippingAddress->email }}</p>
                                </div>
                            </div>
                            <!-- Payment -->
                            <div class="col-md-6">
                                <h6 class="font-heading fs-7 text-dark fw-bold mb-3">PAYMENT DETAILS</h6>
                                <div class="small text-muted lh-base">
                                    <p class="mb-1"><strong>Method:</strong> {{ $order->payment->payment_method }}</p>
                                    <p class="mb-1"><strong>Status:</strong> 
                                        <span class="badge bg-{{ $order->payment->status_color }}">{{ $order->payment->status }}</span>
                                    </p>
                                    @if($order->payment->transaction_id)
                                        <p class="mb-1"><strong>Transaction ID:</strong> <span class="font-monospace">{{ $order->payment->transaction_id }}</span></p>
                                    @endif
                                    @if($order->payment->proof_image)
                                        <p class="mb-0"><strong>Proof Uploaded:</strong> <span class="text-success fw-bold">Yes</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
