<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keahlian;
use App\Models\KeahlianPencariKerja;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class VerifikasiKeahlianController extends Controller
{
    /**
     * Menampilkan pengajuan keahlian yang menunggu verifikasi.
     */
    public function index()
    {
        $data = KeahlianPencariKerja::with([
            'pencariKerja',
            'keahlian',
        ])
            ->where(
                'status_verifikasi_keahlian',
                'menunggu'
            )
            ->orderByDesc('tanggal_upload')
            ->get();

        $keahlian = Keahlian::orderBy('nama_keahlian')->get();

        return view(
            'admin.verifikasi-keahlian',
            compact('data', 'keahlian')
        );
    }

    /**
     * Menyimpan keputusan Admin terhadap pengajuan keahlian.
     */
    public function keputusan(
        Request $request,
        int $id_keahlian_pencari
    ) {
        $data = $request->validate([
            'status_verifikasi_keahlian' => [
                'required',
                'in:terverifikasi,ditolak',
            ],

            'id_keahlian' => [
                'nullable',
                'exists:keahlian,id_keahlian',
            ],
        ]);

        $pengajuan = KeahlianPencariKerja::find(
            $id_keahlian_pencari
        );

        if (!$pengajuan) {
            return back()->with(
                'error',
                'Data pengajuan keahlian tidak ditemukan.'
            );
        }

        // Pastikan hanya pengajuan yang masih menunggu
        // yang dapat diproses.
        if (
            $pengajuan->status_verifikasi_keahlian
            !== 'menunggu'
        ) {
            return back()->with(
                'error',
                'Pengajuan keahlian ini sudah diproses.'
            );
        }

        // Jika diterima, kategori keahlian wajib dipilih.
        if (
            $data['status_verifikasi_keahlian']
            === 'terverifikasi'
            && empty($data['id_keahlian'])
        ) {
            return back()->with(
                'error',
                'Pilih kategori keahlian terlebih dahulu.'
            );
        }

        // Jika ditolak, kategori master dikosongkan.
        if (
            $data['status_verifikasi_keahlian']
            === 'ditolak'
        ) {
            $pengajuan->id_keahlian = null;
        } else {
            $pengajuan->id_keahlian =
                $data['id_keahlian'];
        }

        $pengajuan->status_verifikasi_keahlian =
            $data['status_verifikasi_keahlian'];

        $pengajuan->save();

        // Kirim notifikasi kepada pencari kerja.
        if (
            $data['status_verifikasi_keahlian']
            === 'terverifikasi'
        ) {
            Notifikasi::kirim(
                $pengajuan->id_pencari,
                'pencari_kerja',
                'Pengajuan keahlian "' .
                $pengajuan->judul_keahlian .
                '" telah diverifikasi oleh admin.'
            );
        } else {
            Notifikasi::kirim(
                $pengajuan->id_pencari,
                'pencari_kerja',
                'Pengajuan keahlian "' .
                $pengajuan->judul_keahlian .
                '" ditolak oleh admin.'
            );
        }

        return back()->with(
            'success',
            'Keputusan verifikasi berhasil disimpan.'
        );
    }
}