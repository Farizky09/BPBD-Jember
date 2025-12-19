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
        Schema::create('confirm_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('admin_id');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();


            // Foreign key constraint
            $table->foreign('report_id')
                ->references('id')
                ->on('reports')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirm_reports');
    }
};
