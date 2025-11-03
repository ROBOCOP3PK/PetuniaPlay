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
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id()->comment('ID único del item en wishlist');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuario dueño de la wishlist');
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->comment('Producto agregado a wishlist');
            $table->timestamps();

            // Evitar duplicados: un usuario no puede agregar el mismo producto dos veces
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
    }
};
