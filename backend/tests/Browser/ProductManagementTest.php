<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * E2E Tests para gestión de productos
 *
 * Estos tests verifican:
 * - Listar productos en el catálogo
 * - Búsqueda de productos
 * - Creación de productos (admin)
 * - Edición de productos (admin)
 * - Eliminación de productos (admin)
 * - Visualización de detalle de producto
 * - Filtros y ordenamiento
 * - Carga de imágenes
 */
class ProductManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar seeders necesarios
        $this->artisan('db:seed');
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
     * Test: Usuario puede ver lista de productos
     */
    public function test_user_can_view_product_list()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/products')
                    ->waitFor('[data-test="product-card"]', 5)
                    ->assertPresent('[data-test="product-card"]')
                    ->assertSee('Productos');
        });
    }

    /**
     * Test: Usuario puede buscar productos
     */
    public function test_user_can_search_products()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/products')
                    ->waitFor('input[data-test="search-input"]')
                    ->type('input[data-test="search-input"]', 'collar')
                    ->pause(1000) // Esperar debounce
                    ->waitFor('[data-test="product-card"]', 5)
                    ->assertSee('collar');
        });
    }

    /**
     * Test: Usuario puede ver detalle de producto
     */
    public function test_user_can_view_product_detail()
    {
        $product = Product::first();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="product-detail"]', 5)
                    ->assertSee($product->name)
                    ->assertSee($product->price)
                    ->assertPresent('[data-test="add-to-cart-button"]');
        });
    }

    /**
     * Test: Usuario puede filtrar productos por categoría
     */
    public function test_user_can_filter_products_by_category()
    {
        $category = Category::first();

        $this->browse(function (Browser $browser) use ($category) {
            $browser->visit('http://localhost:5173/products')
                    ->waitFor('[data-test="category-filter"]')
                    ->click('[data-test="category-' . $category->id . '"]')
                    ->pause(1000)
                    ->waitFor('[data-test="product-card"]', 5)
                    ->assertPresent('[data-test="product-card"]');
        });
    }

    /**
     * Test: Usuario puede ordenar productos por precio
     */
    public function test_user_can_sort_products_by_price()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173/products')
                    ->waitFor('[data-test="sort-select"]')
                    ->select('[data-test="sort-select"]', 'price_asc')
                    ->pause(1000)
                    ->waitFor('[data-test="product-card"]', 5)
                    ->assertPresent('[data-test="product-card"]');
        });
    }

    /**
     * Test: Admin puede hacer clic en botón "Agregar Producto"
     */
    public function test_admin_can_click_add_product_button()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('http://localhost:5173/admin/products')
                    ->waitFor('[data-test="add-product-button"]')
                    ->click('[data-test="add-product-button"]')
                    ->waitFor('[data-test="product-form"]', 5)
                    ->assertPresent('[data-test="product-form"]');
        });
    }

    /**
     * Test: Formulario de producto valida campos requeridos
     */
    public function test_product_form_validates_required_fields()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('http://localhost:5173/admin/products/create')
                    ->waitFor('[data-test="product-form"]')
                    ->press('button[type="submit"]')
                    ->pause(500)
                    ->assertSee('requerido')
                    ->assertPresent('input[name="name"]:invalid');
        });
    }

    /**
     * Test: Admin puede crear producto exitosamente
     */
    public function test_admin_can_create_product_successfully()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $category = Category::first();
            $productName = 'Test Product E2E ' . time();

            $browser->visit('http://localhost:5173/admin/products/create')
                    ->waitFor('[data-test="product-form"]')
                    ->type('input[name="name"]', $productName)
                    ->type('textarea[name="description"]', 'Descripción de prueba E2E')
                    ->type('input[name="price"]', '99.99')
                    ->type('input[name="stock"]', '50')
                    ->select('select[name="category_id"]', $category->id)
                    ->type('input[name="brand"]', 'Test Brand')
                    ->press('button[type="submit"]')
                    ->waitForText('exitosamente', 10)
                    ->assertSee('exitosamente');

            // Verificar que el producto fue creado
            $this->assertDatabaseHas('products', [
                'name' => $productName,
                'price' => 99.99,
                'stock' => 50
            ]);
        });
    }

    /**
     * Test: Producto aparece en lista después de creación
     */
    public function test_product_appears_in_list_after_creation()
    {
        $productName = 'Test Product List ' . time();

        $product = Product::create([
            'name' => $productName,
            'slug' => \Illuminate\Support\Str::slug($productName),
            'description' => 'Test description',
            'price' => 50.00,
            'stock' => 10,
            'category_id' => Category::first()->id,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($productName) {
            $browser->visit('http://localhost:5173/products')
                    ->waitFor('[data-test="product-card"]')
                    ->assertSee($productName);
        });
    }

    /**
     * Test: Admin puede hacer clic en botón editar
     */
    public function test_admin_can_click_edit_button()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $product = Product::first();

            $browser->visit('http://localhost:5173/admin/products')
                    ->waitFor('[data-test="edit-product-' . $product->id . '"]')
                    ->click('[data-test="edit-product-' . $product->id . '"]')
                    ->waitFor('[data-test="product-form"]', 5)
                    ->assertInputValue('input[name="name"]', $product->name);
        });
    }

    /**
     * Test: Admin puede actualizar producto
     */
    public function test_admin_can_update_product()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $product = Product::first();
            $newName = 'Updated Product Name ' . time();

            $browser->visit('http://localhost:5173/admin/products/' . $product->id . '/edit')
                    ->waitFor('[data-test="product-form"]')
                    ->clear('input[name="name"]')
                    ->type('input[name="name"]', $newName)
                    ->press('button[type="submit"]')
                    ->waitForText('actualizado', 10)
                    ->assertSee('actualizado');

            // Verificar actualización en BD
            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'name' => $newName
            ]);
        });
    }

    /**
     * Test: Admin puede eliminar producto con confirmación
     */
    public function test_admin_can_delete_product_with_confirmation()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $product = Product::factory()->create();

            $browser->visit('http://localhost:5173/admin/products')
                    ->waitFor('[data-test="delete-product-' . $product->id . '"]')
                    ->click('[data-test="delete-product-' . $product->id . '"]')
                    ->waitFor('[data-test="confirm-delete-dialog"]', 3)
                    ->assertSee('¿Estás seguro?')
                    ->click('[data-test="confirm-delete-button"]')
                    ->waitForText('eliminado', 10)
                    ->assertSee('eliminado');

            // Verificar eliminación en BD
            $this->assertDatabaseMissing('products', [
                'id' => $product->id
            ]);
        });
    }

    /**
     * Test: Usuario puede subir imagen de producto
     */
    public function test_admin_can_upload_product_image()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $product = Product::first();
            $testImagePath = __DIR__ . '/../../storage/test-image.jpg';

            // Crear imagen de prueba si no existe
            if (!file_exists($testImagePath)) {
                copy(__DIR__ . '/../../public/placeholder.jpg', $testImagePath);
            }

            $browser->visit('http://localhost:5173/admin/products/' . $product->id . '/edit')
                    ->waitFor('[data-test="image-upload"]')
                    ->attach('input[type="file"]', $testImagePath)
                    ->pause(2000) // Esperar upload
                    ->waitFor('[data-test="uploaded-image"]', 10)
                    ->assertPresent('[data-test="uploaded-image"]');
        });
    }

    /**
     * Test: Admin puede descargar catálogo de productos (PDF/Excel)
     */
    public function test_admin_can_download_product_catalog()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('http://localhost:5173/admin/products')
                    ->waitFor('[data-test="export-button"]')
                    ->click('[data-test="export-button"]')
                    ->waitFor('[data-test="export-excel"]')
                    ->click('[data-test="export-excel"]')
                    ->pause(2000); // Esperar descarga

            // Nota: Verificar descarga en el navegador es complejo en Dusk
            // Este test verifica que el botón existe y es clickeable
        });
    }

    /**
     * Test: Usuario puede agregar producto al carrito desde detalle
     */
    public function test_user_can_add_product_to_cart_from_detail()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::where('stock', '>', 0)->first();

            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="add-to-cart-button"]')
                    ->click('[data-test="add-to-cart-button"]')
                    ->waitForText('agregado al carrito', 5)
                    ->assertSee('agregado al carrito')
                    ->assertPresent('[data-test="cart-count"]');
        });
    }

    /**
     * Test: Productos relacionados se muestran en detalle
     */
    public function test_related_products_are_displayed_in_detail()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::first();

            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="product-detail"]')
                    ->scrollIntoView('[data-test="related-products"]')
                    ->assertPresent('[data-test="related-products"]')
                    ->assertSee('Productos relacionados');
        });
    }

    /**
     * Test: Stock bajo se muestra correctamente
     */
    public function test_low_stock_indicator_is_displayed()
    {
        $product = Product::first();
        $product->update(['stock' => 2]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="product-detail"]')
                    ->assertSee('Últimas unidades');
        });
    }

    /**
     * Test: Producto sin stock no se puede agregar al carrito
     */
    public function test_out_of_stock_product_cannot_be_added_to_cart()
    {
        $product = Product::first();
        $product->update(['stock' => 0]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('http://localhost:5173/products/' . $product->slug)
                    ->waitFor('[data-test="product-detail"]')
                    ->assertSee('Agotado')
                    ->assertMissing('[data-test="add-to-cart-button"]');
        });
    }
}
