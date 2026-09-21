<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lamaran', function (Blueprint $table) {
            $table->id('id_lamaran');
            $table->foreignId('id_pekerjaan')->constrained('pekerjaan', 'id_pekerjaan');
            $table->foreignId('id_pencari')->constrained('pencarikerja', 'id_pencari');
            $table->enum('status_lamaran', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->timestamp('tanggal_submit')->useCurrent();

            $table->unique(['id_pekerjaan', 'id_pencari']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lamaran');
    }
};
