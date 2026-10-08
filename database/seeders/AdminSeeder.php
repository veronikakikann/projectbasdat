<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Membuat akun admin awal supaya bisa login ke dashboard admin.
 * Jalankan:  php artisan db:seed --class=AdminSeeder
 *
 * Login admin : admin@gmail.com / admin123  (segera ganti lewat menu Akun Admin)
 * Aman dijalankan berulang: kalau emailnya sudah ada, tidak dibuat dobel.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Admin Teman Kerja',
                'password' => Hash::make('admin123'),
                'tanggal_bergabung' => now()->toDateString(),
            ]
        );
    }
}
