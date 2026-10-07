<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukti_penyelesaian', function (Blueprint $table) {
            $table->id('id_bukti');

            $table->unsignedBigInteger('id_lamaran')->unique();

            $table->string('foto_bukti_kerja')->nullable();
            $table->string('foto_bukti_bayar')->nullable();

            $table->text('catatan')->nullable();

            $table->dateTime('tanggal_upload')->nullable();

            $table->foreign('id_lamaran')
                ->references('id_lamaran')
                ->on('lamaran')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_penyelesaian');
    }
};