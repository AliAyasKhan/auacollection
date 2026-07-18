<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $appends = [
        'image_url',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Public URL for category image (or placeholder).
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return ProductImage::resolveUrl($this->image);
        }

        // Fallback: first product primary image in this category
        $product = $this->relationLoaded('products')
            ? $this->products->first()
            : $this->products()->with('primaryImage')->first();

        if ($product) {
            return $product->image_url;
        }

        return asset('assets/images/placeholder.jpg');
    }

    public function hasOwnImage(): bool
    {
        return filled($this->image);
    }

    public function deleteImageFile(): void
    {
        if ($this->image && str_starts_with($this->image, 'categories/')) {
            Storage::disk('public')->delete($this->image);
        }
    }
}
