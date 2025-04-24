<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type', // 'percentage' or 'fixed'
        'discount_value',
        'expires_at',
        'usage_limit',
        'used_count',
    ];

    protected $casts = [
        'expires_at' => 'datetime', // Ensure expires_at is treated as a datetime
    ];

    public function isValid()
    {
        return $this->used_count < $this->usage_limit && (!$this->expires_at || $this->expires_at->isFuture());
    }
}
