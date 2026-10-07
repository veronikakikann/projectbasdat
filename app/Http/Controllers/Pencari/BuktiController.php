<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\BuktiPenyelesaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuktiController extends Controller
{
    /**
     * Form upload bukti pengerjaan.
     */
    public function create(Lamaran $lamaran)
    {
        $idPencari = session('user_id');

        // Pastikan lamaran milik pencari yang sedang login
        if ($lamaran->id_pencari != $idPencari) {
            abort(403);
        }

        // Bukti pengerjaan hanya boleh dikirim
        // jika lamaran sudah diterima
        if ($lamaran->status_lamaran !== 'diterima') {
            return back()->with(
                'error',
                'Bukti pengerjaan hanya dapat dikirim untuk lamaran yang diterima.'
            );
        }

        $pekerjaan = $lamaran->pekerjaan;

        // Pekerjaan harus sedang dikerjakan
        if (!$pekerjaan || $pekerjaan->status_pekerjaan !== 'sedang_dikerjakan') {
            return back()->with(
                'error',
                'Pekerjaan belum berada dalam status sedang dikerjakan.'
            );
        }

        // Ambil bukti yang sudah ada
        $bukti = BuktiPenyelesaian::where(
            'id_lamaran',
            $lamaran->id_lamaran
        )->first();

        return view(
            'pencari.bukti.create',
            compact('lamaran', 'pekerjaan', 'bukti')
        );
    }


    /**
     * Simpan bukti pengerjaan dari Pencari Kerja.
     */
    public function store(Request $request, Lamaran $lamaran)
    {
        $idPencari = session('user_id');

        // Pastikan lamaran milik pencari yang login
        if ($lamaran->id_pencari != $idPencari) {
            abort(403);
        }

        if ($lamaran->status_lamaran !== 'diterima') {
            return back()->with(
                'error',
                'Lamaran belum diterima.'
            );
        }

        $pekerjaan = $lamaran->pekerjaan;

        if (!$pekerjaan || $pekerjaan->status_pekerjaan !== 'sedang_dikerjakan') {
            return back()->with(
                'error',
                'Pekerjaan belum sedang dikerjakan.'
            );
        }

        $request->validate([
            'foto_bukti_kerja' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'foto_bukti_kerja.required' => 'Bukti pengerjaan wajib diupload.',
            'foto_bukti_kerja.image' => 'File bukti pengerjaan harus berupa gambar.',
            'foto_bukti_kerja.mimes' => 'Format bukti harus JPG, JPEG, atau PNG.',
            'foto_bukti_kerja.max' => 'Ukuran bukti maksimal 2 MB.',
        ]);

        $bukti = BuktiPenyelesaian::where(
            'id_lamaran',
            $lamaran->id_lamaran
        )->first();

        // Jika sebelumnya sudah ada bukti kerja,
        // hapus file lama sebelum diganti
        if ($bukti && $bukti->foto_bukti_kerja) {
            Storage::disk('public')->delete($bukti->foto_bukti_kerja);
        }

        $path = $request->file('foto_bukti_kerja')
            ->store('bukti/pengerjaan', 'public');

        if (!$bukti) {
            $bukti = new BuktiPenyelesaian();
            $bukti->id_lamaran = $lamaran->id_lamaran;
        }

        $bukti->foto_bukti_kerja = $path;
        $bukti->catatan = $request->catatan;
        $bukti->tanggal_upload = now();

        // Bukti bayar tetap NULL sampai Pemberi melakukan pembayaran
        $bukti->save();

        // Jangan ubah lamaran menjadi selesai di sini.
        // Karena Pemberi masih harus melakukan pembayaran.

        // Beri tahu Pemberi Kerja
        Notifikasi::kirim(
            'pemberi_kerja',
            $pekerjaan->id_pemberi,
            'Bukti pengerjaan telah dikirim',
            'Pencari Kerja telah mengunggah bukti pengerjaan untuk pekerjaan "' .
            $pekerjaan->deskripsi .
            '". Silakan periksa bukti dan lakukan pembayaran.'
        );

        return redirect()
            ->route('pencari.lamaran-saya')
            ->with(
                'success',
                'Bukti pengerjaan berhasil dikirim. Silakan menunggu konfirmasi dan pembayaran dari Pemberi Kerja.'
            );
    }
}