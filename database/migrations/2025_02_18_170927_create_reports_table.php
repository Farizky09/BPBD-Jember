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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->decimal('latitude', 10, 8);  // Latitude: max 10 digits with 8 decimal places
            $table->decimal('longitude', 11, 8); // Longitude: max 11 digits with 8 decimal places
            $table->text('address');
            $table->text('description');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->enum('disaster_level', ['low', 'medium', 'high', 'extreme']);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
