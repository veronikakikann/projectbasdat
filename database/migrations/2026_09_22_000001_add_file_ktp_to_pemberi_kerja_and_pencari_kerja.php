<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemberi_kerja', function (Blueprint $table) {
            $table->string('file_ktp')->nullable()->after('nik');
        });

        Schema::table('pencari_kerja', function (Blueprint $table) {
            $table->string('file_ktp')->nullable()->after('nik');
        });
    }

    public function down(): void
    {
        Schema::table('pemberi_kerja', function (Blueprint $table) {
            $table->dropColumn('file_ktp');
        });

        Schema::table('pencari_kerja', function (Blueprint $table) {
            $table->dropColumn('file_ktp');
        });
    }
};