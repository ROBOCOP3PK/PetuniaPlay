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
            $table->id();
            $table->foreignId('loyalty_program_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Ej: "Primera Compra", "Nivel Bronce", etc.
            $table->text('description')->nullable();
            $table->enum('type', ['permanent', 'campaign'])->default('permanent'); // Permanente o campaña temporal
            $table->enum('target_audience', ['all', 'new_only', 'recurring_only'])->default('all'); // Audiencia objetivo
            $table->integer('required_purchases'); // Cantidad de compras necesarias
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null'); // Producto de regalo
            $table->date('start_date')->nullable(); // Para campañas temporales
            $table->date('end_date')->nullable(); // Para campañas temporales
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // Para ordenar recompensas
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
