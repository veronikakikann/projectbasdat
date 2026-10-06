<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pekerjaan', function (Blueprint $table) {
            $table->id('id_pekerjaan');
            $table->foreignId('id_pemberi')->constrained('pemberi_kerja', 'id_pemberi');
            $table->foreignId('id_keahlian')->constrained('keahlian', 'id_keahlian');
            $table->string('nama_pekerjaan', 150);
            $table->text('deskripsi');
            $table->decimal('upah', 12, 2);
            $table->text('lokasi');
            $table->decimal('latitude', 9, 6)->nullable();   // opsional, dipakai nanti untuk filter radius
            $table->decimal('longitude', 9, 6)->nullable();
            $table->date('tanggal_pengerjaan');
            $table->unsignedSmallInteger('jumlah_pekerja')->default(1);
            $table->text('persyaratan')->nullable();
            $table->enum('status_pekerjaan', ['tersedia', 'penuh', 'sedang_dikerjakan', 'selesai', 'ditutup'])->default('tersedia');
            $table->timestamp('tanggal_posting')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pekerjaan');
    }
};
