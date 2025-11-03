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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id()->comment('ID único de la notificación');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuario que recibe la notificación');
            $table->string('type')->comment('Tipo: order_status, payment, shipment, general, etc.');
            $table->string('title')->comment('Título de la notificación');
            $table->text('message')->comment('Mensaje de la notificación');
            $table->json('data')->nullable()->comment('Datos adicionales (order_id, product_id, etc.)');
            $table->boolean('read')->default(false)->comment('Notificación leída o no');
            $table->timestamp('read_at')->nullable()->comment('Fecha de lectura');
            $table->timestamps();

            $table->index(['user_id', 'read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
