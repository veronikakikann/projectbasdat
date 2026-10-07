<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    // Notifikasi milik pemberi yang login. Membuka halaman ini = menandai semua sudah dibaca.
    public function index()
    {
        $query = Notifikasi::where('tipe_user', 'pemberi_kerja')
            ->where('id_user', session('user_id'));

        $notifikasi = (clone $query)->orderByDesc('tanggal')->orderByDesc('id_notifikasi')->get();

        // Simpan dulu mana yang baru supaya masih bisa di-highlight di view
        $baru = $notifikasi->where('status_baca', false)->pluck('id_notifikasi')->all();

        (clone $query)->where('status_baca', false)->update(['status_baca' => true]);

        return view('pemberi.notifikasi.index', compact('notifikasi', 'baru'));
    }
}
