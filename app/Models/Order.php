<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'status',
    ];

    // Define relationship to order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    protected static function booted()
{
    static::deleting(function ($order) {
        $order->orderItems()->delete();
    });
}
}

