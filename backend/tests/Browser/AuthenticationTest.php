<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * E2E Tests para autenticación de usuarios
 *
 * Estos tests verifican:
 * - Login exitoso con credenciales válidas
 * - Validación de formularios de login
 * - Registro de nuevos usuarios
 * - Logout
 * - Persistencia de sesión
 * - Acceso según roles (admin, manager, customer)
 */
class AuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar seeders necesarios
        $this->artisan('db:seed');
    }

    /**
     * Test: Usuario puede hacer login con credenciales válidas
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/login')
                    ->waitFor('input[type="email"]', 5)
                    ->type('input[type="email"]', 'admin@petuniaplay.com')
                    ->type('input[type="password"]', '2025')
                    ->click('button[type="submit"]')
                    ->pause(2000) // Esperar a que procese el login
                    ->waitForLocation('/account', 15)
                    ->pause(1000) // Esperar a que cargue la página
                    ->assertPathIs('/account');
        });
    }

    /**
     * Test: Login falla con credenciales inválidas
     */
    public function test_login_fails_with_invalid_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/login')
                    ->waitFor('input[type="email"]')
                    ->type('input[type="email"]', 'admin@petuniaplay.com')
                    ->type('input[type="password"]', 'wrongpassword')
                    ->press('button[type="submit"]')
                    ->waitForText('credenciales', 5)
                    ->assertSee('credenciales');
        });
    }

    /**
     * Test: Formulario de login valida campos requeridos
     */
    public function test_login_form_validates_required_fields()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/login')
                    ->waitFor('button[type="submit"]')
                    ->press('button[type="submit"]')
                    ->pause(500)
                    ->assertPresent('input[type="email"]:invalid')
                    ->assertPresent('input[type="password"]:invalid');
        });
    }

    /**
     * Test: Usuario puede registrarse con datos válidos
     */
    public function test_user_can_register_with_valid_data()
    {
        $this->browse(function (Browser $browser) {
            $uniqueEmail = 'newuser_' . time() . '@test.com';

            $browser->visit('http://localhost:5173/register')
                    ->waitFor('input[name="name"]')
                    ->type('input[name="name"]', 'New User Test')
                    ->type('input[name="email"]', $uniqueEmail)
                    ->type('input[name="phone"]', '+573001234567')
                    ->type('input[name="document"]', '1234567890')
                    ->type('input[name="password"]', 'password123')
                    ->type('input[name="password_confirmation"]', 'password123')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/account', 10)
                    ->assertPathIs('/account');

            // Verificar que el usuario fue creado en la base de datos
            $this->assertDatabaseHas('users', [
                'email' => $uniqueEmail,
                'role' => 'customer'
            ]);
        });
    }

    /**
     * Test: Registro valida formato de email
     */
    public function test_register_validates_email_format()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/register')
                    ->waitFor('input[name="email"]')
                    ->type('input[name="email"]', 'invalid-email')
                    ->type('input[name="password"]', 'password123')
                    ->press('button[type="submit"]')
                    ->pause(500)
                    ->assertPresent('input[name="email"]:invalid');
        });
    }

    /**
     * Test: Registro valida que passwords coincidan
     */
    public function test_register_validates_password_confirmation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/register')
                    ->waitFor('input[name="name"]')
                    ->type('input[name="name"]', 'Test User')
                    ->type('input[name="email"]', 'test@example.com')
                    ->type('input[name="phone"]', '+573001234567')
                    ->type('input[name="document"]', '1234567890')
                    ->type('input[name="password"]', 'password123')
                    ->type('input[name="password_confirmation"]', 'differentpassword')
                    ->press('button[type="submit"]')
                    ->waitForText('contraseñas', 5)
                    ->assertSee('contraseñas');
        });
    }

    /**
     * Test: Usuario puede hacer logout
     */
    public function test_user_can_logout()
    {
        // Crear usuario de prueba
        $user = User::factory()->create([
            'email' => 'logout-test@test.com',
            'password' => bcrypt('password123')
        ]);

        $this->browse(function (Browser $browser) {
            // Login primero
            $browser->visit('http://localhost:5173/login')
                    ->waitFor('input[type="email"]')
                    ->type('input[type="email"]', 'logout-test@test.com')
                    ->type('input[type="password"]', 'password123')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/account', 10);

            // Ahora hacer logout
            $browser->click('button[data-test="user-menu"]')
                    ->waitFor('button[data-test="logout-button"]')
                    ->click('button[data-test="logout-button"]')
                    ->waitForLocation('/login', 10)
                    ->assertPathIs('/login');
        });
    }

    /**
     * Test: Admin puede acceder al panel de administración
     */
    public function test_admin_can_access_admin_panel()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/login')
                    ->waitFor('input[type="email"]')
                    ->type('input[type="email"]', 'admin@petuniaplay.com')
                    ->type('input[type="password"]', '2025')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/account', 10)
                    ->visit('http://localhost:5173/admin/users')
                    ->waitFor('[data-test="admin-panel"]', 5)
                    ->assertSee('Usuarios');
        });
    }

    /**
     * Test: Manager puede acceder al panel pero no gestión de usuarios
     */
    public function test_manager_cannot_access_user_management()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/login')
                    ->waitFor('input[type="email"]')
                    ->type('input[type="email"]', 'manager@petuniaplay.com')
                    ->type('input[type="password"]', '2025')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/account', 10)
                    ->visit('http://localhost:5173/admin/users')
                    ->waitForText('No autorizado', 5)
                    ->assertSee('No autorizado');
        });
    }

    /**
     * Test: Customer no puede acceder al panel de administración
     */
    public function test_customer_cannot_access_admin_panel()
    {
        // Crear un cliente
        $customer = User::factory()->create([
            'email' => 'customer-test@test.com',
            'password' => bcrypt('password123'),
            'role' => 'customer'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/login')
                    ->waitFor('input[type="email"]')
                    ->type('input[type="email"]', 'customer-test@test.com')
                    ->type('input[type="password"]', 'password123')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/account', 10)
                    ->visit('http://localhost:5173/admin/products')
                    ->waitForText('No autorizado', 5)
                    ->assertSee('No autorizado');
        });
    }

    /**
     * Test: Sesión persiste después de refrescar la página
     */
    public function test_session_persists_after_page_refresh()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/login')
                    ->waitFor('input[type="email"]')
                    ->type('input[type="email"]', 'admin@petuniaplay.com')
                    ->type('input[type="password"]', '2025')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/account', 10)
                    ->refresh()
                    ->waitFor('[data-test="user-menu"]', 5)
                    ->assertPresent('[data-test="user-menu"]');
        });
    }

    /**
     * Test: Usuario no autenticado es redirigido al login
     */
    public function test_unauthenticated_user_is_redirected_to_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/dashboard')
                    ->waitForLocation('/login', 5)
                    ->assertPathIs('/login');
        });
    }
}
