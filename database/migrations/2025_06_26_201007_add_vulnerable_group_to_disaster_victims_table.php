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
        Schema::table('disaster_victims', function (Blueprint $table) {
            $table->enum('vulnerable_group', [
                'elderly',
                'babies',
                'disabled',
                'pregnant_women',
                'general'
            ])
                ->default('general');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disaster_victims', function (Blueprint $table) {
            $table->dropColumn('vulnerable_group');
        });
    }
};
