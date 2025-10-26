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
            $table->string('phone')->nullable()->after('email');
            $table->string('document', 50)->nullable()->after('phone');
            $table->enum('role', ['customer', 'manager', 'admin'])->default('customer')->after('password');
            $table->date('birth_date')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('birth_date');
            $table->boolean('is_active')->default(true)->after('avatar');
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
