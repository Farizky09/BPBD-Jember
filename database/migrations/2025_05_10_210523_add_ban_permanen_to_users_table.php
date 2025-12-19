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
            $table->dropColumn('banned');
            $table->boolean('is_banned')->default(false);
            $table->boolean('is_permanent_ban')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_banned');
            $table->dropColumn('is_permanent_ban');
            $table->enum('banned', ['not_banned', 'temporary_ban', 'permanent_ban'])->default('not_banned');
            // $table->date('banned_until')->nullable();
        });
    }
};
