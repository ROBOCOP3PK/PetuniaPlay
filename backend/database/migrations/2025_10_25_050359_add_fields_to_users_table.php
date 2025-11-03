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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email')->comment('Teléfono del usuario');
            $table->string('document', 50)->nullable()->after('phone')->comment('Documento de identidad (cédula, pasaporte)');
            $table->enum('role', ['customer', 'manager', 'admin'])->default('customer')->after('password')->comment('Rol del usuario en el sistema');
            $table->date('birth_date')->nullable()->after('role')->comment('Fecha de nacimiento');
            $table->string('avatar')->nullable()->after('birth_date')->comment('URL de la foto de perfil');
            $table->boolean('is_active')->default(true)->after('avatar')->comment('Usuario activo/bloqueado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'document', 'role', 'birth_date', 'avatar', 'is_active']);
        });
    }
};
