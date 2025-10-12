<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        Discount::create([
            'name' => 'Staff Discount',
            'percentage' => 35,
        ]);
    }
}
