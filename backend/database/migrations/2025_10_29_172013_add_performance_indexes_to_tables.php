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
        // Agregar índices a la tabla products
        Schema::table('products', function (Blueprint $table) {
            $table->index('brand');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index(['category_id', 'is_active']);
        });

        // Agregar índices a la tabla orders
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('payment_status');
            $table->index(['status', 'payment_status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar índices de la tabla products
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['brand']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured']);
            // Skip composite index if used by foreign key
            if (Schema::hasColumn('products', 'category_id')) {
                try {
                    $table->dropIndex(['category_id', 'is_active']);
                } catch (\Exception $e) {
                    // Ignore if index is used by foreign key constraint
                }
            }
        });

        // Eliminar índices de la tabla orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['status', 'payment_status']);
            $table->dropIndex(['created_at']);
        });
    }
};
