<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_configs', function (Blueprint $table) {
            $table->id()->comment('ID de la configuración (solo habrá un registro)');
            $table->string('whatsapp_number')->default('573057594088')->comment('Número de WhatsApp con código de país');
            $table->boolean('whatsapp_enabled')->default(true)->comment('WhatsApp habilitado en el sitio');
            $table->text('whatsapp_message')->comment('Mensaje predeterminado para WhatsApp');
            $table->timestamps();
        });

        // Insertar configuración por defecto
        DB::table('site_configs')->insert([
            'whatsapp_number' => '573057594088',
            'whatsapp_enabled' => true,
            'whatsapp_message' => 'Buen día Tengo una duda con un producto',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_configs');
    }
};
