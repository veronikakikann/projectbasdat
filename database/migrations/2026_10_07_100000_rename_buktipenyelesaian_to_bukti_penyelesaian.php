<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Database lama dibuat saat tabelnya masih bernama `buktipenyelesaian`,
 * sedangkan kode sekarang memakai `bukti_penyelesaian`. Migration ini
 * menyamakan namanya. Di database baru (yang sudah memakai nama baru)
 * migration ini tidak melakukan apa-apa.
 *
 * Nama file sengaja diberi tanggal SEBELUM 2026_10_07_142257 supaya
 * dijalankan lebih dulu daripada migration yang butuh tabel ini.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('buktipenyelesaian') && ! Schema::hasTable('bukti_penyelesaian')) {
            Schema::rename('buktipenyelesaian', 'bukti_penyelesaian');
        }
    }

    public function down(): void
    {
        // sengaja kosong
    }
};
