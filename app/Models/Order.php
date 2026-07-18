<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'discount',
        'shipping_charges',
        'tax',
        'total',
        'status',
        'notes',
        'tracking_number',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_charges' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(ShippingAddress::class);
    }

    // Status helper colors for UI badges
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Pending' => 'secondary',
            'Payment Verified' => 'info',
            'Confirmed' => 'primary',
            'Packing' => 'warning',
            'Ready To Ship' => 'info',
            'Shipped' => 'info',
            'Out For Delivery' => 'warning',
            'Delivered' => 'success',
            'Cancelled' => 'danger',
            'Returned' => 'dark',
            'Refunded' => 'danger',
            default => 'secondary'
        };
    }
}
