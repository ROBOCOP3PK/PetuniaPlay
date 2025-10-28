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
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('loyalty_reward_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null'); // Orden generada por el canje
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->integer('purchases_at_redemption'); // Cantidad de compras que tenía al momento del canje
            $table->timestamp('redeemed_at')->nullable(); // Fecha de canje
            $table->timestamp('completed_at')->nullable(); // Fecha de completado (cuando se genera la orden)
            $table->text('notes')->nullable(); // Notas adicionales
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
