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
        Schema::create('animal_sections', function (Blueprint $table) {
            $table->id()->comment('ID único de la sección de animal');
            $table->string('name')->comment('Nombre de la sección (ej: Perros, Gatos, Loros)');
            $table->string('slug')->unique()->comment('URL amigable única (ej: perros, gatos)');
            $table->string('icon')->nullable()->comment('Icono/emoji para UI (ej: 🐕, 🐈, 🦜)');
            $table->text('description')->nullable()->comment('Descripción de la sección');
            $table->integer('order')->default(0)->comment('Orden de visualización');
            $table->boolean('is_active')->default(false)->comment('Sección visible en frontend público');
            $table->timestamps();

            // Índices para optimización
            $table->index('is_active');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_sections');
    }
};
