<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemberikerja', function (Blueprint $table) {
            $table->string('file_ktp')->nullable()->after('nik');
        });

        Schema::table('pencarikerja', function (Blueprint $table) {
            $table->string('file_ktp')->nullable()->after('nik');
        });
    }

    public function down(): void
    {
        Schema::table('pemberikerja', function (Blueprint $table) {
            $table->dropColumn('file_ktp');
        });

        Schema::table('pencarikerja', function (Blueprint $table) {
            $table->dropColumn('file_ktp');
        });
    }
};