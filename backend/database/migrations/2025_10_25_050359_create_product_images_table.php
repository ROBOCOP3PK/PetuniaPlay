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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id()->comment('ID único de la imagen');
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->comment('Producto al que pertenece la imagen');
            $table->string('image_url')->comment('URL de la imagen del producto');
            $table->boolean('is_primary')->default(false)->comment('Imagen principal del producto');
            $table->integer('order')->default(0)->comment('Orden de visualización de las imágenes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
