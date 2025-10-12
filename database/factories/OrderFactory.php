<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => null,     
            'guest_id' => null,
            'status' => 'pending',
            'total' => 0,
        ];
    }
}
