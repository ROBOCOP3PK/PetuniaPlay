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

        // Get products for rewards
        $products = Product::take(7)->get();

        if ($products->count() < 7) {
            $this->command->warn('No hay suficientes productos en la base de datos. Se necesitan al menos 7 productos.');
            return;
        }

        // Create permanent rewards (programas permanentes)
        $permanentRewards = [
            [
                'name' => 'Primera Compra - Regalo de Bienvenida',
                'description' => 'Recibe un regalo especial en tu primera compra con nosotros. ¡Gracias por confiar en Petunia Play!',
                'type' => 'permanent',
                'target_audience' => 'new_only',
                'required_purchases' => 1,
                'product_id' => $products[0]->id,
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'name' => 'Cliente Frecuente - 5 Compras',
                'description' => 'Has realizado 5 compras con nosotros. ¡Disfruta de este regalo como agradecimiento por tu preferencia!',
                'type' => 'permanent',
                'target_audience' => 'all',
                'required_purchases' => 5,
                'product_id' => $products[1]->id,
                'is_active' => true,
                'priority' => 2,
            ],
            [
                'name' => 'Cliente VIP - 10 Compras',
                'description' => 'Has alcanzado 10 compras. Eres parte de nuestra familia VIP. Recibe este premio especial.',
                'type' => 'permanent',
                'target_audience' => 'all',
                'required_purchases' => 10,
                'product_id' => $products[2]->id,
                'is_active' => true,
                'priority' => 3,
            ],
            [
                'name' => 'Cliente Gold - 20 Compras',
                'description' => '¡20 compras completadas! Eres un cliente Gold. Disfruta de esta recompensa exclusiva por tu lealtad.',
                'type' => 'permanent',
                'target_audience' => 'all',
                'required_purchases' => 20,
                'product_id' => $products[3]->id,
                'is_active' => true,
                'priority' => 4,
            ],
        ];

        // Create campaign rewards (campañas temporales - ejemplos)
        $campaignRewards = [
            [
                'name' => 'Campaña Navideña - Regalo Especial',
                'description' => 'Celebra la navidad con nosotros. Realiza 3 compras durante diciembre y recibe un regalo navideño.',
                'type' => 'campaign',
                'target_audience' => 'all',
                'required_purchases' => 3,
                'product_id' => $products[4]->id,
                'start_date' => '2025-12-01',
                'end_date' => '2025-12-31',
                'is_active' => true,
                'priority' => 10,
            ],
            [
                'name' => 'Día del Perro - Promo Especial',
                'description' => 'En el día internacional del perro, realiza 2 compras y llévate un regalo especial para tu mejor amigo.',
                'type' => 'campaign',
                'target_audience' => 'all',
                'required_purchases' => 2,
                'product_id' => $products[5]->id,
                'start_date' => '2025-08-21',
                'end_date' => '2025-08-31',
                'is_active' => false, // Inactiva porque ya pasó la fecha
                'priority' => 11,
            ],
            [
                'name' => 'Aniversario Petunia Play - Recompensa',
                'description' => '¡Celebramos nuestro aniversario! Compra 4 productos durante marzo y recibe un regalo sorpresa.',
                'type' => 'campaign',
                'target_audience' => 'all',
                'required_purchases' => 4,
                'product_id' => $products[6]->id,
                'start_date' => '2026-03-01',
                'end_date' => '2026-03-31',
                'is_active' => true,
                'priority' => 12,
            ],
        ];

        // Insert all rewards
        $allRewards = array_merge($permanentRewards, $campaignRewards);

        foreach ($allRewards as $rewardData) {
            $rewardData['loyalty_program_id'] = $program->id;
            LoyaltyReward::create($rewardData);
        }

        $this->command->info('Programa de lealtad creado exitosamente.');
        $this->command->info('- 4 recompensas permanentes (1, 5, 10, 20 compras)');
        $this->command->info('- 3 campañas temporales de ejemplo');
    }
}
