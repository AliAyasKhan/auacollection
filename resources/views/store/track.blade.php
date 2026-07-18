@extends('layouts.store')

@section('title', 'Track Order - AUA Collection')

@section('content')

    <section class="py-5 bg-light" style="min-height: 70vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <div class="card border-0 shadow-sm rounded-4 p-5 mb-4 text-center">
                        <h1 class="font-serif mb-2 fs-2">Track Your Order</h1>
                        <p class="text-muted small mb-4">Input your order number below to check the real-time shipping progress.</p>
                        
                        <form action="{{ route('account.track') }}" method="GET">
                            <div class="input-group bg-white border border-light-subtle rounded-3 p-1 max-width-500 mx-auto">
                                <input type="text" name="order_number" class="form-control border-0 bg-transparent shadow-none font-monospace py-2" placeholder="e.g. AUA-XXXXXXXX-YYYYMMDD" value="{{ $orderNumber }}" required>
                                <button class="btn btn-dark border-0 px-4 rounded-3" type="submit">TRACK</button>
                            </div>
                        </form>
                    </div>

                    @if($error)
                        <div class="alert alert-danger border-0 rounded-3 text-center shadow-sm">{{ $error }}</div>
                    @endif

                    @if($order)
                        <!-- Order Status Card Details -->
                        <div class="card border-0 shadow-sm rounded-4 p-5">
                            
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light">
                                <div class="text-start">
                                    <span class="text-muted small d-block mb-1">ORDER NUMBER</span>
                                    <h5 class="font-monospace fw-bold mb-0 text-dark">{{ $order->order_number }}</h5>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted small d-block mb-1">CURRENT STATUS</span>
                                    <span class="badge bg-{{ $order->status_color }} py-2 px-3">{{ strtoupper($order->status) }}</span>
                                </div>
                            </div>

                            <!-- Cancelled / Returned Alert if applicable -->
                            @if(in_array($order->status, ['Cancelled', 'Returned', 'Refunded']))
                                <div class="alert alert-warning border-0 rounded-3 mb-4 text-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    This order has been <strong>{{ strtoupper($order->status) }}</strong>. Please contact support for any questions.
                                </div>
                            @else
                                <!-- Tracking Timeline Graph (Vertical steps for mobile & desktop) -->
                                @php
                                    $statuses = ['Pending', 'Confirmed', 'Packing', 'Shipped', 'Out For Delivery', 'Delivered'];
                                    
                                    // Custom matching mapping to highlight complete steps
                                    $statusIndex = array_search($order->status, $statuses);
                                    if ($order->status === 'Payment Verified') {
                                        $statusIndex = 0; // treat as pending/confirmed transition
                                    } elseif ($order->status === 'Ready To Ship') {
                                        $statusIndex = 2; // treat as packing done
                                    }
                                    if ($statusIndex === false) $statusIndex = 0;
                                @endphp

                                <div class="py-4">
                                    <div class="row text-center justify-content-between g-3 position-relative d-md-flex d-none mb-3">
                                        <!-- Line Connector behind -->
                                        <div class="position-absolute top-50 start-0 w-100 bg-secondary-subtle" style="height: 2px; z-index: 1; transform: translateY(-50%);"></div>
                                        <div class="position-absolute top-50 start-0 bg-dark" style="height: 2px; z-index: 1; transform: translateY(-50%); width: {{ ($statusIndex / 5) * 100 }}%; transition: width 0.5s ease;"></div>

                                        @foreach($statuses as $idx => $st)
                                            <div class="col position-relative" style="z-index: 2;">
                                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center border-2 border shadow-sm" 
                                                     style="width: 40px; height: 40px; 
                                                            background-color: {{ $idx <= $statusIndex ? 'var(--color-primary-dark)' : 'white' }}; 
                                                            border-color: {{ $idx <= $statusIndex ? 'var(--color-primary-dark)' : 'var(--color-gray-200)' }}; 
                                                            color: {{ $idx <= $statusIndex ? 'white' : 'var(--color-gray-500)' }};">
                                                    @if($idx < $statusIndex || $order->status === 'Delivered')
                                                        <i class="bi bi-check-lg small"></i>
                                                    @else
                                                        <span class="small font-monospace fw-bold">{{ $idx + 1 }}</span>
                                                    @endif
                                                </div>
                                                <span class="d-block small mt-2 fw-semibold text-uppercase {{ $idx <= $statusIndex ? 'text-dark' : 'text-muted' }}" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ $st }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Vertical list for mobile view -->
                                    <ul class="list-unstyled d-md-none border-start border-2 border-light-subtle ps-4 ms-2 text-start">
                                        @foreach($statuses as $idx => $st)
                                            <li class="mb-4 position-relative">
                                                <div class="position-absolute rounded-circle d-flex align-items-center justify-content-center border-2" 
                                                     style="left: -33px; top: 0; width: 22px; height: 22px; 
                                                            background-color: {{ $idx <= $statusIndex ? 'var(--color-primary-dark)' : 'white' }}; 
                                                            border-color: {{ $idx <= $statusIndex ? 'var(--color-primary-dark)' : 'var(--color-gray-200)' }}; 
                                                            color: {{ $idx <= $statusIndex ? 'white' : 'var(--color-gray-500)' }};">
                                                    @if($idx < $statusIndex || $order->status === 'Delivered')
                                                        <i class="bi bi-check-lg" style="font-size: 0.55rem;"></i>
                                                    @endif
                                                </div>
                                                <h6 class="fs-7 mb-1 {{ $idx <= $statusIndex ? 'text-dark fw-bold' : 'text-muted' }}">{{ $st }}</h6>
                                                <span class="small text-muted">{{ $idx <= $statusIndex ? 'Processed' : 'Pending' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mt-4 pt-3 border-top border-light text-start small text-muted">
                                <p class="mb-1"><i class="bi bi-info-circle-fill me-2"></i><strong>Delivery Details:</strong> Standard delivery inside Pakistan takes 3 to 5 business days.</p>
                                <p class="mb-0"><i class="bi bi-truck me-2"></i><strong>Courier:</strong> BlueEx / Leopards Logistics (Tracking Number: {{ $order->tracking_number ?: 'Pending Assignment' }})</p>
                            </div>

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection
