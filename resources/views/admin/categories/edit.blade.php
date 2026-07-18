@extends('layouts.admin')

@section('title', 'Edit Category - AUA Collection')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-link text-dark p-0 text-decoration-none mb-2"><i class="bi bi-arrow-left"></i> Back to Categories</a>
    <h4 class="font-serif fw-bold text-uppercase letter-spacing-1">Edit Category: {{ $category->name }}</h4>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-4">{{ session('success') }}</div>
@endif
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
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" id="category-edit-form">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label small fw-bold">Category Name *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 p-3 border rounded-3 bg-light">
                    <label class="form-label small fw-bold d-block mb-2">Category Image (Homepage)</label>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <img
                            src="{{ $category->image_url }}?v={{ $category->updated_at?->timestamp ?? time() }}"
                            alt="{{ $category->name }}"
                            id="category-preview"
                            class="img-thumbnail rounded-3"
                            style="width: 120px; height: 150px; object-fit: cover;"
                        >
                        <div class="small text-muted">
                            <p class="mb-1">Shown in <strong>Shop by Category</strong> on the homepage.</p>
                            <p class="mb-0">Pick any image from your computer below, then click <strong>Save Changes</strong>.</p>
                        </div>
                    </div>

                    <label for="image" class="form-label small fw-bold">Choose Your Image</label>
                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="form-control @error('image') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif"
                    >
                    <span class="text-muted small fs-7 mt-1 d-block">Upload your own JPG, PNG, WEBP or GIF — max 5MB. This replaces the current image.</span>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-bold">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $category->status) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-bold" for="status">Active Category</label>
                </div>

                <button type="submit" class="btn btn-luxury-gold w-100 py-3 fw-bold">SAVE CHANGES</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('image')?.addEventListener('change', function (e) {
        const file = e.target.files?.[0];
        const preview = document.getElementById('category-preview');
        if (!file || !preview) return;
        preview.src = URL.createObjectURL(file);
    });
</script>
@endpush
