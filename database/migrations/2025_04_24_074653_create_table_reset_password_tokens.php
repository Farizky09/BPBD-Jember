<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reset_password_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreign('email')->references('email')->on('users');
            $table->text('token');
            $table->dateTime('expires_at');
            $table->boolean('is_used');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_reset_password_tokens');
    }
};
