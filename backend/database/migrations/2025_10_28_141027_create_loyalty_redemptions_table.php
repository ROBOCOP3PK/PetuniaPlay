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
        Schema::create('loyalty_redemptions', function (Blueprint $table) {
            $table->id()->comment('ID único del canje');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuario que canjea la recompensa');
            $table->foreignId('loyalty_reward_id')->constrained()->onDelete('cascade')->comment('Recompensa canjeada');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null')->comment('Orden generada por el canje');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending')->comment('Estado del canje');
            $table->integer('purchases_at_redemption')->comment('Compras que tenía el usuario al momento del canje');
            $table->timestamp('redeemed_at')->nullable()->comment('Fecha y hora del canje');
            $table->timestamp('completed_at')->nullable()->comment('Fecha de completado (orden generada)');
            $table->text('notes')->nullable()->comment('Notas adicionales del canje');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_redemptions');
    }
};
