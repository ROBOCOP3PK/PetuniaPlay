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
        Schema::create('pets', function (Blueprint $table) {
            $table->id()->comment('ID único de la mascota');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuario dueño de la mascota');
            $table->foreignId('animal_section_id')->nullable()->constrained('animal_sections')->onDelete('set null')->comment('Categoría de la mascota (perro, gato, etc.)');
            $table->string('name')->comment('Nombre de la mascota');
            $table->date('birth_date')->nullable()->comment('Fecha de nacimiento');
            $table->decimal('weight', 5, 2)->nullable()->comment('Peso en kg');
            $table->string('photo')->nullable()->comment('URL de la foto de la mascota');
            $table->timestamps();

            // Índices
            $table->index('user_id');
            $table->index('animal_section_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
