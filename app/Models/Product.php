<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'collection_id',
        'brand_id',
        'name',
        'slug',
        'image',
        'short_description',
        'description',
        'price',
        'discount_price',
        'SKU',
        'barcode',
        'stock',
        'weight',
        'status',
        'featured',
        'new_arrival',
        'sale_product',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'status' => 'boolean',
        'featured' => 'boolean',
        'new_arrival' => 'boolean',
        'sale_product' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true)->withDefault([
            'image_path' => 'assets/images/placeholder.jpg',
        ]);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', true);
    }

    /**
     * Primary image URL for views.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return ProductImage::resolveUrl($this->image);
        }

        return $this->primaryImage->url;
    }

    /**
     * Keep products.image in sync with the primary gallery image.
     */
    public function syncPrimaryImageColumn(?string $path = null): void
    {
        $path ??= $this->images()->where('is_primary', true)->value('image_path')
            ?? $this->images()->value('image_path')
            ?? 'assets/images/placeholder.jpg';

        $this->forceFill(['image' => $path])->saveQuietly();
    }

    public function getActivePriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public function getHasDiscountAttribute()
    {
        return ! is_null($this->discount_price) && $this->discount_price < $this->price;
    }
}
