<?php

namespace App\Http\Controllers\Pemberi;

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
    public function create(Pekerjaan $pekerjaan): View|RedirectResponse
    {
        $this->milik($pekerjaan);
        if (! in_array($pekerjaan->status_pekerjaan, ['sedang_dikerjakan', 'selesai'], true)) {
            return back()->with('error', 'Pekerjaan belum sedang dikerjakan.');
        }

        $lamarans = $pekerjaan->lamaran()->whereIn('status_lamaran', ['diterima', 'selesai'])
            ->with(['pencariKerja', 'buktiPenyelesaian'])->get();

        return view('pemberi.bukti.create', compact('pekerjaan', 'lamarans'));
    }

    public function store(Request $request, Pekerjaan $pekerjaan): RedirectResponse
    {
        $this->milik($pekerjaan);
        $data = $request->validate([
            'id_lamaran' => 'required|integer|exists:lamaran,id_lamaran',
            'foto_bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan_bayar' => 'nullable|string|max:1000',
        ]);
        $newPath = null;
        $oldPath = null;

        try {
            DB::transaction(function () use ($pekerjaan, $request, $data, &$newPath, &$oldPath): void {
                $current = Pekerjaan::lockForUpdate()->findOrFail($pekerjaan->id_pekerjaan);
                $this->milik($current);
                if ($current->status_pekerjaan !== 'sedang_dikerjakan') {
                    throw ValidationException::withMessages(['foto_bukti_bayar' => 'Pekerjaan tidak sedang dikerjakan.']);
                }
                $lamaran = Lamaran::where('id_pekerjaan', $current->id_pekerjaan)
                    ->lockForUpdate()->findOrFail($data['id_lamaran']);
                if ($lamaran->status_lamaran !== 'diterima') {
                    throw ValidationException::withMessages(['id_lamaran' => 'Lamaran sudah diproses atau belum diterima.']);
                }
                $bukti = BuktiPenyelesaian::where('id_lamaran', $lamaran->id_lamaran)
                    ->lockForUpdate()->first();
                if (! $bukti?->foto_bukti_kerja) {
                    throw ValidationException::withMessages(['foto_bukti_bayar' => 'Pekerja belum mengunggah bukti pengerjaan.']);
                }
                $oldPath = $bukti->foto_bukti_bayar;
                $newPath = PrivateDocuments::store($request->file('foto_bukti_bayar'), 'bukti/pembayaran');
                $bukti->update([
                    'foto_bukti_bayar' => $newPath,
                    'catatan_bayar' => $data['catatan_bayar'] ?? null,
                ]);
                $lamaran->update(['status_lamaran' => 'selesai']);
                Notifikasi::kirim($lamaran->id_pencari, 'pencari_kerja',
                    'Pembayaran untuk "'.$current->nama_pekerjaan.'" telah dikonfirmasi. Kamu sekarang bisa memberi rating.');

                if (! $current->lamaran()->where('status_lamaran', 'diterima')->exists()) {
                    $current->update(['status_pekerjaan' => 'selesai']);
                }
            });
        } catch (Throwable $exception) {
            PrivateDocuments::deleteUnused($newPath);
            throw $exception;
        }

        PrivateDocuments::deleteUnused($oldPath);

        return redirect()->route('pemberi.pekerjaan.show', $pekerjaan)
            ->with('success', 'Bukti pembayaran berhasil disimpan.');
    }

    private function milik(Pekerjaan $pekerjaan): void
    {
        abort_unless((int) $pekerjaan->id_pemberi === (int) session('user_id'), 403);
    }
}
