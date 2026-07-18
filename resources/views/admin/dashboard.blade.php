@extends('layouts.admin')

@section('title', 'Admin Dashboard - AUA Collection')

@section('content')
<div class="row g-4 mb-4">
    <!-- Today's Sales Card -->
    <div class="col-md-3">
        <div class="card card-luxury card-stats gold p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small text-uppercase mb-2">Today's Sales</h6>
                    <h3 class="mb-0 text-gold fw-bold">Rs. {{ number_format($stats['today_sales'], 2) }}</h3>
                </div>
                <div class="fs-1 text-gold"><i class="bi bi-wallet2"></i></div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Revenue Card -->
    <div class="col-md-3">
        <div class="card card-luxury card-stats p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small text-uppercase mb-2">Monthly Revenue</h6>
                    <h3 class="mb-0 fw-bold">Rs. {{ number_format($stats['monthly_revenue'], 2) }}</h3>
                </div>
                <div class="fs-1 text-dark"><i class="bi bi-currency-dollar"></i></div>
            </div>
        </div>
    </div>

    <!-- Total Orders Card -->
    <div class="col-md-3">
        <div class="card card-luxury card-stats p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small text-uppercase mb-2">Total Orders</h6>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_orders'] }}</h3>
                    <span class="text-muted small"><strong class="text-warning">{{ $stats['pending_orders'] }}</strong> pending</span>
                </div>
                <div class="fs-1 text-dark"><i class="bi bi-cart3"></i></div>
            </div>
        </div>
    </div>

    <!-- Inventory Stock Alert Card -->
    <div class="col-md-3">
        <div class="card card-luxury card-stats p-4" style="border-left-color: {{ $stats['total_low_stock'] > 0 ? '#dc3545' : '#198754' }};">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small text-uppercase mb-2">Stock Alerts</h6>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_low_stock'] }}</h3>
                    <span class="text-muted small">{{ $stats['total_low_stock'] > 0 ? 'Requires attention' : 'Inventory healthy' }}</span>
                </div>
                <div class="fs-1 text-{{ $stats['total_low_stock'] > 0 ? 'danger' : 'success' }}"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Secondary Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-luxury p-3 text-center">
            <h6 class="text-muted small text-uppercase mb-1">Total Customers</h6>
            <h4 class="mb-0 fw-semibold">{{ $stats['total_customers'] }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-luxury p-3 text-center">
            <h6 class="text-muted small text-uppercase mb-1">Total Products</h6>
            <h4 class="mb-0 fw-semibold">{{ $stats['total_products'] }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-luxury p-3 text-center">
            <h6 class="text-muted small text-uppercase mb-1">Completed Deliveries</h6>
            <h4 class="mb-0 fw-semibold text-success">{{ $stats['completed_orders'] }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart -->
    <div class="col-lg-8">
        <div class="card card-luxury p-4">
            <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase letter-spacing-1">Weekly Sales Statistics</h5>
            <div style="height: 300px; position: relative;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="col-lg-4">
        <div class="card card-luxury p-4">
            <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase letter-spacing-1">Top Selling Products</h5>
            <div class="list-group list-group-flush">
                @forelse($stats['top_selling'] as $item)
                    @if($item->product)
                        <div class="list-group-item bg-transparent border-0 px-0 py-3 d-flex align-items-center gap-3">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="mb-1 text-truncate small fw-semibold">{{ $item->product->name }}</h6>
                                <span class="text-muted small">Rs. {{ number_format($item->product->price, 2) }}</span>
                            </div>
                            <span class="badge bg-dark rounded-pill fw-bold font-monospace">{{ $item->total_qty }} sold</span>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-4 text-muted small">No product sales recorded yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Latest Orders -->
<div class="card card-luxury p-4 mt-4">
    <h5 class="font-serif mb-4 fs-6 fw-bold text-uppercase letter-spacing-1">Latest Customer Orders</h5>
    <div class="table-responsive">
        <table class="table table-hover table-luxury align-middle">
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['latest_orders'] as $order)
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
                                    <span class="badge bg-success text-white badge-luxury small ms-1">Paid</span>
                                @else
                                    <span class="badge bg-danger text-white badge-luxury small ms-1">Unpaid</span>
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
                        <td colspan="7" class="text-center py-4 text-muted">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($stats['chart_dates']) !!},
            datasets: [{
                label: 'Revenue (Rs.)',
                data: {!! json_encode($stats['chart_sales']) !!},
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                borderColor: '#D4AF37',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#111111',
                pointBorderColor: '#D4AF37',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rs. ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
