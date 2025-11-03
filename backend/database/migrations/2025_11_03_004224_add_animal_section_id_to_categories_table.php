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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('animal_section_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('animal_sections')
                  ->onDelete('cascade')
                  ->comment('Sección de animal a la que pertenece esta categoría');

            // Índice para optimización de queries
            $table->index('animal_section_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['animal_section_id']);
            $table->dropIndex(['animal_section_id']);
            $table->dropColumn('animal_section_id');
        });
    }
};
