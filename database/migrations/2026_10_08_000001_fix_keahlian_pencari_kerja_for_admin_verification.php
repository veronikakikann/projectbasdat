<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyamakan tabel keahlian_pencari_kerja dengan yang dipakai fitur
 * "Verifikasi Keahlian" admin:
 *   - kolom id_keahlian_pencari (primary key baru, auto increment)
 *   - kolom judul_keahlian & deskripsi_keahlian
 *   - id_keahlian boleh NULL (diisi admin saat memverifikasi)
 *
 * Aman dijalankan berulang: setiap langkah dicek dulu sebelum dikerjakan.
 * Data lama tidak dihapus.
 */
return new class extends Migration
{
    private const TABEL = 'keahlian_pencari_kerja';

    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql' || ! Schema::hasTable(self::TABEL)) {
            return;
        }

        $db = DB::getDatabaseName();
        $t = self::TABEL;

        // 1. Kolom teks pengajuan keahlian
        if (! Schema::hasColumn($t, 'judul_keahlian')) {
            Schema::table($t, fn (Blueprint $b) => $b->string('judul_keahlian', 255)->nullable()->after('id_pencari'));
        }
        if (! Schema::hasColumn($t, 'deskripsi_keahlian')) {
            Schema::table($t, fn (Blueprint $b) => $b->text('deskripsi_keahlian')->nullable()->after('judul_keahlian'));
        }

        // 2. Primary key baru id_keahlian_pencari
        if (! Schema::hasColumn($t, 'id_keahlian_pencari')) {
            // Foreign key id_keahlian dilepas dulu (nanti dipasang lagi).
            foreach ($this->namaForeignKey($db, $t, 'id_keahlian') as $fk) {
                DB::statement("ALTER TABLE `$t` DROP FOREIGN KEY `$fk`");
            }

            // Foreign key id_pencari butuh index sendiri setelah primary key lama dilepas.
            $adaIndexPencari = DB::selectOne(
                "SELECT 1 AS ada FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = 'id_pencari'
                   AND SEQ_IN_INDEX = 1 AND INDEX_NAME <> 'PRIMARY' LIMIT 1",
                [$db, $t]
            );
            if (! $adaIndexPencari) {
                DB::statement("ALTER TABLE `$t` ADD INDEX `idx_kp_pencari` (`id_pencari`)");
            }

            $adaPrimary = DB::selectOne(
                "SELECT 1 AS ada FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = 'PRIMARY' LIMIT 1",
                [$db, $t]
            );
            if ($adaPrimary) {
                DB::statement("ALTER TABLE `$t` DROP PRIMARY KEY");
            }

            DB::statement("ALTER TABLE `$t` ADD COLUMN `id_keahlian_pencari` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
        }

        // 3. id_keahlian boleh NULL
        $kolom = DB::selectOne(
            "SELECT COLUMN_TYPE AS tipe, IS_NULLABLE AS boleh_null FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = 'id_keahlian'",
            [$db, $t]
        );
        if ($kolom && $kolom->boleh_null === 'NO') {
            foreach ($this->namaForeignKey($db, $t, 'id_keahlian') as $fk) {
                DB::statement("ALTER TABLE `$t` DROP FOREIGN KEY `$fk`");
            }
            DB::statement("ALTER TABLE `$t` MODIFY `id_keahlian` {$kolom->tipe} NULL");
        }

        // 4. Pasang lagi foreign key id_keahlian -> keahlian (kalau belum ada)
        if (count($this->namaForeignKey($db, $t, 'id_keahlian')) === 0) {
            try {
                DB::statement(
                    "ALTER TABLE `$t` ADD CONSTRAINT `fk_kp_keahlian` FOREIGN KEY (`id_keahlian`)
                     REFERENCES `keahlian` (`id_keahlian`) ON DELETE CASCADE ON UPDATE CASCADE"
                );
            } catch (\Throwable $e) {
                // Tipe kolom bisa beda dengan tabel keahlian. Foreign key hanya pengaman,
                // fitur verifikasi tetap jalan tanpa itu.
            }
        }
    }

    public function down(): void
    {
        // Sengaja kosong: mengembalikan struktur lama akan membuang data pengajuan keahlian.
    }

    /** @return list<string> */
    private function namaForeignKey(string $db, string $tabel, string $kolom): array
    {
        return array_map(
            fn ($r) => $r->nama,
            DB::select(
                "SELECT CONSTRAINT_NAME AS nama FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
                   AND REFERENCED_TABLE_NAME IS NOT NULL",
                [$db, $tabel, $kolom]
            )
        );
    }
};
