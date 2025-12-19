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
        Schema::table('disaster_impacts', function (Blueprint $table) {
            $table->integer('lightly_damaged_houses')->nullable()->change();
            $table->integer('moderately_damaged_houses')->nullable()->change();
            $table->integer('heavily_damaged_houses')->nullable()->change();
            $table->integer('damaged_public_facilities')->nullable()->change();
            $table->integer('missing_people')->nullable()->change();
            $table->integer('injured_people')->nullable()->change();
            $table->integer('affected_people')->nullable()->change();
            $table->integer('deceased_people')->nullable()->change();
            $table->text('logistic_aid_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disaster_impacts', function (Blueprint $table) {
            $table->integer('lightly_damaged_houses')->nullable(false)->change();
            $table->integer('moderately_damaged_houses')->nullable(false)->change();
            $table->integer('heavily_damaged_houses')->nullable(false)->change();
            $table->integer('damaged_public_facilities')->nullable(false)->change();
            $table->integer('missing_people')->nullable(false)->change();
            $table->integer('injured_people')->nullable(false)->change();
            $table->integer('affected_people')->nullable(false)->change();
            $table->integer('deceased_people')->nullable(false)->change();
            $table->text('logistic_aid_description')->nullable(false)->change();
        });
    }
};
