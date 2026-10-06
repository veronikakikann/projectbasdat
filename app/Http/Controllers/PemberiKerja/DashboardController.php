<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Pekerjaan;

class DashboardController extends Controller
{
    // Dashboard sederhana: semua angka dihitung dari data pemberi yang sedang login
    public function index()
    {
        $id = session('user_id');

        // jumlah lowongan per status: ['tersedia' => 2, 'selesai' => 1, ...]
        $perStatus = Pekerjaan::where('id_pemberi', $id)
            ->selectRaw('status_pekerjaan, COUNT(*) as total')
            ->groupBy('status_pekerjaan')
            ->pluck('total', 'status_pekerjaan');

        $pelamar = fn () => Lamaran::whereHas('pekerjaan', fn ($q) => $q->where('id_pemberi', $id));

        $totalPelamar    = $pelamar()->count();
        $pelamarMenunggu = $pelamar()->where('status_lamaran', 'menunggu')->count();

        $terbaru = Pekerjaan::withCount([
                'lamaran',
                'lamaran as menunggu_count' => fn ($q) => $q->where('status_lamaran', 'menunggu'),
            ])
            ->where('id_pemberi', $id)
            ->orderByDesc('tanggal_posting')
            ->limit(5)
            ->get();

        return view('dashboard.pemberi', compact('perStatus', 'totalPelamar', 'pelamarMenunggu', 'terbaru'));
    }
}
