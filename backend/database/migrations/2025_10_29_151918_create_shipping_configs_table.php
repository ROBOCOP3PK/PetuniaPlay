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
        Schema::create('shipping_configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('night_delivery_enabled')->default(true);
            $table->time('night_delivery_start_time')->default('21:00:00'); // 9:00 PM
            $table->time('night_delivery_end_time')->default('02:00:00'); // 2:00 AM
            $table->integer('free_shipping_min_items')->default(4); // Envío gratis con 4+ items
            $table->decimal('standard_shipping_cost', 10, 2)->default(10000); // Costo estándar
            $table->timestamps();
        });

        // Insertar configuración por defecto
        DB::table('shipping_configs')->insert([
            'night_delivery_enabled' => true,
            'night_delivery_start_time' => '21:00:00',
            'night_delivery_end_time' => '02:00:00',
            'free_shipping_min_items' => 4,
            'standard_shipping_cost' => 10000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_configs');
    }
};
