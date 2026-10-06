<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat index terpisah
        Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
            $table->index('id_pencari', 'idx_kp_pencari');
        });

        // 2. Lepas struktur primary key lama dan foreign key keahlian
        Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
            // Gunakan array ['id_keahlian'] agar Laravel mencari nama defaultnya secara otomatis
            $table->dropForeign(['id_keahlian']);
            $table->dropPrimary();

            $table->id('id_keahlian_pencari')->first();

            $table->string('judul_keahlian', 255)->after('id_pencari');

            $table->text('deskripsi_keahlian')->nullable()->after('judul_keahlian');

            // Ubah id_keahlian agar bisa NULL
            // Pastikan tipe datanya adalah unsignedBigInteger agar cocok dengan foreignId
            $table->unsignedBigInteger('id_keahlian')->nullable()->change();
        });

        // 3. Pasang kembali foreign key dengan nama custom 'fk_kp_keahlian'
        Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
            $table->foreign('id_keahlian', 'fk_kp_keahlian')
                ->references('id_keahlian')
                ->on('keahlian')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        // 1. Lepas foreign key id_keahlian yang bernama fk_kp_keahlian
        Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
            $table->dropForeign('fk_kp_keahlian');
            $table->dropPrimary();

            $table->dropColumn([
                'id_keahlian_pencari',
                'judul_keahlian',
                'deskripsi_keahlian',
            ]);

            $table->unsignedBigInteger('id_keahlian')->nullable(false)->change();

            $table->primary(['id_pencari', 'id_keahlian']);
        });

        // 2. Pasang kembali foreign key id_keahlian tanpa nama custom (kembali ke default)
        Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
            $table->foreign('id_keahlian')
                ->references('id_keahlian')
                ->on('keahlian')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
};