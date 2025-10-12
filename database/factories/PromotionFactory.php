<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Promotion;

class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'start_date' => now(),
            'end_date' => now()->addWeek(),
        ];
    }
}
