<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buktipenyelesaian', function (Blueprint $table) {
            $table->id('id_bukti');
            $table->foreignId('id_lamaran')->unique()->constrained('lamaran', 'id_lamaran');
            $table->string('foto_bukti_kerja', 255);
            $table->string('foto_bukti_bayar', 255);
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_upload')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buktipenyelesaian');
    }
};
