<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\KeahlianPencariKerja;
use App\Models\Lamaran;
use App\Models\Pekerjaan;
use App\Models\PencariKerja;

class DashboardController extends Controller
{
    public function index()
    {
        $idPencari = session('user_id');

        $pencari = PencariKerja::find($idPencari);

        if (!$pencari) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Data Pencari Kerja tidak ditemukan.'
                );
        }

        // Ambil hanya keahlian pencari yang sudah terverifikasi.
        $idKeahlianTerverifikasi = KeahlianPencariKerja::where(
            'id_pencari',
            $idPencari
        )
            ->where(
                'status_verifikasi_keahlian',
                'terverifikasi'
            )
            ->whereNotNull('id_keahlian')
            ->pluck('id_keahlian')
            ->unique();

        // Hitung hanya lowongan tersedia yang sesuai
        // dengan keahlian terverifikasi pencari.
        $jumlahPekerjaanTersedia = Pekerjaan::where(
            'status_pekerjaan',
            'tersedia'
        )
            ->whereIn(
                'id_keahlian',
                $idKeahlianTerverifikasi
            )
            ->count();

        // Total semua lamaran milik pencari.
        $totalLamaranTerkirim = Lamaran::where(
            'id_pencari',
            $idPencari
        )->count();

        /*
         * Ambil maksimal 5 lamaran.
         *
         * Lamaran yang sudah DITERIMA ditempatkan paling atas.
         * Setelah itu, lamaran diurutkan dari yang paling baru.
         */
        $lamaranTerakhir = Lamaran::with([
            'pekerjaan'
        ])
            ->where(
                'id_pencari',
                $idPencari
            )
            ->orderByRaw("
                CASE
                    WHEN status_lamaran = 'diterima' THEN 0
                    ELSE 1
                END
            ")
            ->orderByDesc('tanggal_submit')
            ->orderByDesc('id_lamaran')
            ->limit(5)
            ->get();

        return view(
            'pencari.dashboard',
            compact(
                'pencari',
                'jumlahPekerjaanTersedia',
                'totalLamaranTerkirim',
                'lamaranTerakhir'
            )
        );
    }
}