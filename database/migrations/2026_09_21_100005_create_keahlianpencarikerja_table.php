<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keahlianpencarikerja', function (Blueprint $table) {
            $table->foreignId('id_pencari')->constrained('pencarikerja', 'id_pencari');
            $table->foreignId('id_keahlian')->constrained('keahlian', 'id_keahlian');
            $table->string('file_surat_rekomendasi', 255);
            $table->enum('status_verifikasi_keahlian', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->date('tanggal_upload');

            $table->primary(['id_pencari', 'id_keahlian']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keahlianpencarikerja');
    }
};
