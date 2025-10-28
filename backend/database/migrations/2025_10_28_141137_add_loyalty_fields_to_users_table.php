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
            $table->integer('completed_purchases')->default(0)->after('email_verified_at');
            $table->timestamp('first_purchase_at')->nullable()->after('completed_purchases');
            $table->timestamp('last_purchase_at')->nullable()->after('first_purchase_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['completed_purchases', 'first_purchase_at', 'last_purchase_at']);
        });
    }
};
