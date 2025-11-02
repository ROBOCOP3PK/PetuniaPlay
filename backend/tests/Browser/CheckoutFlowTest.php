<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Product;
use App\Models\Address;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * E2E Tests para flujo completo de checkout
 *
 * Estos tests verifican:
 * - Agregar productos al carrito
 * - Modificar cantidades en el carrito
 * - Eliminar productos del carrito
 * - Aplicar cupones de descuento
 * - Seleccionar dirección de envío
 * - Completar proceso de pago
 * - Confirmación de orden
 */
class CheckoutFlowTest extends DuskTestCase
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
    protected function loginAsCustomer(Browser $browser)
    {
        $customer = User::firstOrCreate(
            ['email' => 'checkout@test.com'],
            [
                'name' => 'Checkout Test User',
                'password' => bcrypt('password123'),
                'role' => 'customer',
                'phone' => '+573001234567',
                'document' => '1234567890'
            ]
        );

        $browser->visit('http://localhost:5173/login')
                ->waitFor('input[type="email"]')
                ->type('input[type="email"]', 'checkout@test.com')
                ->type('input[type="password"]', 'password123')
                ->press('button[type="submit"]')
                ->waitForLocation('/dashboard', 10);

        return $customer;
    }

    /**
     * Test: Usuario puede agregar producto al carrito
     */
    public function test_user_can_add_product_to_cart()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::where('stock', '>', 0)->first();

            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->waitForText('agregado al carrito', 5)
                    ->assertSee('agregado al carrito')
                    ->assertPresent('[data-test="cart-badge"]');
        });
    }

    /**
     * Test: Contador del carrito se actualiza correctamente
     */
    public function test_cart_counter_updates_correctly()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::where('stock', '>', 0)->first();

            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000)
                    ->assertSeeIn('[data-test="cart-badge"]', '1')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000)
                    ->assertSeeIn('[data-test="cart-badge"]', '2');
        });
    }

    /**
     * Test: Usuario puede ver el carrito de compras
     */
    public function test_user_can_view_cart()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::where('stock', '>', 0)->first();

            // Agregar producto primero
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000);

            // Ir al carrito
            $browser->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="cart-items"]', 5)
                    ->assertPresent('[data-test="cart-item"]')
                    ->assertSee($product->name)
                    ->assertSee($product->price);
        });
    }

    /**
     * Test: Usuario puede incrementar cantidad en el carrito
     */
    public function test_user_can_increase_quantity_in_cart()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::where('stock', '>', 5)->first();

            // Agregar producto
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000);

            // Ir al carrito e incrementar
            $browser->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="cart-item"]')
                    ->click('[data-test="increase-quantity"]')
                    ->pause(500)
                    ->assertSeeIn('[data-test="item-quantity"]', '2');
        });
    }

    /**
     * Test: Usuario puede decrementar cantidad en el carrito
     */
    public function test_user_can_decrease_quantity_in_cart()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::where('stock', '>', 5)->first();

            // Agregar producto 2 veces
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(500)
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000);

            // Ir al carrito y decrementar
            $browser->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="cart-item"]')
                    ->click('[data-test="decrease-quantity"]')
                    ->pause(500)
                    ->assertSeeIn('[data-test="item-quantity"]', '1');
        });
    }

    /**
     * Test: Usuario puede eliminar producto del carrito
     */
    public function test_user_can_remove_product_from_cart()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::where('stock', '>', 0)->first();

            // Agregar producto
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000);

            // Ir al carrito y eliminar
            $browser->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="cart-item"]')
                    ->click('[data-test="remove-item"]')
                    ->waitForText('Carrito vacío', 5)
                    ->assertSee('Carrito vacío');
        });
    }

    /**
     * Test: Total del carrito se calcula correctamente
     */
    public function test_cart_total_is_calculated_correctly()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::where('stock', '>', 5)->first();
            $expectedTotal = $product->price * 2;

            // Agregar producto 2 veces
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(500)
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000);

            // Verificar total
            $browser->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="cart-total"]')
                    ->assertSeeIn('[data-test="cart-total"]', number_format($expectedTotal, 2));
        });
    }

    /**
     * Test: Usuario puede aplicar cupón de descuento válido
     */
    public function test_user_can_apply_valid_coupon()
    {
        $this->browse(function (Browser $browser) {
            // Crear cupón válido
            $coupon = Coupon::create([
                'code' => 'TEST10',
                'type' => 'percentage',
                'value' => 10,
                'min_purchase_amount' => 0,
                'max_discount_amount' => 100,
                'is_active' => true,
                'expires_at' => now()->addDays(30)
            ]);

            $product = Product::where('stock', '>', 0)->first();

            // Agregar producto
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000);

            // Ir al carrito y aplicar cupón
            $browser->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="coupon-input"]')
                    ->type('[data-test="coupon-input"]', 'TEST10')
                    ->click('[data-test="apply-coupon-button"]')
                    ->waitForText('Cupón aplicado', 5)
                    ->assertSee('Cupón aplicado')
                    ->assertPresent('[data-test="discount-amount"]');
        });
    }

    /**
     * Test: Cupón inválido muestra error
     */
    public function test_invalid_coupon_shows_error()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::where('stock', '>', 0)->first();

            // Agregar producto
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000);

            // Intentar aplicar cupón inválido
            $browser->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="coupon-input"]')
                    ->type('[data-test="coupon-input"]', 'INVALID')
                    ->click('[data-test="apply-coupon-button"]')
                    ->waitForText('inválido', 5)
                    ->assertSee('inválido');
        });
    }

    /**
     * Test: Usuario puede proceder al checkout
     */
    public function test_user_can_proceed_to_checkout()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsCustomer($browser);

            $product = Product::where('stock', '>', 0)->first();

            // Agregar producto
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000);

            // Proceder al checkout
            $browser->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="checkout-button"]')
                    ->click('[data-test="checkout-button"]')
                    ->waitForLocation('/checkout', 10)
                    ->assertPathIs('/checkout');
        });
    }

    /**
     * Test: Usuario puede seleccionar dirección de envío
     */
    public function test_user_can_select_shipping_address()
    {
        $this->browse(function (Browser $browser) {
            $customer = $this->loginAsCustomer($browser);

            // Crear dirección de prueba
            $address = Address::create([
                'user_id' => $customer->id,
                'address_line1' => 'Calle Test 123',
                'city' => 'Bogotá',
                'state' => 'Cundinamarca',
                'postal_code' => '110111',
                'country' => 'Colombia',
                'is_default' => true
            ]);

            $product = Product::where('stock', '>', 0)->first();

            // Agregar producto y proceder al checkout
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000)
                    ->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="checkout-button"]')
                    ->click('[data-test="checkout-button"]')
                    ->waitForLocation('/checkout', 10)
                    ->waitFor('[data-test="address-' . $address->id . '"]')
                    ->click('[data-test="address-' . $address->id . '"]')
                    ->assertPresent('[data-test="selected-address"]');
        });
    }

    /**
     * Test: Usuario puede agregar nueva dirección desde checkout
     */
    public function test_user_can_add_new_address_from_checkout()
    {
        $this->browse(function (Browser $browser) {
            $customer = $this->loginAsCustomer($browser);

            $product = Product::where('stock', '>', 0)->first();

            // Agregar producto y proceder al checkout
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000)
                    ->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="checkout-button"]')
                    ->click('[data-test="checkout-button"]')
                    ->waitForLocation('/checkout', 10)
                    ->click('[data-test="add-address-button"]')
                    ->waitFor('[data-test="address-form"]')
                    ->type('input[name="address_line1"]', 'Nueva Dirección 456')
                    ->type('input[name="city"]', 'Medellín')
                    ->type('input[name="state"]', 'Antioquia')
                    ->type('input[name="postal_code"]', '050001')
                    ->press('button[type="submit"]')
                    ->waitForText('agregada', 5)
                    ->assertSee('agregada');
        });
    }

    /**
     * Test: Flujo completo de checkout hasta confirmación
     */
    public function test_complete_checkout_flow()
    {
        $this->browse(function (Browser $browser) {
            $customer = $this->loginAsCustomer($browser);

            // Crear dirección
            $address = Address::create([
                'user_id' => $customer->id,
                'address_line1' => 'Dirección Completa 789',
                'city' => 'Cali',
                'state' => 'Valle del Cauca',
                'postal_code' => '760001',
                'country' => 'Colombia',
                'is_default' => true
            ]);

            $product = Product::where('stock', '>', 0)->first();

            // 1. Agregar producto al carrito
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->pause(1000);

            // 2. Ir al carrito
            $browser->click('[data-test="cart-icon"]')
                    ->waitFor('[data-test="checkout-button"]');

            // 3. Proceder al checkout
            $browser->click('[data-test="checkout-button"]')
                    ->waitForLocation('/checkout', 10);

            // 4. Seleccionar dirección
            $browser->waitFor('[data-test="address-' . $address->id . '"]')
                    ->click('[data-test="address-' . $address->id . '"]')
                    ->pause(500);

            // 5. Seleccionar método de pago
            $browser->click('[data-test="payment-method-card"]')
                    ->pause(500);

            // 6. Confirmar orden
            $browser->click('[data-test="place-order-button"]')
                    ->waitForLocation('/order-confirmation', 15)
                    ->assertPathIs('/order-confirmation')
                    ->assertSee('¡Gracias por tu compra!');

            // Verificar que se creó la orden en BD
            $this->assertDatabaseHas('orders', [
                'user_id' => $customer->id,
                'status' => 'pending'
            ]);
        });
    }

    /**
     * Test: Usuario puede ver detalles de la orden después del checkout
     */
    public function test_user_can_view_order_details_after_checkout()
    {
        $this->browse(function (Browser $browser) {
            $customer = $this->loginAsCustomer($browser);

            $browser->visit('http://localhost:5173/orders')
                    ->waitFor('[data-test="orders-list"]', 5)
                    ->assertPresent('[data-test="orders-list"]')
                    ->assertSee('Mis órdenes');
        });
    }
}
