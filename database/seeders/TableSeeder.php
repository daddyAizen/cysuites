<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        Table::insert([
            ['name' => 'Table 1', 'seats' => 4, 'status' => 'available'],
            ['name' => 'Table 2', 'seats' => 2, 'status' => 'available'],
            ['name' => 'Table 3', 'seats' => 6, 'status' => 'unavailable'],
            ['name' => 'VIP Table', 'seats' => 8, 'status' => 'available'],
        ]);
    }
}
