<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear cupones usando modelo Eloquent
        $coupons = [
            [
                'code' => 'BIENVENIDO10',
                'type' => 'percentage',
                'value' => 10.00,
                'min_purchase_amount' => 50000.00,
                'usage_limit' => 100,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'VERANO2025',
                'type' => 'percentage',
                'value' => 15.00,
                'min_purchase_amount' => 100000.00,
                'usage_limit' => 50,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'PRIMERACOMPRA',
                'type' => 'fixed',
                'value' => 20000.00,
                'min_purchase_amount' => 80000.00,
                'usage_limit' => 200,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'BLACKFRIDAY',
                'type' => 'percentage',
                'value' => 25.00,
                'min_purchase_amount' => 150000.00,
                'usage_limit' => 30,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'code' => 'ENVIOGRATIS',
                'type' => 'fixed',
                'value' => 15000.00,
                'min_purchase_amount' => 60000.00,
                'usage_limit' => null, // Sin límite
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => null, // Sin expiración
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }

        $this->command->info('✅ Cupones creados exitosamente: ' . count($coupons) . ' cupones');
    }
}
