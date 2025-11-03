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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre del remitente');
            $table->string('email')->comment('Email del remitente');
            $table->string('phone')->nullable()->comment('Teléfono del remitente');
            $table->string('subject')->comment('Asunto del mensaje');
            $table->text('message')->comment('Contenido del mensaje');
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending')->comment('Estado del mensaje');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete()->comment('Usuario que resolvió el mensaje');
            $table->timestamp('resolved_at')->nullable()->comment('Fecha de resolución');
            $table->text('admin_notes')->nullable()->comment('Notas internas del admin');
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
