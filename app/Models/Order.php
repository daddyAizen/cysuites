<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'user_id',
        'status',
    ];

    protected $casts = [
        'total' => 'float',
    ];


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function updateTotal()
{
    $this->load('orderItems.menu');

    $total = $this->orderItems->sum(function($item) {
        return $item->menu ? $item->menu->price * $item->quantity : 0;
    });

    if ($this->user && $this->user->role === 'staff') {
        $total *= 0.9;
    }

    $this->total = $total;
    $this->save();
}


    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
    public function user()
{
    return $this->belongsTo(User::class);
}

    protected static function booted()
{
    static::deleting(function ($order) {
        $order->orderItems()->delete();
    });
}
}

