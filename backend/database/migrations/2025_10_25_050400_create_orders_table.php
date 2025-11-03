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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->comment('ID único de la orden');
            $table->string('order_number')->unique()->comment('Número de orden único (ej: PP-ABC123)');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Cliente que realizó la orden');
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->onDelete('set null')->comment('Dirección de envío');
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->onDelete('set null')->comment('Dirección de facturación');
            $table->decimal('subtotal', 10, 2)->comment('Subtotal sin impuestos ni envío');
            $table->decimal('tax', 10, 2)->default(0)->comment('Impuestos aplicados');
            $table->decimal('shipping_cost', 10, 2)->default(0)->comment('Costo de envío');
            $table->decimal('discount', 10, 2)->default(0)->comment('Descuento aplicado (cupones)');
            $table->decimal('total', 10, 2)->comment('Total final de la orden');
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending')->comment('Estado del pedido');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')->comment('Estado del pago');
            $table->text('notes')->nullable()->comment('Notas adicionales del pedido');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
