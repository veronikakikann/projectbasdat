<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rating', function (Blueprint $table) {
            $table->id('id_rating');
            $table->foreignId('id_lamaran')->constrained('lamaran', 'id_lamaran');
            // pemberi_rating & penerima_rating polimorfik (bisa id_pencari atau id_pemberi
            // tergantung arah_rating) - divalidasi di level aplikasi/controller.
            $table->unsignedBigInteger('pemberi_rating');
            $table->unsignedBigInteger('penerima_rating');
            $table->enum('arah_rating', ['pekerja_ke_pemberi', 'pemberi_ke_pekerja']);
            $table->unsignedTinyInteger('skor');
            $table->string('kategori_komentar', 100)->nullable();
            $table->timestamp('tanggal_rating')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
