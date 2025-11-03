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
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->comment('ID único de la categoría');
            $table->string('name')->comment('Nombre de la categoría (ej: Perros, Gatos)');
            $table->string('slug')->unique()->comment('URL amigable única (ej: perros)');
            $table->text('description')->nullable()->comment('Descripción de la categoría');
            $table->string('image')->nullable()->comment('Imagen representativa de la categoría');
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade')->comment('Categoría padre (para subcategorías)');
            $table->integer('order')->default(0)->comment('Orden de visualización');
            $table->boolean('is_active')->default(true)->comment('Categoría visible en tienda');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
