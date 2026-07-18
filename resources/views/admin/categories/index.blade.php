@extends('layouts.admin')

@section('title', 'Manage Categories - AUA Collection')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif mb-1 fw-bold text-uppercase letter-spacing-1">Product Categories</h4>
        <p class="text-muted small mb-0">Total {{ $categories->total() }} categories defined</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-luxury-gold"><i class="bi bi-plus-lg me-1"></i> ADD CATEGORY</a>
</div>

<div class="card card-luxury p-4">
    <div class="table-responsive">
        <table class="table table-hover table-luxury align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Category Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td style="width:80px;">
                            <img src="{{ $category->image_url }}?v={{ $category->updated_at?->timestamp }}" alt="{{ $category->name }}" class="rounded-2" style="width:56px;height:70px;object-fit:cover;">
                        </td>
                        <td class="fw-semibold text-dark">{{ $category->name }}</td>
                        <td class="font-monospace small text-muted">{{ $category->slug }}</td>
                        <td>{{ Str::limit($category->description, 50, '...') ?: 'No description' }}</td>
                        <td>
                            @if($category->status)
                                <span class="badge bg-success-subtle text-success badge-luxury border border-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger badge-luxury border border-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-tags fs-1 d-block mb-3"></i>
                            No categories created yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
