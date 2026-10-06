<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\BuktiPenyelesaian;
use App\Models\Notifikasi;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuktiController extends Controller
{
    // Form upload bukti (item 21). Hanya untuk pekerjaan yang sedang dikerjakan.
    public function create(Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        if ($pekerjaan->status_pekerjaan !== 'sedang_dikerjakan') {
            return redirect()->route('pemberi.pekerjaan.show', $pekerjaan)
                ->with('error', 'Bukti hanya bisa dikirim untuk pekerjaan yang sedang dikerjakan.');
        }

        return view('pemberi.bukti.create', compact('pekerjaan'));
    }

    // Simpan bukti (file benar-benar diupload) lalu pekerjaan otomatis SELESAI
    public function store(Request $request, Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        $request->validate([
            'foto_bukti_kerja' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan'          => 'nullable|string|max:500',
        ]);

        $error = DB::transaction(function () use ($request, $pekerjaan) {
            $p = Pekerjaan::lockForUpdate()->findOrFail($pekerjaan->id_pekerjaan);

            if ($p->status_pekerjaan !== 'sedang_dikerjakan') {
                return 'Pekerjaan ini tidak sedang dikerjakan.';
            }

            $pekerja = $p->lamaran()->where('status_lamaran', 'diterima')->get();

            if ($pekerja->isEmpty()) {
                return 'Tidak ada pekerja yang tercatat pada lowongan ini.';
            }

            // Satu kali upload, berlaku untuk semua pekerja di lowongan ini
            $pathKerja = $request->file('foto_bukti_kerja')->store('bukti');
            $pathBayar = $request->file('foto_bukti_bayar')->store('bukti');

            foreach ($pekerja as $l) {
                BuktiPenyelesaian::updateOrCreate(
                    ['id_lamaran' => $l->id_lamaran],
                    [
                        'foto_bukti_kerja' => $pathKerja,
                        'foto_bukti_bayar' => $pathBayar,
                        'catatan'          => $request->catatan,
                        'tanggal_upload'   => now(),
                    ]
                );

                $l->update(['status_lamaran' => 'selesai']);

                Notifikasi::kirim(
                    $l->id_pencari,
                    'pencari_kerja',
                    'Pekerjaan "' . $p->nama_pekerjaan . '" dinyatakan selesai. Kamu sekarang bisa memberi rating.'
                );
            }

            $p->update(['status_pekerjaan' => 'selesai']);

            return null;
        });

        if ($error) {
            return redirect()->route('pemberi.pekerjaan.show', $pekerjaan)->with('error', $error);
        }

        return redirect()->route('pemberi.pekerjaan.show', $pekerjaan)
            ->with('success', 'Bukti tersimpan dan pekerjaan ditandai selesai. Silakan beri rating ke pekerja.');
    }

    // Tampilkan file bukti (disimpan privat, jadi disajikan lewat controller)
    public function file(Pekerjaan $pekerjaan, string $jenis)
    {
        $this->milik($pekerjaan);

        $bukti = BuktiPenyelesaian::whereIn('id_lamaran', $pekerjaan->lamaran()->pluck('id_lamaran'))->first();

        abort_unless($bukti, 404);

        $path = $jenis === 'kerja' ? $bukti->foto_bukti_kerja : $bukti->foto_bukti_bayar;

        abort_unless($path && Storage::exists($path), 404);

        return Storage::response($path);
    }

    private function milik(Pekerjaan $pekerjaan): void
    {
        abort_unless((int) $pekerjaan->id_pemberi === (int) session('user_id'), 403);
    }
}
