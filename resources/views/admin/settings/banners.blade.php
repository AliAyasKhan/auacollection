@extends('layouts.admin')

@section('title', 'Manage Banners - AUA Collection')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif mb-1 fw-bold text-uppercase letter-spacing-1">Homepage Promo Banners</h4>
        <p class="text-muted small mb-0">Total {{ $banners->count() }} active/inactive carousel slides defined</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-luxury-gold"><i class="bi bi-plus-lg me-1"></i> ADD BANNER</a>
</div>

<div class="card card-luxury p-4">
    <div class="table-responsive">
        <table class="table table-hover table-luxury align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Slide Content</th>
                    <th>Link Target</th>
                    <th>Order Sequence</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td style="width: 150px;">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="rounded-3" style="width: 130px; height: 60px; object-fit: cover;">
                            <div class="text-muted text-truncate" style="font-size:0.65rem; max-width:130px;">{{ $banner->image_path }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $banner->title ?: 'No Title Overlay' }}</div>
                            <span class="text-muted small">{{ $banner->subtitle ?: 'No subtitle' }}</span>
                            @if($banner->button_text)
                                <div class="mt-1"><span class="badge bg-light text-dark border small">Button: {{ $banner->button_text }}</span></div>
                            @endif
                        </td>
                        <td>
                            @if($banner->link)
                                <span class="font-monospace small text-truncate d-inline-block" style="max-width: 180px;">{{ $banner->link }}</span>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td class="font-monospace fw-bold">{{ $banner->order }}</td>
                        <td>
                            @if($banner->status)
                                <span class="badge bg-success-subtle text-success badge-luxury border border-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger badge-luxury border border-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner slide?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-images fs-1 d-block mb-3"></i>
                            No banners uploaded yet. Banners populate the home hero slider.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
