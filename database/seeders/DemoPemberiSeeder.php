<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * DATA CONTOH untuk mencoba alur Pemberi Kerja tanpa menunggu fitur Pencari/Admin selesai.
 * Jalankan setelah DatabaseSeeder:   php artisan db:seed --class=DemoPemberiSeeder
 * HAPUS / jangan dijalankan untuk data final.
 *
 * Login pemberi : pemberi@gmail.com / pemberi123
 */
class DemoPemberiSeeder extends Seeder
{
    public function run(): void
    {
        $idKeahlian = DB::table('keahlian')->where('nama_keahlian', 'Tukang AC')->value('id_keahlian');

        // Pemberi kerja (sudah terverifikasi & aktif)
        $idPemberi = DB::table('pemberi_kerja')->where('email', 'pemberi@gmail.com')->value('id_pemberi')
            ?? DB::table('pemberi_kerja')->insertGetId([
                'nik'               => '3578000000000100',
                'nama'              => 'Pak Contoh Pemberi',
                'alamat'            => 'Jl. Contoh No. 10, Surabaya',
                'no_telpon'         => '081200000100',
                'email'             => 'pemberi@gmail.com',
                'password'          => Hash::make('pemberi123'),
                'status_verifikasi' => 'terverifikasi',
                'tanggal_daftar'    => now()->toDateString(),
            ]);

        // 3 pencari contoh, masing-masing punya keahlian "Tukang AC" yang sudah terverifikasi
        $idPencari = [];
        foreach ([1, 2, 3] as $i) {
            $email = "pencari{$i}@gmail.com";

            $id = DB::table('pencari_kerja')->where('email', $email)->value('id_pencari')
                ?? DB::table('pencari_kerja')->insertGetId([
                    'nik'               => '357800000000020' . $i,
                    'nama'              => "Pencari Contoh {$i}",
                    'alamat'            => "Jl. Pencari No. {$i}, Surabaya",
                    'no_telpon'         => '08120000020' . $i,
                    'email'             => $email,
                    'password'          => Hash::make('pencari123'),
                    'status_verifikasi' => 'terverifikasi',
                    'tanggal_daftar'    => now()->toDateString(),
                ]);

            DB::table('keahlian_pencari_kerja')->updateOrInsert(
                ['id_pencari' => $id, 'id_keahlian' => $idKeahlian],
                [
                    'file_surat_rekomendasi'      => 'contoh/surat.pdf',
                    'status_verifikasi_keahlian'  => 'terverifikasi',
                    'tanggal_upload'              => now()->toDateString(),
                ]
            );

            $idPencari[] = $id;
        }

        // 1 lowongan butuh 2 pekerja, dengan 3 pelamar menunggu
        $idPekerjaan = DB::table('pekerjaan')
            ->where('id_pemberi', $idPemberi)
            ->where('nama_pekerjaan', 'Servis AC Rumah (contoh)')
            ->value('id_pekerjaan');

        if (!$idPekerjaan) {
            $idPekerjaan = DB::table('pekerjaan')->insertGetId([
                'id_pemberi'         => $idPemberi,
                'id_keahlian'        => $idKeahlian,
                'nama_pekerjaan'     => 'Servis AC Rumah (contoh)',
                'deskripsi'          => 'Cuci dan cek 4 unit AC split di rumah.',
                'persyaratan'        => 'Membawa peralatan sendiri.',
                'upah'               => 150000,
                'lokasi'             => 'Jl. Contoh No. 10, Surabaya',
                'tanggal_pengerjaan' => now()->addDays(3)->toDateString(),
                'jumlah_pekerja'     => 2,
                'status_pekerjaan'   => 'tersedia',
            ]);
        }

        foreach ($idPencari as $id) {
            DB::table('lamaran')->updateOrInsert(
                ['id_pekerjaan' => $idPekerjaan, 'id_pencari' => $id],
                ['status_lamaran' => 'menunggu']
            );
        }

        DB::table('notifikasi')->insert([
            'id_user'   => $idPemberi,
            'tipe_user' => 'pemberi_kerja',
            'isi_pesan' => 'Ada 3 pelamar baru untuk "Servis AC Rumah (contoh)".',
        ]);
    }
}
