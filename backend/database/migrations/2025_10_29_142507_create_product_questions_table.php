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
        Schema::create('product_questions', function (Blueprint $table) {
            $table->id()->comment('ID único de la pregunta');
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->comment('Producto sobre el que se pregunta');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuario que hace la pregunta');
            $table->text('question')->comment('Pregunta del usuario');
            $table->text('answer')->nullable()->comment('Respuesta del vendedor/manager');
            $table->foreignId('answered_by')->nullable()->constrained('users')->onDelete('set null')->comment('Usuario que respondió la pregunta');
            $table->timestamp('answered_at')->nullable()->comment('Fecha y hora de la respuesta');
            $table->boolean('is_public')->default(false)->comment('Pregunta visible públicamente (solo cuando se responde)');
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index('product_id');
            $table->index('user_id');
            $table->index('is_public');
            $table->index('answered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_questions');
    }
};
