<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoyaltyProgram;
use App\Models\LoyaltyReward;
use App\Models\Product;

class LoyaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the loyalty program
        $program = LoyaltyProgram::create([
            'name' => 'Programa de Lealtad Petunia Play',
            'description' => 'Gana recompensas por tus compras. Mientras más compres, mejores beneficios obtienes.',
            'is_active' => true,
            'settings' => [
                'points_per_purchase' => 10,
                'welcome_message' => '¡Bienvenido al programa de lealtad!',
            ],
        ]);

        // Get first 4 products for rewards
        $products = Product::take(4)->get();

        if ($products->count() < 4) {
            $this->command->warn('No hay suficientes productos en la base de datos. Se necesitan al menos 4 productos.');
            return;
        }

        // Create permanent rewards
        $rewards = [
            [
                'name' => 'Primera Compra - Regalo de Bienvenida',
                'description' => 'Recibe un regalo especial en tu primera compra con nosotros',
                'type' => 'permanent',
                'target_audience' => 'new_only',
                'required_purchases' => 1,
                'product_id' => $products[0]->id,
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'name' => 'Nivel Bronce - 3 Compras',
                'description' => 'Alcanza el nivel bronce y recibe un producto de regalo',
                'type' => 'permanent',
                'target_audience' => 'new_only',
                'required_purchases' => 3,
                'product_id' => $products[1]->id,
                'is_active' => true,
                'priority' => 2,
            ],
            [
                'name' => 'Nivel Plata - 10 Compras',
                'description' => 'Alcanza el nivel plata y disfruta de beneficios exclusivos',
                'type' => 'permanent',
                'target_audience' => 'all',
                'required_purchases' => 10,
                'product_id' => $products[2]->id,
                'is_active' => true,
                'priority' => 3,
            ],
            [
                'name' => 'Nivel Oro - 25 Compras',
                'description' => 'Alcanza el nivel oro y obtén las mejores recompensas',
                'type' => 'permanent',
                'target_audience' => 'all',
                'required_purchases' => 25,
                'product_id' => $products[3]->id,
                'is_active' => true,
                'priority' => 4,
            ],
        ];

        foreach ($rewards as $rewardData) {
            $rewardData['loyalty_program_id'] = $program->id;
            LoyaltyReward::create($rewardData);
        }

        $this->command->info('Programa de lealtad y recompensas creados exitosamente.');
    }
}
