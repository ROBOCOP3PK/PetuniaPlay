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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id()->comment('ID único del cupón');
            $table->string('code')->unique()->comment('Código del cupón (ej: VERANO2025)');
            $table->enum('type', ['percentage', 'fixed'])->default('percentage')->comment('Tipo de descuento: porcentaje o monto fijo');
            $table->decimal('value', 10, 2)->comment('Valor del descuento (ej: 10 = 10% o $10)');
            $table->decimal('min_purchase_amount', 10, 2)->nullable()->comment('Monto mínimo de compra para aplicar');
            $table->integer('usage_limit')->nullable()->comment('Límite total de usos del cupón');
            $table->integer('usage_count')->default(0)->comment('Contador de veces usado');
            $table->timestamp('valid_from')->nullable()->comment('Fecha desde cuando es válido');
            $table->timestamp('valid_until')->nullable()->comment('Fecha hasta cuando es válido');
            $table->boolean('is_active')->default(true)->comment('Cupón activo/inactivo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
