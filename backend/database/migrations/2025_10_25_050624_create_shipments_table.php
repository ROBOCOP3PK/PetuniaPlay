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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id()->comment('ID único del envío');
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->comment('Orden asociada al envío');
            $table->string('tracking_number')->unique()->nullable()->comment('Número de guía de rastreo');
            $table->string('carrier')->nullable()->comment('Empresa transportadora (ej: Servientrega, Coordinadora)');
            $table->enum('status', ['pending', 'in_transit', 'delivered', 'returned'])->default('pending')->comment('Estado del envío');
            $table->timestamp('shipped_at')->nullable()->comment('Fecha de envío');
            $table->timestamp('delivered_at')->nullable()->comment('Fecha de entrega');
            $table->text('notes')->nullable()->comment('Notas adicionales del envío');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
