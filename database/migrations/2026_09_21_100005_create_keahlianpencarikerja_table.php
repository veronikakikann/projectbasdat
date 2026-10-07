<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keahlian_pencari_kerja', function (Blueprint $table) {
            $table->id('id_keahlian_pencari');

            $table->unsignedBigInteger('id_pencari');
            $table->unsignedBigInteger('id_keahlian')->nullable();

            $table->string('judul_keahlian')->nullable();
            $table->text('deskripsi_keahlian')->nullable();

            $table->string('file_surat_rekomendasi')->nullable();

            $table->enum('status_verifikasi_keahlian', [
                'menunggu',
                'terverifikasi',
                'ditolak',
            ])->default('menunggu');

            $table->dateTime('tanggal_upload')->nullable();

            $table->foreign('id_pencari')
                ->references('id_pencari')
                ->on('pencari_kerja')
                ->onDelete('cascade');

            $table->foreign('id_keahlian')
                ->references('id_keahlian')
                ->on('keahlian')
                ->onDelete('set null');

            $table->index('id_pencari');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keahlian_pencari_kerja');
    }
};