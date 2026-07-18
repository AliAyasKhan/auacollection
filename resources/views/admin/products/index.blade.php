@extends('layouts.admin')

@section('title', 'Manage Products - AUA Collection')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif mb-1 fw-bold text-uppercase letter-spacing-1">Products Catalog</h4>
        <p class="text-muted small mb-0">Total {{ $products->total() }} products found in database</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-luxury-gold"><i class="bi bi-plus-lg me-1"></i> ADD PRODUCT</a>
</div>

<div class="card card-luxury p-4">
    <!-- Filter and Search Row -->
    <div class="row g-3 mb-4 justify-content-between">
        <div class="col-md-4">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, SKU..." value="{{ request('search') }}">
                    <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                    <th>Image</th>
                    <th>Product details</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price (Rs.)</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-3" style="width: 55px; height: 55px; object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $product->name }}</div>
                            <div class="d-flex gap-2 mt-1">
                                @if($product->featured)
                                    <span class="badge bg-dark badge-luxury" style="font-size: 0.6rem;">Featured</span>
                                @endif
                                @if($product->new_arrival)
                                    <span class="badge bg-info text-dark badge-luxury" style="font-size: 0.6rem;">New</span>
                                @endif
                                @if($product->sale_product)
                                    <span class="badge bg-warning text-dark badge-luxury" style="font-size: 0.6rem;">Sale</span>
                                @endif
                            </div>
                        </td>
                        <td class="font-monospace small">{{ $product->SKU }}</td>
                        <td>
                            <span class="badge bg-light text-dark text-uppercase border">{{ $product->category ? $product->category->name : 'Uncategorized' }}</span>
                        </td>
                        <td>
                            @if($product->discount_price)
                                <div class="text-dark fw-bold">Rs. {{ number_format($product->discount_price, 2) }}</div>
                                <div class="text-muted text-decoration-line-through small">Rs. {{ number_format($product->price, 2) }}</div>
                            @else
                                <div class="text-dark fw-bold">Rs. {{ number_format($product->price, 2) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($product->stock <= 5)
                                <span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> {{ $product->stock }} (Low)</span>
                            @else
                                <span class="text-success fw-semibold">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->status)
                                <span class="badge bg-success-subtle text-success badge-luxury border border-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger badge-luxury border border-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to soft delete this product? It will preserve order histories.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                            No products created in database yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection
