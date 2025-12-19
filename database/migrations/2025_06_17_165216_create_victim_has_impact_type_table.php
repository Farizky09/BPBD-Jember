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
        Schema::create('victim_has_impact_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('disaster_victim_id');
            $table->unsignedBigInteger('impact_type_id');
            $table->foreign('disaster_victim_id')
                ->references('id')
                ->on('disaster_victims')
                ->onDelete('cascade');
            $table->foreign('impact_type_id')
                ->references('id')
                ->on('impact_type')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('victim_has_impact_type');
    }
};
