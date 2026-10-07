<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\Pekerjaan;
use App\Models\PencariKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LamaranController extends Controller
{
    public function index()
    {
        $idPencari = session('user_id');

        $lamaran = Lamaran::with([
            'pekerjaan.keahlian',
            'pekerjaan.pemberiKerja',
            'buktiPenyelesaian',
            'rating',
        ])
            ->where(
                'id_pencari',
                $idPencari
            )
            ->orderByDesc(
                'tanggal_submit'
            )
            ->get();

        return view(
            'pencari_kerja.lamaran-saya',
            compact('lamaran')
        );
    }

    public function store(
        Request $request,
        Pekerjaan $pekerjaan
    ) {
        $idPencari = session('user_id');

        if (
            ! PencariKerja::where(
                'id_pencari',
                $idPencari
            )->exists()
        ) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login sebagai Pencari Kerja.'
                );
        }

        $error = DB::transaction(
            function () use (
                $pekerjaan,
                $idPencari
            ) {
                $pekerjaan = Pekerjaan::lockForUpdate()
                    ->findOrFail(
                        $pekerjaan->id_pekerjaan
                    );

                if (
                    $pekerjaan->status_pekerjaan
                    !== 'tersedia'
                ) {
                    return 'Pekerjaan sudah tidak tersedia.';
                }

                $sudahMelamar = Lamaran::where(
                    'id_pekerjaan',
                    $pekerjaan->id_pekerjaan
                )
                    ->where(
                        'id_pencari',
                        $idPencari
                    )
                    ->exists();

                if ($sudahMelamar) {
                    return 'Kamu sudah melamar pekerjaan ini.';
                }

                $jumlahDiterima = Lamaran::where(
                    'id_pekerjaan',
                    $pekerjaan->id_pekerjaan
                )
                    ->whereIn(
                        'status_lamaran',
                        ['diterima', 'selesai']
                    )
                    ->count();

                if (
                    $jumlahDiterima
                    >= $pekerjaan->jumlah_pekerja
                ) {
                    return 'Kuota pekerja sudah penuh.';
                }

                Lamaran::create([
                    'id_pekerjaan' => $pekerjaan->id_pekerjaan,

                    'id_pencari' => $idPencari,

                    'status_lamaran' => 'menunggu',

                    'tanggal_submit' => now(),
                ]);

                Notifikasi::kirim(
                    $pekerjaan->id_pemberi,
                    'pemberi_kerja',
                    'Ada pelamar baru untuk pekerjaan "'.
                    $pekerjaan->nama_pekerjaan.
                    '".'
                );

                return null;
            }
        );

        if ($error) {
            return back()
                ->with('error', $error);
        }

        return redirect()
            ->route('pencari.lamaran-saya')
            ->with(
                'success',
                'Lamaran berhasil dikirim.'
            );
    }

    public function batalkan(Lamaran $lamaran): RedirectResponse
    {
        abort_unless((int) $lamaran->id_pencari === (int) session('user_id'), 403);
        $error = DB::transaction(function () use ($lamaran): ?string {
            Pekerjaan::lockForUpdate()->findOrFail($lamaran->id_pekerjaan);
            $current = Lamaran::lockForUpdate()->findOrFail($lamaran->id_lamaran);
            abort_unless((int) $current->id_pencari === (int) session('user_id'), 403);
            if ($current->status_lamaran !== 'menunggu') {
                return 'Lamaran yang sudah diproses tidak dapat dibatalkan.';
            }
            $current->delete();

            return null;
        });

        return $error ? back()->with('error', $error) : back()->with('success', 'Lamaran berhasil dibatalkan.');
    }
}
