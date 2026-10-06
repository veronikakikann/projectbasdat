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
        Schema::table('pekerjaan', function (Blueprint $table) {
            $table->string('nama_pekerjaan')->after('id_keahlian');
            $table->unsignedInteger('jumlah_pekerja')->after('nama_pekerjaan');
            $table->text('persyaratan')->nullable()->after('jumlah_pekerja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pekerjaan', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pekerjaan',
                'jumlah_pekerja',
                'persyaratan',
            ]);
        });
    }
};