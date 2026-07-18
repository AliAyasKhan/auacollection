<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'link',
        'button_text',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
        'status' => 'boolean',
    ];

    protected $appends = [
        'image_url',
    ];

    /**
     * Public URL for the banner image (storage uploads + public assets).
     */
    public function getImageUrlAttribute(): string
    {
        return ProductImage::resolveUrl($this->image_path);
    }

    public function deleteImageFile(): void
    {
        if (! $this->image_path) {
            return;
        }

        if (str_starts_with($this->image_path, 'banners/') || str_starts_with($this->image_path, 'products/')) {
            Storage::disk('public')->delete($this->image_path);
        }
    }
}
