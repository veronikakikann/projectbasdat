<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            // polimorfik: id_user merujuk ke id_pencari ATAU id_pemberi tergantung tipe_user,
            // jadi tidak bisa pakai foreignId()->constrained() - divalidasi di level aplikasi/controller.
            $table->unsignedBigInteger('id_user');
            $table->enum('tipe_user', ['pencari_kerja', 'pemberi_kerja']);
            $table->text('isi_pesan');
            $table->boolean('status_baca')->default(false);
            $table->timestamp('tanggal')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
