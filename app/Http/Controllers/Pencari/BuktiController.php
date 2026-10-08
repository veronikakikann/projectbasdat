<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\BuktiPenyelesaian;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\Pekerjaan;
use App\Support\PrivateDocuments;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class BuktiController extends Controller
{
    /**
     * Halaman Pekerjaan Saya.
     * Hanya menampilkan lamaran yang sudah diterima.
     */
    public function index(): View
    {
        $lamarans = Lamaran::with([
            'pekerjaan.pemberiKerja',
            'buktiPenyelesaian',
        ])
            ->where('id_pencari', session('user_id'))
            ->where('status_lamaran', 'diterima')
            ->orderByDesc('tanggal_submit')
            ->orderByDesc('id_lamaran')
            ->get();

        return view('pencari.pekerjaan-saya.index', compact('lamarans'));
    }

    /**
     * Halaman upload bukti kerja.
     */
    public function create(Lamaran $lamaran): View|RedirectResponse
    {
        $this->milik($lamaran);

        if (
            $lamaran->status_lamaran !== 'diterima'
            || $lamaran->pekerjaan->status_pekerjaan !== 'sedang_dikerjakan'
        ) {
            return back()->with(
                'error',
                'Bukti hanya dapat dikirim saat pekerjaan sedang dikerjakan.'
            );
        }

        return view('pencari.bukti.create', [
            'lamaran' => $lamaran,
            'pekerjaan' => $lamaran->pekerjaan,
            'bukti' => $lamaran->buktiPenyelesaian,
        ]);
    }

    /**
     * Menyimpan bukti kerja.
     */
    public function store(Request $request, Lamaran $lamaran): RedirectResponse
    {
        $this->milik($lamaran);

        $data = $request->validate([
            'foto_bukti_kerja' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $newPath = null;
        $oldPath = null;

        try {
            DB::transaction(function () use (
                $lamaran,
                $request,
                $data,
                &$newPath,
                &$oldPath
            ): void {
                $pekerjaan = Pekerjaan::lockForUpdate()
                    ->findOrFail($lamaran->id_pekerjaan);

                $current = Lamaran::lockForUpdate()
                    ->findOrFail($lamaran->id_lamaran);

                $this->milik($current);

                if (
                    $current->status_lamaran !== 'diterima'
                    || $pekerjaan->status_pekerjaan !== 'sedang_dikerjakan'
                ) {
                    throw ValidationException::withMessages([
                        'foto_bukti_kerja' =>
                            'Bukti hanya dapat dikirim saat pekerjaan sedang dikerjakan.',
                    ]);
                }

                $bukti = BuktiPenyelesaian::where(
                    'id_lamaran',
                    $current->id_lamaran
                )
                    ->lockForUpdate()
                    ->first();

                if ($bukti?->foto_bukti_bayar) {
                    throw ValidationException::withMessages([
                        'foto_bukti_kerja' =>
                            'Bukti yang sudah dibayar tidak dapat diubah.',
                    ]);
                }

                $oldPath = $bukti?->foto_bukti_kerja;

                $newPath = PrivateDocuments::store(
                    $request->file('foto_bukti_kerja'),
                    'bukti/pengerjaan'
                );

                BuktiPenyelesaian::updateOrCreate(
                    [
                        'id_lamaran' => $current->id_lamaran,
                    ],
                    [
                        'foto_bukti_kerja' => $newPath,
                        'catatan' => $data['catatan'] ?? null,
                        'tanggal_upload' => now(),
                    ]
                );

                Notifikasi::kirim(
                    $pekerjaan->id_pemberi,
                    'pemberi_kerja',
                    'Bukti pengerjaan untuk "' .
                    $pekerjaan->nama_pekerjaan .
                    '" telah dikirim. Silakan periksa dan lakukan pembayaran.'
                );
            });
        } catch (Throwable $exception) {
            PrivateDocuments::deleteUnused($newPath);

            throw $exception;
        }

        PrivateDocuments::deleteUnused($oldPath);

        return redirect()
            ->route('pencari.lamaran-saya')
            ->with(
                'success',
                'Bukti pengerjaan berhasil dikirim. Silakan menunggu pembayaran.'
            );
    }

    /**
     * Memastikan lamaran milik pencari yang sedang login.
     */
    private function milik(Lamaran $lamaran): void
    {
        abort_unless(
            (int) $lamaran->id_pencari === (int) session('user_id'),
            403
        );
    }
}