<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuestFactory extends Factory
{
    protected $model = \App\Models\Guest::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'room_name' => 'Ex',  
            'room_code' => 'RM' . $this->faker->numberBetween(100, 999),
            'password' => bcrypt('password'),
        ];
    }
}

