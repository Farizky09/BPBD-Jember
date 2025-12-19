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
            $table->dropColumn(['is_banned', 'banned_until', 'is_permanent_ban']);
            $table->enum('is_banned', ['none', 'temporary', 'permanent'])->default('none')->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_banned']);
            $table->boolean('is_banned')->default(false)->after('is_active');
            $table->timestamp('banned_until')->nullable()->after('is_banned');
            $table->boolean('is_permanent_ban')->default(false)->after('banned_until');
        });
    }
};
