@extends('layouts.admin')

@section('title', 'Add Banner - AUA Collection')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-link text-dark p-0 text-decoration-none mb-2"><i class="bi bi-arrow-left"></i> Back to Banners</a>
    <h4 class="font-serif fw-bold text-uppercase letter-spacing-1">Add Homepage Banner Slide</h4>
</div>

<div class="card card-luxury p-4 max-width-800 mx-auto">
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="image" class="form-label small fw-bold">Banner Slide Image *</label>
            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif" required>
            <span class="text-muted small fs-7 mt-1 d-block">Recommended size: 1920x800px. JPG/PNG/WEBP max 5MB.</span>
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="title" class="form-label small fw-bold">Banner Overlay Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="subtitle" class="form-label small fw-bold">Banner Overlay Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle') }}">
                @error('subtitle')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="button_text" class="form-label small fw-bold">Button Call-To-Action Text</label>
                <input type="text" name="button_text" id="button_text" class="form-control @error('button_text') is-invalid @enderror" value="{{ old('button_text') }}" placeholder="e.g. SHOP NOW">
                @error('button_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="link" class="form-label small fw-bold">Button Target link / URL</label>
                <input type="text" name="link" id="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link') }}" placeholder="/shop or custom URL">
                @error('link')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="order" class="form-label small fw-bold">Display Order Sequence</label>
                <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}" min="0">
                @error('order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-bold" for="status">Active (Display in home slider)</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-2">
                <button type="submit" class="btn btn-luxury-gold w-100 py-3 fw-bold">CREATE BANNER</button>
            </div>
            <div class="col-md-6">
                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-dark w-100 py-3">CANCEL</a>
            </div>
        </div>
    </form>
</div>
@endsection
