<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
            RoleSeeder::class,
            RoomSeeder::class,
            GuestSeeder::class,
            TableSeeder::class,
            DiscountSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'employee_id' => 'CY001',
            'role_id' => 1,
            'password' => bcrypt('password'),
            'discount_id' => 1
        ]);
    }
}
