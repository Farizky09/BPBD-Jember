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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_confirm_reports');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->enum('status', ['draft', 'published', 'takedown'])->default('draft');
            $table->foreign('id_confirm_reports')->references('id')->on('confirm_reports')->onDelete('cascade');
            $table->date('published_at')->nullable();
            $table->date('takedown_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
