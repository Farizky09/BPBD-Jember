<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bencana');
            $table->string('gambar'); // Menyimpan path gambar
            $table->text('keterangan_bencana');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporans');
    }
};
