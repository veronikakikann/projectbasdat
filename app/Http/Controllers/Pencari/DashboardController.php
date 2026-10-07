<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
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

        $jumlahPekerjaanTersedia = Pekerjaan::where(
            'status_pekerjaan',
            'tersedia'
        )->count();

        $lamaranTerakhir = Lamaran::with('pekerjaan')
            ->where('id_pencari', $idPencari)
            ->latest('tanggal_submit')
            ->first();

        return view(
            'dashboard.pencari',
            compact(
                'pencari',
                'jumlahPekerjaanTersedia',
                'lamaranTerakhir'
            )
        );
    }
}