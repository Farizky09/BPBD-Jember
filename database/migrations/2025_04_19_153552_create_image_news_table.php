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
        Schema::create('image_news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_news');
            $table->string('iamge_path');
            $table->timestamps();

            // Foreign key constraint ke tabel news
            $table->foreign('id_news')
                ->references('id')
                ->on('news')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_news');
    }
};
