@extends('layouts.store')

@section('title', 'My Orders - AUA Collection')

@section('content')

    <!-- Header -->
    <section class="py-4 bg-light">
        <div class="container">
            <h1 class="font-serif mb-0 fs-3">My Orders</h1>
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

                <!-- Orders List -->
                <div class="col-md-9">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h4 class="font-heading fs-5 pb-2 mb-4 border-bottom border-light">ORDER HISTORY</h4>
                        
                        @if($orders->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
                                <h5 class="font-serif">No Orders Yet</h5>
                                <p class="text-muted small">You haven't placed any orders with us yet.</p>
                                <a href="{{ url('/shop') }}" class="btn-luxury-dark btn-sm mt-2">SHOP NOW</a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr class="small text-muted font-heading">
                                            <th class="border-0">ORDER NUMBER</th>
                                            <th class="border-0">DATE</th>
                                            <th class="border-0">TOTAL</th>
                                            <th class="border-0">PAYMENT</th>
                                            <th class="border-0">STATUS</th>
                                            <th class="border-0 text-end">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @foreach($orders as $order)
                                            <tr>
                                                <td class="fw-bold font-monospace">{{ $order->order_number }}</td>
                                                <td>{{ $order->created_at->format('d M, Y') }}</td>
                                                <td class="fw-semibold text-dark">{{ $storeCurrencySymbol }}{{ number_format($order->total) }}</td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ $order->payment->payment_method ?? 'COD' }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $order->status_color }} text-wrap py-1.5 px-2.5">
                                                        {{ strtoupper($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('account.order_detail', $order->order_number) }}" class="btn btn-outline-dark btn-sm rounded-3 py-1 px-2.5">VIEW</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                {{ $orders->links() }}
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
