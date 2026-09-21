<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemberikerja', function (Blueprint $table) {
            $table->id('id_pemberi');
            $table->string('nik', 16)->unique();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('no_telpon', 15);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('foto_profil', 255)->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->foreignId('id_admin')->nullable()->constrained('admin', 'id_admin');
            $table->date('tanggal_daftar');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemberikerja');
    }
};
