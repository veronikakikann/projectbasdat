<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bukti_penyelesaian', 'catatan_bayar')) {
            Schema::table('bukti_penyelesaian', function (Blueprint $table): void {
                $table->text('catatan_bayar')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('bukti_penyelesaian', function (Blueprint $table): void {
            $table->dropColumn('catatan_bayar');
        });
    }
};
