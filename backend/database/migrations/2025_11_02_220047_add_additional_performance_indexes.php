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
        // Índices para reviews - mejorar queries de estadísticas y aprobaciones
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'is_approved']);
            $table->index(['product_id', 'rating']);
            $table->index('is_approved');
            $table->index('is_verified_purchase');
        });

        // Índices para product_images - mejorar búsqueda de imagen primaria
        Schema::table('product_images', function (Blueprint $table) {
            $table->index(['product_id', 'is_primary']);
            $table->index('is_primary');
        });

        // Índices para order_items - mejorar reportes y análisis
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('product_id');
            $table->index(['order_id', 'product_id']);
        });

        // Índices para wishlist_items - mejorar búsquedas de wishlist
        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->index(['user_id', 'product_id']);
            $table->index('product_id');
        });

        // Índices para addresses - mejorar búsquedas de direcciones por tipo y default
        Schema::table('addresses', function (Blueprint $table) {
            $table->index(['user_id', 'is_default']);
            $table->index('type');
        });

        // Índices compuestos adicionales para products - búsquedas con stock
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'is_featured', 'stock']);
            $table->index(['category_id', 'is_active', 'stock']);
        });

        // Índices para coupons - validación de cupones
        Schema::table('coupons', function (Blueprint $table) {
            $table->index('is_active');
            $table->index(['code', 'is_active']);
            $table->index(['is_active', 'valid_from', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar índices de reviews
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'is_approved']);
            $table->dropIndex(['product_id', 'rating']);
            $table->dropIndex(['is_approved']);
            $table->dropIndex(['is_verified_purchase']);
        });

        // Eliminar índices de product_images
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'is_primary']);
            $table->dropIndex(['is_primary']);
        });

        // Eliminar índices de order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['order_id', 'product_id']);
        });

        // Eliminar índices de wishlist_items
        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'product_id']);
            $table->dropIndex(['product_id']);
        });

        // Eliminar índices de addresses
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_default']);
            $table->dropIndex(['type']);
        });

        // Eliminar índices adicionales de products
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'is_featured', 'stock']);
            $table->dropIndex(['category_id', 'is_active', 'stock']);
        });

        // Eliminar índices de coupons
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['code', 'is_active']);
            $table->dropIndex(['is_active', 'valid_from', 'valid_until']);
        });
    }
};
