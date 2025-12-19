<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Hapus kolom yang lama
            $table->dropColumn(['status', 'disaster_level', 'reject_reason']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'process', 'done'])->default('pending');

            $table->string('city');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
         
            $table->dropColumn(['status', 'city']);
        });

        Schema::table('reports', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->enum('disaster_level', ['low', 'medium', 'high', 'extreme']);
            $table->text('reject_reason')->nullable();
        });
    }
};
