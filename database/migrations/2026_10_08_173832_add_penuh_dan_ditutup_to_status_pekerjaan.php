<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pekerjaan
            MODIFY status_pekerjaan
            ENUM(
                'tersedia',
                'penuh',
                'sedang_dikerjakan',
                'ditutup',
                'selesai'
            )
            NOT NULL
            DEFAULT 'tersedia'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE pekerjaan
            MODIFY status_pekerjaan
            ENUM(
                'tersedia',
                'sedang_dikerjakan',
                'selesai'
            )
            NOT NULL
            DEFAULT 'tersedia'
        ");
    }
};