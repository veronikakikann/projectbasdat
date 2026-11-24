<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyamakan database lama dengan struktur yang diharapkan kode terbaru.
 *
 * Penyebab: file migration "create_*" sempat diedit setelah dijalankan di
 * beberapa laptop, jadi perubahannya tidak pernah sampai ke database lama.
 * Aman dijalankan di database baru maupun lama (tidak mengubah data).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Pencari mengunggah bukti kerja lebih dulu, bukti bayar menyusul dari
        // pemberi kerja, jadi kedua kolom ini harus boleh kosong.
        if (Schema::hasTable('bukti_penyelesaian')) {
            Schema::table('bukti_penyelesaian', function (Blueprint $table) {
                $table->string('foto_bukti_kerja', 255)->nullable()->change();
                $table->string('foto_bukti_bayar', 255)->nullable()->change();
                $table->dateTime('tanggal_upload')->nullable()->change();
            });
        }

        // Keahlian manual: judul/surat boleh kosong sebelum diisi pencari.
        if (Schema::hasTable('keahlian_pencari_kerja')) {
            Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
                $table->string('judul_keahlian', 255)->nullable()->change();
                $table->string('file_surat_rekomendasi', 255)->nullable()->change();
                $table->dateTime('tanggal_upload')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // sengaja kosong
    }
};
