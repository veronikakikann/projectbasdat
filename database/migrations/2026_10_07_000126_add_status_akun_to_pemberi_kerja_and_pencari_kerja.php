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
        // Tambahkan status_akun ke tabel pemberi_kerja
        if (!Schema::hasColumn('pemberi_kerja', 'status_akun')) {
            Schema::table('pemberi_kerja', function (Blueprint $table) {
                $table->enum('status_akun', ['aktif', 'nonaktif'])
                    ->default('aktif')
                    ->after('status_verifikasi');
            });
        }

        // Tambahkan status_akun ke tabel pencari_kerja
        if (!Schema::hasColumn('pencari_kerja', 'status_akun')) {
            Schema::table('pencari_kerja', function (Blueprint $table) {
                $table->enum('status_akun', ['aktif', 'nonaktif'])
                    ->default('aktif')
                    ->after('status_verifikasi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pemberi_kerja', 'status_akun')) {
            Schema::table('pemberi_kerja', function (Blueprint $table) {
                $table->dropColumn('status_akun');
            });
        }

        if (Schema::hasColumn('pencari_kerja', 'status_akun')) {
            Schema::table('pencari_kerja', function (Blueprint $table) {
                $table->dropColumn('status_akun');
            });
        }
    }
};