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
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->comment('ID único del pago');
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->comment('Orden asociada al pago');
            $table->string('transaction_id')->unique()->comment('ID de transacción de la pasarela de pago');
            $table->enum('payment_method', ['stripe', 'payu', 'cash_on_delivery'])->default('stripe')->comment('Método de pago utilizado');
            $table->decimal('amount', 10, 2)->comment('Monto del pago');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending')->comment('Estado del pago');
            $table->text('payment_details')->nullable()->comment('Detalles adicionales del pago (JSON)');
            $table->timestamp('paid_at')->nullable()->comment('Fecha y hora del pago exitoso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
