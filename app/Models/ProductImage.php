<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    protected $appends = [
        'url',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Public URL for this image (supports storage uploads and public assets).
     */
    public function getUrlAttribute(): string
    {
        return self::resolveUrl($this->image_path);
    }

    public static function resolveUrl(?string $path): string
    {
        $fallback = asset('assets/images/placeholder.jpg');

        if (! $path) {
            return $fallback;
        }

        // Uploaded files on the public disk
        if (
            str_starts_with($path, 'products/')
            || str_starts_with($path, 'banners/')
            || str_starts_with($path, 'payments/')
            || str_starts_with($path, 'categories/')
            || str_starts_with($path, 'settings/')
        ) {
            return asset('storage/'.$path);
        }

        // Public asset paths (assets/images/...)
        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return $fallback;
    }

    public function isStoredUpload(): bool
    {
        return $this->image_path
            && $this->image_path !== 'assets/images/placeholder.jpg'
            && ! str_starts_with($this->image_path, 'assets/');
    }

    public function deleteFile(): void
    {
        if ($this->isStoredUpload()) {
            Storage::disk('public')->delete($this->image_path);
        }
    }
}
