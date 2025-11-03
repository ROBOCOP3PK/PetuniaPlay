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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ID único del usuario');
            $table->string('name')->comment('Nombre completo del usuario');
            $table->string('email')->unique()->comment('Email único para autenticación');
            $table->timestamp('email_verified_at')->nullable()->comment('Fecha de verificación de email');
            $table->string('password')->comment('Contraseña hasheada');
            $table->rememberToken()->comment('Token para recordar sesión');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary()->comment('Email del usuario que solicita reset');
            $table->string('token')->comment('Token de verificación para reset de contraseña');
            $table->timestamp('created_at')->nullable()->comment('Fecha de creación del token');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary()->comment('ID único de la sesión');
            $table->foreignId('user_id')->nullable()->index()->comment('ID del usuario autenticado (null si guest)');
            $table->string('ip_address', 45)->nullable()->comment('Dirección IP del cliente');
            $table->text('user_agent')->nullable()->comment('User agent del navegador');
            $table->longText('payload')->comment('Datos serializados de la sesión');
            $table->integer('last_activity')->index()->comment('Timestamp de última actividad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
