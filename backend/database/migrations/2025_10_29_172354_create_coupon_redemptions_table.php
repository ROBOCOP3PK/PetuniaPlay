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
        Schema::create('coupon_redemptions', function (Blueprint $table) {
            $table->id()->comment('ID único del uso del cupón');
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade')->comment('Cupón utilizado');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuario que usó el cupón');
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->comment('Orden donde se aplicó el cupón');
            $table->decimal('discount_amount', 10, 2)->comment('Monto del descuento aplicado');
            $table->timestamps();

            // Índice compuesto para consultas rápidas de usos por cupón y usuario
            $table->index(['coupon_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_redemptions');
    }
};
