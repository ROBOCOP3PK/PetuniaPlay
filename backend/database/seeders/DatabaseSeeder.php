<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AnimalSectionSeeder::class, // DEBE ejecutarse antes de CategorySeeder
            CategorySeeder::class,
            ProductSeeder::class,
            CatProductSeeder::class, // Productos específicos para gatos
            BlogCategorySeeder::class,
            CouponSeeder::class,
            LoyaltySeeder::class,
        ]);
    }
}
