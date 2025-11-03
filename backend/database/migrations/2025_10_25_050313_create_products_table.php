<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->comment('ID único del producto');
            $table->foreignId('category_id')->constrained()->onDelete('cascade')->comment('Categoría del producto');
            $table->string('name')->comment('Nombre del producto');
            $table->string('slug')->unique()->comment('URL amigable única (ej: collar-perro-rojo)');
            $table->text('description')->comment('Descripción corta del producto');
            $table->longText('long_description')->nullable()->comment('Descripción detallada del producto');
            $table->decimal('price', 10, 2)->comment('Precio regular del producto');
            $table->decimal('sale_price', 10, 2)->nullable()->comment('Precio de oferta (si aplica)');
            $table->string('sku')->unique()->comment('Código SKU único del producto');
            $table->integer('stock')->default(0)->comment('Cantidad disponible en inventario');
            $table->decimal('weight', 8, 2)->nullable()->comment('Peso del producto en kg (para envíos)');
            $table->boolean('is_featured')->default(false)->comment('Producto destacado en home');
            $table->boolean('is_active')->default(true)->comment('Producto visible en tienda');
            $table->string('meta_title')->nullable()->comment('Título para SEO');
            $table->text('meta_description')->nullable()->comment('Descripción para SEO');
            $table->string('meta_keywords')->nullable()->comment('Palabras clave para SEO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
