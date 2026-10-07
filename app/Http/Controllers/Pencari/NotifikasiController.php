<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        $query = Notifikasi::where(
            'tipe_user',
            'pencari_kerja'
        )
            ->where(
                'id_user',
                session('user_id')
            );

        $notifikasi = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('id_notifikasi')
            ->get();

        $baru = $notifikasi
            ->where('status_baca', false)
            ->pluck('id_notifikasi')
            ->all();

        $query
            ->where('status_baca', false)
            ->update([
                'status_baca' => true
            ]);

        return view(
            'pencari.notifikasi.index',
            compact(
                'notifikasi',
                'baru'
            )
        );
    }
}