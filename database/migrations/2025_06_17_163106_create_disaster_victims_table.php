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
        Schema::create('disaster_victims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('disaster_impact_id');
            $table->string('fullname');
            $table->string('nik');
            $table->string('kk');
            $table->enum('gender', ['male', 'female']);
            $table->integer('age');
            $table->enum('family_status', ['ayah', 'ibu', 'anak']);
            $table->string('phone_number');
            $table->string('birth_place');
            $table->date('birth_date');

            $table->foreign('disaster_impact_id')
                ->references('id')
                ->on('disaster_impacts')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disaster_victims');
    }
};
