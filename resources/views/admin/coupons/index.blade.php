@extends('layouts.admin')

@section('title', 'Manage Coupons - AUA Collection')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif mb-1 fw-bold text-uppercase letter-spacing-1">Discount Coupons</h4>
        <p class="text-muted small mb-0">Total {{ $coupons->total() }} coupon codes found</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-luxury-gold"><i class="bi bi-plus-lg me-1"></i> ADD COUPON</a>
</div>

<div class="card card-luxury p-4">
    <div class="table-responsive">
        <table class="table table-hover table-luxury align-middle">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Discount Details</th>
                    <th>Spend Minimum</th>
                    <th>Max Discount</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td class="font-monospace fw-bold text-dark fs-6">{{ $coupon->code }}</td>
                        <td>
                            @if($coupon->type === 'percent')
                                <span class="fw-semibold text-gold">{{ $coupon->value }}% OFF</span>
                            @else
                                <span class="fw-semibold text-dark">Rs. {{ number_format($coupon->value, 2) }} OFF</span>
                            @endif
                            <div class="text-muted small mt-1">{{ $coupon->description ?: 'No description' }}</div>
                        </td>
                        <td>
                            @if($coupon->min_spend)
                                <span>Rs. {{ number_format($coupon->min_spend, 2) }}</span>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->type === 'percent' && $coupon->max_discount)
                                <span>Rs. {{ number_format($coupon->max_discount, 2) }}</span>
                            @else
                                <span class="text-muted small">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->expiry_date)
                                @php
                                    $isExpired = \Carbon\Carbon::parse($coupon->expiry_date)->isPast();
                                @endphp
                                <span class="{{ $isExpired ? 'text-danger fw-bold' : '' }}">
                                    {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d M Y') }}
                                    @if($isExpired) (Expired) @endif
                                </span>
                            @else
                                <span class="text-muted small">Never</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->status)
                                <span class="badge bg-success-subtle text-success badge-luxury border border-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger badge-luxury border border-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-ticket-perforated fs-1 d-block mb-3"></i>
                            No coupons created yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $coupons->links() }}
    </div>
</div>
@endsection
