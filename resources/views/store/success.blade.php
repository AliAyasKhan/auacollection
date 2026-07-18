@extends('layouts.store')

@section('title', 'Order Success - AUA Collection')

@section('content')

    <section class="py-5 text-center">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-sm-8">
                    
                    <div class="mb-4">
                        <i class="bi bi-check2-circle display-1 text-success"></i>
                    </div>

                    <h1 class="font-serif mb-3">Thank You For Your Order</h1>
                    <p class="text-muted lh-lg mb-4">
                        Your order has been securely placed. We have sent a confirmation email containing your details and will process your package shortly.
                    </p>

                    <div class="card border-0 bg-light p-4 rounded-4 mb-4">
                        <span class="text-muted small uppercase letter-spacing-1 d-block mb-1">YOUR ORDER NUMBER:</span>
                        <h4 class="font-monospace fw-bold text-dark mb-0">{{ $orderNumber }}</h4>
                    </div>

                    <p class="small text-muted mb-4">
                        You can trace your shipment progress by inputting your order number on our tracking page.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('account.track', ['order_number' => $orderNumber]) }}" class="btn-luxury-dark">TRACK ORDER</a>
                        <a href="{{ url('/shop') }}" class="btn-luxury-outline">CONTINUE SHOPPING</a>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
