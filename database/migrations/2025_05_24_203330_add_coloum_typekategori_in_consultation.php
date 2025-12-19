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
        Schema::table('consultation', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->unsignedBigInteger('typekategori_id')->after('id')->nullable();
            $table->foreign('typekategori_id')->references('id')->on('disaster_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultation', function (Blueprint $table) {
            $table->dropColumn('typekategori_id');
            $table->string('name')->after('id');
        });
    }
};
