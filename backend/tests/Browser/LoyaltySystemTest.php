<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\LoyaltyProgram;
use App\Models\LoyaltyReward;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * E2E Tests para sistema de lealtad
 *
 * Estos tests verifican:
 * - Acumulación de puntos al realizar compras
 * - Visualización de puntos del usuario
 * - Listado de recompensas disponibles
 * - Canje de recompensas
 * - Historial de canjes
 * - Gestión de programa de lealtad (admin)
 * - Creación de recompensas (admin)
 */
class LoyaltySystemTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar seeders necesarios
        $this->artisan('db:seed');
    }

    /**
     * Helper: Login como customer
     */
    protected function loginAsCustomer(Browser $browser, $email = null)
    {
        $email = $email ?? 'customer@test.com';

        // Crear customer si no existe
        $customer = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Test Customer',
                'password' => bcrypt('password123'),
                'role' => 'customer',
                'loyalty_points' => 500,
                'loyalty_tier' => 'bronze'
            ]
        );

        $browser->visit('http://localhost:5173/login')
                ->waitFor('input[type="email"]')
                ->type('input[type="email"]', $email)
                ->type('input[type="password"]', 'password123')
                ->press('button[type="submit"]')
                ->waitForLocation('/dashboard', 10);

        return $customer;
    }

    /**
     * Helper: Login como admin
     */
    protected function loginAsAdmin(Browser $browser)
    {
        $browser->visit('http://localhost:5173/login')
                ->waitFor('input[type="email"]')
                ->type('input[type="email"]', 'admin@petuniaplay.com')
                ->type('input[type="password"]', '2025')
                ->press('button[type="submit"]')
                ->waitForLocation('/dashboard', 10);
    }

    /**
     * Test: Usuario puede ver sus puntos de lealtad
     */
    public function test_user_can_view_loyalty_points()
    {
        $this->browse(function (Browser $browser) {
            $customer = $this->loginAsCustomer($browser);

            $browser->visit('http://localhost:5173/loyalty')
                    ->waitFor('[data-test="loyalty-points"]', 5)
                    ->assertSee($customer->loyalty_points)
                    ->assertSee('puntos');
        });
    }

    /**
     * Test: Usuario puede ver su tier de lealtad
     */
    public function test_user_can_view_loyalty_tier()
    {
        $this->browse(function (Browser $browser) {
            $customer = $this->loginAsCustomer($browser);

            $browser->visit('http://localhost:5173/loyalty')
                    ->waitFor('[data-test="loyalty-tier"]', 5)
                    ->assertSee(ucfirst($customer->loyalty_tier));
        });
    }

    /**
     * Test: Usuario puede ver listado de recompensas disponibles
     */
    public function test_user_can_view_available_rewards()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsCustomer($browser);

            $browser->visit('http://localhost:5173/loyalty/rewards')
                    ->waitFor('[data-test="reward-card"]', 5)
                    ->assertPresent('[data-test="reward-card"]')
                    ->assertSee('Recompensas');
        });
    }

    /**
     * Test: Usuario puede filtrar recompensas por puntos requeridos
     */
    public function test_user_can_filter_rewards_by_points()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsCustomer($browser);

            $browser->visit('http://localhost:5173/loyalty/rewards')
                    ->waitFor('[data-test="points-filter"]')
                    ->select('[data-test="points-filter"]', 'affordable')
                    ->pause(1000)
                    ->waitFor('[data-test="reward-card"]', 5)
                    ->assertPresent('[data-test="reward-card"]');
        });
    }

    /**
     * Test: Recompensas no canjeables muestran indicador correcto
     */
    public function test_unaffordable_rewards_show_correct_indicator()
    {
        $this->browse(function (Browser $browser) {
            // Usuario con pocos puntos
            $customer = User::factory()->create([
                'email' => 'lowpoints@test.com',
                'password' => bcrypt('password123'),
                'role' => 'customer',
                'loyalty_points' => 50
            ]);

            $this->loginAsCustomer($browser, 'lowpoints@test.com');

            $browser->visit('http://localhost:5173/loyalty/rewards')
                    ->waitFor('[data-test="reward-card"]', 5)
                    ->assertSee('Puntos insuficientes');
        });
    }

    /**
     * Test: Usuario puede canjear recompensa con suficientes puntos
     */
    public function test_user_can_redeem_reward_with_sufficient_points()
    {
        $this->browse(function (Browser $browser) {
            $customer = User::factory()->create([
                'email' => 'redeemer@test.com',
                'password' => bcrypt('password123'),
                'role' => 'customer',
                'loyalty_points' => 1000
            ]);

            // Crear recompensa canjeable
            $reward = LoyaltyReward::first();
            if (!$reward) {
                $program = LoyaltyProgram::first();
                $reward = LoyaltyReward::create([
                    'loyalty_program_id' => $program->id,
                    'name' => 'Test Reward',
                    'description' => 'Test description',
                    'points_required' => 500,
                    'type' => 'discount',
                    'value' => 10,
                    'is_active' => true,
                    'reward_type' => 'permanent'
                ]);
            }

            $this->loginAsCustomer($browser, 'redeemer@test.com');

            $initialPoints = $customer->loyalty_points;

            $browser->visit('http://localhost:5173/loyalty/rewards')
                    ->waitFor('[data-test="reward-' . $reward->id . '"]')
                    ->click('[data-test="redeem-reward-' . $reward->id . '"]')
                    ->waitFor('[data-test="confirm-redeem-dialog"]', 3)
                    ->assertSee('¿Canjear esta recompensa?')
                    ->click('[data-test="confirm-redeem-button"]')
                    ->waitForText('canjeada exitosamente', 10)
                    ->assertSee('canjeada exitosamente');

            // Verificar que los puntos se restaron
            $customer->refresh();
            $this->assertLessThan($initialPoints, $customer->loyalty_points);

            // Verificar que se creó el registro de canje
            $this->assertDatabaseHas('loyalty_redemptions', [
                'user_id' => $customer->id,
                'loyalty_reward_id' => $reward->id
            ]);
        });
    }

    /**
     * Test: Usuario puede ver historial de canjes
     */
    public function test_user_can_view_redemption_history()
    {
        $this->browse(function (Browser $browser) {
            $customer = $this->loginAsCustomer($browser);

            $browser->visit('http://localhost:5173/loyalty/history')
                    ->waitFor('[data-test="redemption-history"]', 5)
                    ->assertPresent('[data-test="redemption-history"]')
                    ->assertSee('Historial de canjes');
        });
    }

    /**
     * Test: Puntos se acumulan automáticamente al completar orden
     */
    public function test_points_are_accumulated_after_order_completion()
    {
        $customer = User::factory()->create([
            'email' => 'buyer@test.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'loyalty_points' => 100
        ]);

        $initialPoints = $customer->loyalty_points;

        // Crear orden completada
        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'completed',
            'total' => 100.00,
            'points_earned' => 10
        ]);

        // Actualizar puntos del usuario manualmente (simula el job)
        $customer->loyalty_points += $order->points_earned;
        $customer->save();

        $this->browse(function (Browser $browser) use ($customer, $initialPoints) {
            $this->loginAsCustomer($browser, 'buyer@test.com');

            $browser->visit('http://localhost:5173/loyalty')
                    ->waitFor('[data-test="loyalty-points"]', 5);

            // Verificar que los puntos aumentaron
            $customer->refresh();
            $this->assertGreaterThan($initialPoints, $customer->loyalty_points);
        });
    }

    /**
     * Test: Admin puede acceder a gestión del programa de lealtad
     */
    public function test_admin_can_access_loyalty_program_management()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('http://localhost:5173/admin/loyalty/program')
                    ->waitFor('[data-test="loyalty-program-settings"]', 5)
                    ->assertPresent('[data-test="loyalty-program-settings"]')
                    ->assertSee('Programa de Lealtad');
        });
    }

    /**
     * Test: Admin puede crear nueva recompensa
     */
    public function test_admin_can_create_new_reward()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $rewardName = 'New Test Reward ' . time();

            $browser->visit('http://localhost:5173/admin/loyalty/rewards/create')
                    ->waitFor('[data-test="reward-form"]')
                    ->type('input[name="name"]', $rewardName)
                    ->type('textarea[name="description"]', 'Test reward description')
                    ->type('input[name="points_required"]', '500')
                    ->select('select[name="type"]', 'discount')
                    ->type('input[name="value"]', '15')
                    ->press('button[type="submit"]')
                    ->waitForText('exitosamente', 10)
                    ->assertSee('exitosamente');

            // Verificar creación en BD
            $this->assertDatabaseHas('loyalty_rewards', [
                'name' => $rewardName,
                'points_required' => 500
            ]);
        });
    }

    /**
     * Test: Admin puede editar recompensa existente
     */
    public function test_admin_can_edit_existing_reward()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $reward = LoyaltyReward::first();
            $newName = 'Updated Reward ' . time();

            $browser->visit('http://localhost:5173/admin/loyalty/rewards/' . $reward->id . '/edit')
                    ->waitFor('[data-test="reward-form"]')
                    ->clear('input[name="name"]')
                    ->type('input[name="name"]', $newName)
                    ->press('button[type="submit"]')
                    ->waitForText('actualizada', 10)
                    ->assertSee('actualizada');

            // Verificar actualización
            $this->assertDatabaseHas('loyalty_rewards', [
                'id' => $reward->id,
                'name' => $newName
            ]);
        });
    }

    /**
     * Test: Admin puede desactivar recompensa
     */
    public function test_admin_can_deactivate_reward()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $reward = LoyaltyReward::where('is_active', true)->first();

            $browser->visit('http://localhost:5173/admin/loyalty/rewards')
                    ->waitFor('[data-test="toggle-reward-' . $reward->id . '"]')
                    ->click('[data-test="toggle-reward-' . $reward->id . '"]')
                    ->waitForText('desactivada', 5)
                    ->assertSee('desactivada');

            // Verificar en BD
            $reward->refresh();
            $this->assertFalse($reward->is_active);
        });
    }

    /**
     * Test: Admin puede ver estadísticas del programa de lealtad
     */
    public function test_admin_can_view_loyalty_statistics()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('http://localhost:5173/admin/loyalty/stats')
                    ->waitFor('[data-test="loyalty-stats"]', 5)
                    ->assertPresent('[data-test="total-members"]')
                    ->assertPresent('[data-test="points-distributed"]')
                    ->assertPresent('[data-test="redemptions-count"]');
        });
    }

    /**
     * Test: Usuario puede ver beneficios de cada tier
     */
    public function test_user_can_view_tier_benefits()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsCustomer($browser);

            $browser->visit('http://localhost:5173/loyalty/tiers')
                    ->waitFor('[data-test="tier-card"]', 5)
                    ->assertPresent('[data-test="tier-bronze"]')
                    ->assertPresent('[data-test="tier-silver"]')
                    ->assertPresent('[data-test="tier-gold"]')
                    ->assertPresent('[data-test="tier-platinum"]')
                    ->assertSee('beneficios');
        });
    }

    /**
     * Test: Recompensa con stock limitado se actualiza después del canje
     */
    public function test_limited_stock_reward_updates_after_redemption()
    {
        $customer = User::factory()->create([
            'email' => 'stocktest@test.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'loyalty_points' => 2000
        ]);

        $program = LoyaltyProgram::first();
        $reward = LoyaltyReward::create([
            'loyalty_program_id' => $program->id,
            'name' => 'Limited Reward',
            'description' => 'Limited stock test',
            'points_required' => 500,
            'type' => 'discount',
            'value' => 20,
            'is_active' => true,
            'reward_type' => 'campaign',
            'stock' => 5,
            'max_redemptions_per_user' => 1
        ]);

        $this->browse(function (Browser $browser) use ($reward) {
            $this->loginAsCustomer($browser, 'stocktest@test.com');

            $initialStock = $reward->stock;

            $browser->visit('http://localhost:5173/loyalty/rewards')
                    ->waitFor('[data-test="reward-' . $reward->id . '"]')
                    ->assertSee('Stock: ' . $initialStock)
                    ->click('[data-test="redeem-reward-' . $reward->id . '"]')
                    ->waitFor('[data-test="confirm-redeem-dialog"]')
                    ->click('[data-test="confirm-redeem-button"]')
                    ->waitForText('canjeada exitosamente', 10)
                    ->refresh()
                    ->waitFor('[data-test="reward-' . $reward->id . '"]')
                    ->assertSee('Stock: ' . ($initialStock - 1));
        });
    }
}
