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
            $table->dropColumn('id_confirm_reports');
            $table->unsignedBigInteger('confirm_report_id')->after('id');
            $table->foreign('confirm_report_id')
                ->references('id')
                ->on('confirm_reports')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('disaster_impacts', function (Blueprint $table) {

            $table->dropForeign(['confirm_report_id']);
            $table->dropColumn('confirm_report_id');
            $table->unsignedBigInteger('id_confirm_reports')->after('id');
        });
    }
};
