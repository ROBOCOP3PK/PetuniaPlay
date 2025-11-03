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
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->id()->comment('ID único del programa de fidelidad');
            $table->string('name')->comment('Nombre del programa (ej: Club PetuniaPlay)');
            $table->text('description')->nullable()->comment('Descripción del programa');
            $table->boolean('is_active')->default(true)->comment('Programa activo/inactivo');
            $table->json('settings')->nullable()->comment('Configuraciones: reglas de puntos, niveles, etc.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_programs');
    }
};
