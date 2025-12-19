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
        Schema::table('disaster_impacts', function (Blueprint $table) {
            $table->integer('affected_babies')->nullable()->after('affected_people');
            $table->integer('affected_elderly')->nullable()->after('affected_babies');
            $table->integer('affected_disabled')->nullable()->after('affected_elderly');
            $table->integer('affected_pregnant_women')->nullable()->after('affected_disabled');
            $table->integer('affected_general')->nullable()->after('affected_pregnant_women');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disaster_impacts', function (Blueprint $table) {
            $table->dropColumn('affected_babies');
            $table->dropColumn('affected_elderly');
            $table->dropColumn('affected_disabled');
            $table->dropColumn('affected_pregnant_women');
            $table->dropColumn('affected_general');
        });
    }
};
