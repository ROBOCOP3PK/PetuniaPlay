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
        Schema::create('loyalty_rewards', function (Blueprint $table) {
            $table->id()->comment('ID único de la recompensa');
            $table->foreignId('loyalty_program_id')->constrained()->onDelete('cascade')->comment('Programa de fidelidad al que pertenece');
            $table->string('name')->comment('Nombre de la recompensa (ej: Primera Compra, Nivel Bronce)');
            $table->text('description')->nullable()->comment('Descripción de la recompensa');
            $table->enum('type', ['permanent', 'campaign'])->default('permanent')->comment('Tipo: permanente o campaña temporal');
            $table->enum('target_audience', ['all', 'new_only', 'recurring_only'])->default('all')->comment('Audiencia objetivo de la recompensa');
            $table->integer('required_purchases')->comment('Cantidad de compras necesarias para canjear');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null')->comment('Producto de regalo al canjear');
            $table->date('start_date')->nullable()->comment('Fecha de inicio (para campañas temporales)');
            $table->date('end_date')->nullable()->comment('Fecha de fin (para campañas temporales)');
            $table->boolean('is_active')->default(true)->comment('Recompensa activa/disponible');
            $table->integer('priority')->default(0)->comment('Prioridad para ordenar recompensas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_rewards');
    }
};
