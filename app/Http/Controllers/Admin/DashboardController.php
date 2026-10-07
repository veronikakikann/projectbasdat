<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemberiKerja;
use App\Models\PencariKerja;
use App\Models\Keahlian;
use App\Models\Lamaran;
use App\Models\Pekerjaan;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahPemberi = PemberiKerja::count();
        $jumlahPencari = PencariKerja::count();
        $jumlahKeahlian = Keahlian::count();
        $jumlahPekerjaan = Pekerjaan::count();
        $jumlahLamaran = Lamaran::count();

        $menungguPemberi = PemberiKerja::where(
            'status_verifikasi',
            'menunggu'
        )->count();

        $menungguPencari = PencariKerja::where(
            'status_verifikasi',
            'menunggu'
        )->count();

        return view('dashboard.admin', compact(
            'jumlahPemberi',
            'jumlahPencari',
            'jumlahKeahlian',
            'jumlahPekerjaan',
            'jumlahLamaran',
            'menungguPemberi',
            'menungguPencari'
        ));
    }
}