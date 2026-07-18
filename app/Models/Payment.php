<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'status',
        'proof_image',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Pending' => 'warning',
            'Approved' => 'success',
            'Rejected' => 'danger',
            default => 'secondary'
        };
    }
}
