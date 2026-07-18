@extends('layouts.admin')

@section('title', 'Edit Coupon - AUA Collection')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-link text-dark p-0 text-decoration-none mb-2"><i class="bi bi-arrow-left"></i> Back to Coupons</a>
    <h4 class="font-serif fw-bold text-uppercase letter-spacing-1">Edit Coupon: {{ $coupon->code }}</h4>
</div>

<div class="card card-luxury p-4 max-width-800 mx-auto">
    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="code" class="form-label small fw-bold">Coupon Code *</label>
                <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code) }}" placeholder="e.g. SUMMER26" style="text-transform: uppercase;" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="type" class="form-label small fw-bold">Discount Type *</label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (Rs.)</option>
                    <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="value" class="form-label small fw-bold">Discount Value *</label>
                <input type="number" step="0.01" name="value" id="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $coupon->value) }}" required>
                @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="expiry_date" class="form-label small fw-bold">Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date', $coupon->expiry_date) }}">
                @error('expiry_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="min_spend" class="form-label small fw-bold">Minimum Spend Requirement (Rs.)</label>
                <input type="number" step="0.01" name="min_spend" id="min_spend" class="form-control @error('min_spend') is-invalid @enderror" value="{{ old('min_spend', $coupon->min_spend) }}">
                @error('min_spend')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="max_discount" class="form-label small fw-bold">Maximum Discount Limit (Rs.)</label>
                <input type="number" step="0.01" name="max_discount" id="max_discount" class="form-control @error('max_discount') is-invalid @enderror" value="{{ old('max_discount', $coupon->max_discount) }}">
                <span class="text-muted small fs-7">Only applicable for 'Percentage' discount type.</span>
                @error('max_discount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label small fw-bold">Coupon Description</label>
            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $coupon->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $coupon->status) ? 'checked' : '' }}>
            <label class="form-check-label small fw-bold" for="status">Active Coupon</label>
        </div>

        <div class="row">
            <div class="col-md-6 mb-2">
                <button type="submit" class="btn btn-luxury-gold w-100 py-3 fw-bold">SAVE CHANGES</button>
            </div>
            <div class="col-md-6">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-dark w-100 py-3">CANCEL</a>
            </div>
        </div>
    </form>
</div>
@endsection
