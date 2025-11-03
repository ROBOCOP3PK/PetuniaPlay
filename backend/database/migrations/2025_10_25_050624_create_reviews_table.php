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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id()->comment('ID único de la reseña');
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->comment('Producto reseñado');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuario que escribió la reseña');
            $table->integer('rating')->comment('Calificación de 1-5 estrellas');
            $table->string('title')->nullable()->comment('Título de la reseña');
            $table->text('comment')->nullable()->comment('Comentario de la reseña');
            $table->boolean('is_verified_purchase')->default(false)->comment('Cliente verificó compra del producto');
            $table->boolean('is_approved')->default(true)->comment('Reseña aprobada por moderador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
