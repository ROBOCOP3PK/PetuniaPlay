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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id()->comment('ID único de la dirección');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuario dueño de la dirección');
            $table->string('full_name')->comment('Nombre completo del destinatario');
            $table->string('phone')->comment('Teléfono de contacto');
            $table->string('address_line_1')->comment('Dirección principal (calle y número)');
            $table->string('address_line_2')->nullable()->comment('Dirección adicional (apto, oficina, etc.)');
            $table->string('city')->comment('Ciudad');
            $table->string('state')->comment('Departamento/Estado');
            $table->string('postal_code')->comment('Código postal');
            $table->string('country')->default('Colombia')->comment('País');
            $table->boolean('is_default')->default(false)->comment('Dirección por defecto del usuario');
            $table->enum('type', ['shipping', 'billing', 'both'])->default('both')->comment('Tipo: envío, facturación o ambos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
