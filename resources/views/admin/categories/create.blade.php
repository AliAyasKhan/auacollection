@extends('layouts.admin')

@section('title', 'Create Category - AUA Collection')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-link text-dark p-0 text-decoration-none mb-2"><i class="bi bi-arrow-left"></i> Back to Categories</a>
    <h4 class="font-serif fw-bold text-uppercase letter-spacing-1">Add New Category</h4>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 rounded-3 mb-4">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card card-luxury p-4">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label small fw-bold">Category Name *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 p-3 border rounded-3 bg-light">
                    <label for="image" class="form-label small fw-bold">Category Image</label>
                    <div class="mb-3 d-none" id="category-preview-wrap">
                        <img id="category-preview" alt="Preview" class="img-thumbnail rounded-3" style="width: 120px; height: 150px; object-fit: cover;">
                    </div>
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif">
                    <span class="text-muted small fs-7 mt-1 d-block">Optional. Upload your own image for homepage Shop by Category. Max 5MB.</span>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-bold">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-bold" for="status">Active Category</label>
                </div>

                <button type="submit" class="btn btn-luxury-gold w-100 py-3 fw-bold">CREATE CATEGORY</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('image')?.addEventListener('change', function (e) {
        const file = e.target.files?.[0];
        const wrap = document.getElementById('category-preview-wrap');
        const preview = document.getElementById('category-preview');
        if (!file || !wrap || !preview) return;
        wrap.classList.remove('d-none');
        preview.src = URL.createObjectURL(file);
    });
</script>
@endpush
