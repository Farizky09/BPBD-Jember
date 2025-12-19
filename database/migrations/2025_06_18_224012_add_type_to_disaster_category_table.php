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
        Schema::table('disaster_category', function (Blueprint $table) {
            $table->enum('type', ['kejadian_bencana', 'kejadian_musibah']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disaster_category', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
