<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah nama tabel jadi pakai underscore agar cocok dengan file alter (file ke-3)
        Schema::create('keahlian_pencari_kerja', function (Blueprint $table) {
            
            // 2. Ubah referensi constrained jadi 'pencari_kerja' agar cocok dengan file ke-1
            $table->foreignId('id_pencari')->constrained('pencari_kerja', 'id_pencari');
            $table->foreignId('id_keahlian')->constrained('keahlian', 'id_keahlian');
            $table->string('file_surat_rekomendasi', 255);
            $table->enum('status_verifikasi_keahlian', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->date('tanggal_upload');

            $table->primary(['id_pencari', 'id_keahlian']);
        });
    }

    public function down(): void
    {
        // Sesuaikan nama saat drop tabel
        Schema::dropIfExists('keahlian_pencari_kerja');
    }
};