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
        Schema::create('disaster_impacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_confirm_reports');
            $table->integer('lightly_damaged_houses');
            $table->integer('moderately_damaged_houses');
            $table->integer('heavily_damaged_houses');
            $table->integer('damaged_public_facilities');
            $table->integer('missing_people');
            $table->integer('injured_people');
            $table->integer('affected_people');
            $table->integer('deceased_people');
            $table->text('logistic_aid_description');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disaster_impacts');
    }
};
