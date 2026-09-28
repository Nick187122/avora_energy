<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'order_number', 'customer_name', 'email', 'phone', 'address', 'city',
        'payment_method', 'status', 'subtotal', 'total', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public const STATUSES = ['pending', 'processing', 'completed', 'cancelled'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'SSA-' . strtoupper(uniqid());
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'warning',
        };
    }
}