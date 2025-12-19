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
        Schema::create('disaster_report_documentations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('confirm_report_id');
            $table->text('disaster_chronology')->nullable();
            $table->text('disaster_impact')->nullable();
            $table->text('disaster_response')->nullable();
            $table->foreign('confirm_report_id')
                ->references('id')
                ->on('confirm_reports')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disaster_report_documentations');
    }
};
