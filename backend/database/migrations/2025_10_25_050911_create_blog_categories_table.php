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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id()->comment('ID único de la categoría de blog');
            $table->string('name')->comment('Nombre de la categoría (ej: Tips, Noticias)');
            $table->string('slug')->unique()->comment('URL amigable única');
            $table->text('description')->nullable()->comment('Descripción de la categoría');
            $table->boolean('is_active')->default(true)->comment('Categoría activa/visible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
