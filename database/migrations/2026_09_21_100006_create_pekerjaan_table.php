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
            $table->foreignId('id_pemberi')->constrained('pemberikerja', 'id_pemberi');
            $table->foreignId('id_keahlian')->constrained('keahlian', 'id_keahlian');
            $table->text('deskripsi');
            $table->decimal('upah', 12, 2);
            $table->text('lokasi');
            $table->decimal('latitude', 9, 6);
            $table->decimal('longitude', 9, 6);
            $table->date('tanggal_pengerjaan');
            $table->enum('status_pekerjaan', ['tersedia', 'sedang_dikerjakan', 'selesai'])->default('tersedia');
            $table->timestamp('tanggal_posting')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pekerjaan');
    }
};
