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
        Schema::table('animal_sections', function (Blueprint $table) {
            $table->string('image')->nullable()->after('icon')->comment('Ruta de imagen grande para sección (ej: sections/seccion_perros.jpg)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animal_sections', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
