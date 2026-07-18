@extends('layouts.store')

@section('title', 'Shopping Cart - AUA Collection')

@section('content')

    <section class="py-5">
        <div class="container">
            <h1 class="font-serif mb-5 fs-2">Your Shopping Bag</h1>

            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 mb-4 text-center">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-3 mb-4 text-center">{{ session('error') }}</div>
            @endif

            @if($cart->items->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-bag fs-1 text-muted d-block mb-3"></i>
                    <h3 class="font-serif">Your Bag is Empty</h3>
                    <p class="text-muted">You have not added any luxury items to your cart yet.</p>
                    <a href="{{ url('/shop') }}" class="btn-luxury-dark mt-3">START SHOPPING</a>
                </div>
            @else
                <div class="row g-5">
                    
                    <!-- Cart Items List -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            @foreach($cart->items as $item)
                                <div class="row cart-item-row align-items-center">
                                    <!-- Image -->
                                    <div class="col-md-2 col-3">
                                        <div class="bg-light rounded-3 overflow-hidden" style="width: 100%; padding-bottom: 125%; position: relative;">
                                            @if($item->product->primaryImage && $item->product->primaryImage->image_path)
                                                <img src="{{ $item->product->image_url }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" alt="{{ $item->product->name }}">
                                            @else
                                                <div class="image-placeholder position-absolute top-0 start-0 w-100 h-100">
                                                    <span>{{ strtoupper(substr($item->product->name, 0, 2)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Details -->
                                    <div class="col-md-4 col-9 mb-md-0 mb-3">
                                        <h5 class="fs-6 mb-1">
                                            <a href="{{ route('store.product.detail', $item->product->slug) }}" class="text-dark text-decoration-none hover-gold">
                                                {{ $item->product->name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-1">{{ $item->product->brand->name ?? 'AUA Label' }}</p>
                                        
                                        <!-- Variant info -->
                                        @if($item->variant)
                                            <span class="badge bg-light text-dark border border-light-subtle rounded-1 py-1 px-2 font-monospace" style="font-size: 0.7rem;">
                                                @if($item->variant->color)
                                                    {{ strtoupper($item->variant->color->name) }}
                                                @endif
                                                @if($item->variant->color && $item->variant->size)
                                                     / 
                                                @endif
                                                @if($item->variant->size)
                                                    {{ $item->variant->size->code }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Quantity Editor -->
                                    <div class="col-md-3 col-6">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group bg-light rounded-3 px-1 py-1" style="max-width: 120px;">
                                                <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="btn btn-link text-dark border-0 p-1 fs-6 shadow-none"><i class="bi bi-dash"></i></button>
                                                <input type="text" class="form-control border-0 bg-transparent text-center shadow-none p-1 fw-bold font-monospace" value="{{ $item->quantity }}" readonly>
                                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="btn btn-link text-dark border-0 p-1 fs-6 shadow-none"><i class="bi bi-plus"></i></button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Price and Delete -->
                                    <div class="col-md-3 col-6 text-md-end text-start">
                                        @php
                                            $unitPrice = $item->product->active_price;
                                            if ($item->variant) {
                                                $unitPrice += $item->variant->additional_price;
                                            }
                                            $totalPrice = $unitPrice * $item->quantity;
                                        @endphp
                                        <div class="fw-bold mb-2 text-dark">{{ $storeCurrencySymbol }}{{ number_format($totalPrice) }}</div>
                                        <span class="text-muted small d-block mb-2" style="font-size: 0.75rem;">{{ $storeCurrencySymbol }}{{ number_format($unitPrice) }} each</span>
                                        
                                        <!-- Remove button -->
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 small text-decoration-none"><i class="bi bi-trash me-1"></i>Remove</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ url('/shop') }}" class="btn btn-link text-dark text-decoration-none small p-0"><i class="bi bi-arrow-left me-2"></i>Continue Shopping</a>
                                <form action="{{ route('cart.clear') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-danger text-decoration-none small p-0"><i class="bi bi-trash3 me-2"></i>Clear Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Totals Sidebar Summary -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px; z-index: 1;">
                            <h4 class="font-heading fs-5 pb-2 mb-4 border-bottom border-light">ORDER SUMMARY</h4>
                            
                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span class="small">Subtotal</span>
                                <span class="fw-semibold">{{ $storeCurrencySymbol }}{{ number_format($totals['subtotal']) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span class="small">Estimated Shipping</span>
                                <span class="fw-semibold">{{ $storeCurrencySymbol }}{{ number_format($storeShippingCharges) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 text-muted">
                                <span class="small">Tax ({{ $storeTaxPercentage }}%)</span>
                                @php
                                    $tax = ($totals['subtotal'] * $storeTaxPercentage) / 100;
                                    $grandTotal = $totals['subtotal'] + $tax + $storeShippingCharges;
                                @endphp
                                <span class="fw-semibold">{{ $storeCurrencySymbol }}{{ number_format($tax) }}</span>
                            </div>
                            
                            <div style="border-top: 1px dashed var(--color-gray-200); margin-bottom: 1.5rem;"></div>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold">Total Estimated</span>
                                <span class="fw-bold fs-5 text-dark">{{ $storeCurrencySymbol }}{{ number_format($grandTotal) }}</span>
                            </div>

                            <div class="d-grid mb-3">
                                <a href="{{ route('checkout.index') }}" class="btn-luxury-dark text-center py-3">PROCEED TO CHECKOUT</a>
                            </div>

                            <p class="text-center text-muted mb-0" style="font-size: 0.75rem;">Tax and shipping charges applied at checkout.</p>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </section>

@endsection
