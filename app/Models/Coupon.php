<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type', // fixed, percent
        'value',
        'description',
        'min_spend',
        'max_discount',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'expiry_date' => 'date',
        'status' => 'boolean',
    ];

    // Helper to verify validity
    public function isValidFor($subtotal)
    {
        if (!$this->status) {
            return false;
        }

        if ($this->expiry_date && $this->expiry_date->isPast()) {
            return false;
        }

        if ($this->min_spend && $subtotal < $this->min_spend) {
            return false;
        }

        return true;
    }

    // Helper to calculate discount amount
    public function calculateDiscount($subtotal)
    {
        if (!$this->isValidFor($subtotal)) {
            return 0;
        }

        if ($this->type === 'percent') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                return $this->max_discount;
            }
            return $discount;
        }

        return min($this->value, $subtotal);
    }
}
