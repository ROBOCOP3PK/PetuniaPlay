<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que verifica si un usuario es admin
     */
    public function test_usuario_puede_ser_admin(): void
    {
        // Arrange: Crear un usuario admin
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        // Assert: Verificar que es admin
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isManager());
        $this->assertFalse($user->isCustomer());
    }

    /**
     * Test que verifica si un usuario es manager
     */
    public function test_usuario_puede_ser_manager(): void
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'manager',
        ]);

        // Assert
        $this->assertTrue($user->isManager());
        $this->assertFalse($user->isAdmin());
    }

    /**
     * Test que verifica si un usuario es customer
     */
    public function test_usuario_puede_ser_customer(): void
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        // Assert
        $this->assertTrue($user->isCustomer());
        $this->assertFalse($user->hasManagerAccess());
    }

    /**
     * Test que verifica acceso de manager
     */
    public function test_admin_tiene_acceso_de_manager(): void
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'manager']);
        $customer = User::factory()->create(['role' => 'customer']);

        // Assert
        $this->assertTrue($admin->hasManagerAccess());
        $this->assertTrue($manager->hasManagerAccess());
        $this->assertFalse($customer->hasManagerAccess());
    }
}
