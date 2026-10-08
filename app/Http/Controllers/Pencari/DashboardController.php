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

        // Ambil hanya keahlian milik pencari yang sudah terverifikasi.
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

        $totalLamaranTerkirim = Lamaran::where(
            'id_pencari',
            $idPencari
        )->count();

        $lamaranTerakhir = Lamaran::with('pekerjaan')
            ->where('id_pencari', $idPencari)
            ->latest('tanggal_submit')
            ->first();

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