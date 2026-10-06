<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat index terpisah untuk id_pencari
        // karena foreign key id_pencari membutuhkannya
        // setelah primary key lama dilepas.
        Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
            $table->index('id_pencari', 'idx_kp_pencari');
        });

        // Lepas struktur primary key lama dan foreign key keahlian
        Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
            $table->dropForeign('fk_kp_keahlian');
            $table->dropPrimary();

            $table->id('id_keahlian_pencari')
                ->first();

            $table->string('judul_keahlian', 255)
                ->after('id_pencari');

            $table->text('deskripsi_keahlian')
                ->nullable()
                ->after('judul_keahlian');

            $table->integer('id_keahlian')
                ->nullable()
                ->change();
        });

        // Pasang kembali foreign key id_keahlian,
        // tetapi sekarang id_keahlian boleh NULL
        // sampai Admin menentukan kategorinya.
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
        // Lepas foreign key id_keahlian
        Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
            $table->dropForeign('fk_kp_keahlian');
            $table->dropPrimary();

            $table->dropColumn([
                'id_keahlian_pencari',
                'judul_keahlian',
                'deskripsi_keahlian',
            ]);

            $table->integer('id_keahlian')
                ->nullable(false)
                ->change();

            $table->primary(['id_pencari', 'id_keahlian']);
        });

        // Pasang kembali foreign key id_keahlian
        Schema::table('keahlian_pencari_kerja', function (Blueprint $table) {
            $table->foreign('id_keahlian', 'fk_kp_keahlian')
                ->references('id_keahlian')
                ->on('keahlian')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
};