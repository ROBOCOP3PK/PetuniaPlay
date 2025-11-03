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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id()->comment('ID único del token');
            $table->morphs('tokenable'); // tokenable_type y tokenable_id (polimórfico para User)
            $table->text('name')->comment('Nombre del token (ej: web-token, mobile-app)');
            $table->string('token', 64)->unique()->comment('Token hash único');
            $table->text('abilities')->nullable()->comment('Permisos del token (JSON)');
            $table->timestamp('last_used_at')->nullable()->comment('Última vez que se usó el token');
            $table->timestamp('expires_at')->nullable()->index()->comment('Fecha de expiración del token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
