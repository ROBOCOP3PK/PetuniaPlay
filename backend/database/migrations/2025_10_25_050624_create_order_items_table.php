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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id()->comment('ID único del item de la orden');
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->comment('Orden a la que pertenece el item');
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->comment('Producto ordenado');
            $table->string('product_name')->comment('Nombre del producto al momento de la compra');
            $table->string('product_sku')->comment('SKU del producto al momento de la compra');
            $table->decimal('price', 10, 2)->comment('Precio unitario del producto');
            $table->integer('quantity')->comment('Cantidad ordenada');
            $table->decimal('subtotal', 10, 2)->comment('Subtotal (precio × cantidad)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
