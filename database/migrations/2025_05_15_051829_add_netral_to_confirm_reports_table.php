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
        Schema::table('confirm_reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'accepted', 'rejected', 'netral'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confirm_reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'accepted', 'rejected'])->change();
        });
    }
};
