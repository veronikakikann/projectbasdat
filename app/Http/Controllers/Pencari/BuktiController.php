<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\BuktiPenyelesaian;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuktiController extends Controller
{
    public function create(Lamaran $lamaran)
    {
        $this->milik($lamaran);

        $lamaran->load('pekerjaan');

        abort_unless(
            $lamaran->status_lamaran === 'diterima',
            403
        );

        abort_unless(
            $lamaran->pekerjaan->status_pekerjaan
                === 'sedang_dikerjakan',
            403
        );

        return view(
            'pencari.bukti.create',
            compact('lamaran')
        );
    }

    public function store(
        Request $request,
        Lamaran $lamaran
    ) {
        $this->milik($lamaran);

        $lamaran->load('pekerjaan');

        abort_unless(
            $lamaran->status_lamaran === 'diterima',
            403
        );

        abort_unless(
            $lamaran->pekerjaan->status_pekerjaan
                === 'sedang_dikerjakan',
            403
        );

        $data = $request->validate([
            'foto_bukti_kerja' =>
                'required|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'catatan' =>
                'nullable|string|max:500',
        ]);

        $path = $request
            ->file('foto_bukti_kerja')
            ->store(
                'bukti',
                'public'
            );

        $bukti = BuktiPenyelesaian::updateOrCreate(
            [
                'id_lamaran' =>
                    $lamaran->id_lamaran
            ],
            [
                'foto_bukti_kerja' =>
                    $path,

                'catatan' =>
                    $data['catatan'] ?? null,

                'tanggal_upload' =>
                    now(),
            ]
        );

        Notifikasi::kirim(
            $lamaran->pekerjaan->id_pemberi,
            'pemberi_kerja',
            'Pekerja telah mengunggah bukti penyelesaian untuk pekerjaan "' .
            $lamaran->pekerjaan->nama_pekerjaan .
            '". Silakan diperiksa.'
        );

        return redirect()
            ->route(
                'pencari.lamaran-saya'
            )
            ->with(
                'success',
                'Bukti penyelesaian berhasil diunggah.'
            );
    }

    private function milik(Lamaran $lamaran): void
    {
        abort_unless(
            (int) $lamaran->id_pencari
                === (int) session('user_id'),
            403
        );
    }
}