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
        Schema::create('image_disaster_report', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('disaster_report_documentation_id');
            $table->string('image_path');

            $table->foreign('disaster_report_documentation_id')
                ->references('id')
                ->on('disaster_report_documentations')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_disaster_report');
    }
};
