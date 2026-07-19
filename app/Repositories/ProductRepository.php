<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::with(['category', 'brand', 'collection', 'primaryImage'])->get();
    }

    public function find($id)
    {
        return Product::with(['category', 'brand', 'collection', 'images', 'variants.color', 'variants.size'])->findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Product::with([
            'category', 'brand', 'collection', 'images', 
            'variants.color', 'variants.size', 
            'reviews.user'
        ])->where('slug', $slug)->where('status', true)->firstOrFail();
    }

    public function searchAndFilter(array $filters, $perPage = 12)
    {
        $query = Product::with(['category', 'brand', 'collection', 'primaryImage'])
            ->where('status', true);

        // Filter by Category
        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }

        // Filter by Collection
        if (!empty($filters['collection'])) {
            $query->whereHas('collection', function ($q) use ($filters) {
                $q->where('slug', $filters['collection']);
            });
        }

        // Filter by Brand
        if (!empty($filters['brand'])) {
            $query->whereHas('brand', function ($q) use ($filters) {
                $q->where('slug', $filters['brand']);
            });
        }

        // Filter by Price range
        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $query->where(function($q) use ($filters) {
                $q->whereRaw('COALESCE(discount_price, price) >= ?', [$filters['price_min']]);
            });
        }
        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $query->where(function($q) use ($filters) {
                $q->whereRaw('COALESCE(discount_price, price) <= ?', [$filters['price_max']]);
            });
        }

        // Filter by Colors (via Variants)
        if (!empty($filters['colors']) && is_array($filters['colors'])) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->whereIn('color_id', $filters['colors']);
            });
        }

        // Filter by Sizes (via Variants)
        if (!empty($filters['sizes']) && is_array($filters['sizes'])) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->whereIn('size_id', $filters['sizes']);
            });
        }

        // Search by keyword
        if (!empty($filters['search'])) {
            $keyword = $filters['search'];
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('short_description', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('SKU', 'LIKE', "%{$keyword}%");
            });
        }

        if (! empty($filters['new_arrival'])) {
            $query->where('new_arrival', true);
        }

        if (! empty($filters['sale'])) {
            $query->where(function ($q) {
                $q->where('sale_product', true)
                    ->orWhereNotNull('discount_price');
            });
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'newest';
        switch ($sortBy) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('name', 'DESC');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getFeatured($limit = 8)
    {
        return Product::with(['category', 'brand', 'primaryImage'])
            ->where('status', true)
            ->where('featured', true)
            ->limit($limit)
            ->latest()
            ->get();
    }

    public function getNewArrivals($limit = 8)
    {
        return Product::with(['category', 'brand', 'primaryImage'])
            ->where('status', true)
            ->where('new_arrival', true)
            ->limit($limit)
            ->latest()
            ->get();
    }

    public function getSaleProducts($limit = 8)
    {
        return Product::with(['category', 'brand', 'primaryImage'])
            ->where('status', true)
            ->where('sale_product', true)
            ->limit($limit)
            ->latest()
            ->get();
    }

    public function getAllCategories()
    {
        return Category::where('status', true)->get();
    }

    public function getAllCollections()
    {
        return Collection::where('status', true)->get();
    }

    public function getAllBrands()
    {
        return Brand::where('status', true)->get();
    }

    public function getAllColors()
    {
        return Color::all();
    }

    public function getAllSizes()
    {
        return Size::all();
    }
}
