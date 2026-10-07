<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Pekerjaan;

class DashboardController extends Controller
{
    // Dashboard Pemberi Kerja
    public function index()
    {
        $id = session('user_id');

        // Jumlah lowongan berdasarkan status
        // Contoh:
        // tersedia => 2
        // penuh => 1
        // sedang_dikerjakan => 1
        // selesai => 2
        $perStatus = Pekerjaan::where('id_pemberi', $id)
            ->selectRaw('status_pekerjaan, COUNT(*) as total')
            ->groupBy('status_pekerjaan')
            ->pluck('total', 'status_pekerjaan');

        // Semua lamaran yang masuk ke lowongan milik pemberi yang sedang login
        $pelamar = fn () => Lamaran::whereHas(
            'pekerjaan',
            fn ($q) => $q->where('id_pemberi', $id)
        );

        // Total seluruh pelamar
        $totalPelamar = $pelamar()->count();

        // Total lamaran yang masih menunggu
        $pelamarMenunggu = $pelamar()
            ->where('status_lamaran', 'menunggu')
            ->count();

        // Lima lowongan terbaru milik pemberi
        // sekaligus menghitung jumlah pelamar dan pelamar yang masih menunggu
        $terbaru = Pekerjaan::withCount([
            'lamaran',
            'lamaran as menunggu_count' => fn ($q) =>
                $q->where('status_lamaran', 'menunggu'),
        ])
            ->where('id_pemberi', $id)
            ->orderByDesc('tanggal_posting')
            ->limit(5)
            ->get();

        // View Pemberi
        return view('pemberi.dashboard', compact(
            'perStatus',
            'totalPelamar',
            'pelamarMenunggu',
            'terbaru'
        ));
    }
}