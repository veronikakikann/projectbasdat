<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $lamaranKeyType = Schema::getColumnType('lamaran', 'id_lamaran', fullDefinition: true);
        $unsigned = str_contains($lamaranKeyType, 'unsigned');

        Schema::create('pesan_lamaran', function (Blueprint $table) use ($lamaranKeyType, $unsigned) {
            $table->id('id_pesan');
            if (str_starts_with($lamaranKeyType, 'bigint')) {
                $table->bigInteger('id_lamaran', unsigned: $unsigned);
            } else {
                $table->integer('id_lamaran', unsigned: $unsigned);
            }
            $table->foreign('id_lamaran')->references('id_lamaran')->on('lamaran')->cascadeOnDelete();
            $table->enum('peran_pengirim', ['pemberi_kerja', 'pencari_kerja']);
            $table->text('pesan');
            $table->timestamp('dikirim_pada');
            $table->index(['id_lamaran', 'id_pesan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_lamaran');
    }
};
