<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\Pekerjaan;
use App\Models\Lamaran;
use App\Models\BuktiPenyelesaian;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuktiController extends Controller
{
    /**
     * Menampilkan bukti pengerjaan dari Pencari
     * dan form upload bukti pembayaran.
     */
    public function create(Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        if ($pekerjaan->status_pekerjaan !== 'sedang_dikerjakan') {
            return back()->with(
                'error',
                'Pekerjaan belum sedang dikerjakan.'
            );
        }

        // Ambil semua pencari yang diterima
        $lamaran = Lamaran::where(
                'id_pekerjaan',
                $pekerjaan->id_pekerjaan
            )
            ->where('status_lamaran', 'diterima')
            ->with(['pencariKerja', 'buktiPenyelesaian'])
            ->get();

        if ($lamaran->isEmpty()) {
            return back()->with(
                'error',
                'Tidak ada pencari kerja yang diterima pada pekerjaan ini.'
            );
        }

        return view(
            'pemberi.bukti.create',
            compact('pekerjaan', 'lamaran')
        );
    }


    /**
     * Pemberi mengupload bukti pembayaran.
     *
     * Bukti pengerjaan TIDAK diupload oleh Pemberi.
     * Bukti pengerjaan berasal dari Pencari.
     */
    public function store(Request $request, Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        $request->validate([
            'id_lamaran' => [
                'required',
                'integer',
                'exists:lamaran,id_lamaran',
            ],
            'foto_bukti_bayar' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'catatan_bayar' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'id_lamaran.required' => 'Pencari kerja wajib dipilih.',
            'foto_bukti_bayar.required' => 'Bukti pembayaran wajib diupload.',
            'foto_bukti_bayar.image' => 'Bukti pembayaran harus berupa gambar.',
            'foto_bukti_bayar.mimes' => 'Format bukti pembayaran harus JPG, JPEG, atau PNG.',
            'foto_bukti_bayar.max' => 'Ukuran bukti pembayaran maksimal 2 MB.',
        ]);

        DB::transaction(function () use ($request, $pekerjaan) {

            // Lock pekerjaan agar tidak terjadi proses bersamaan
            $pekerjaan = Pekerjaan::where(
                'id_pekerjaan',
                $pekerjaan->id_pekerjaan
            )
            ->lockForUpdate()
            ->firstOrFail();

            // Pastikan pekerjaan memang milik Pemberi
            if ($pekerjaan->id_pemberi != session('user_id')) {
                abort(403);
            }

            if ($pekerjaan->status_pekerjaan !== 'sedang_dikerjakan') {
                throw new \Exception(
                    'Pekerjaan tidak sedang dikerjakan.'
                );
            }

            // Ambil lamaran yang dipilih
            $lamaran = Lamaran::where(
                'id_lamaran',
                $request->id_lamaran
            )
            ->where(
                'id_pekerjaan',
                $pekerjaan->id_pekerjaan
            )
            ->where('status_lamaran', 'diterima')
            ->lockForUpdate()
            ->firstOrFail();

            // Ambil bukti pengerjaan dari Pencari
            $bukti = BuktiPenyelesaian::where(
                'id_lamaran',
                $lamaran->id_lamaran
            )
            ->lockForUpdate()
            ->first();

            // Pemberi tidak boleh membayar sebelum Pencari
            // mengirim bukti pengerjaan
            if (!$bukti || !$bukti->foto_bukti_kerja) {
                throw new \Exception(
                    'Pencari Kerja belum mengunggah bukti pengerjaan.'
                );
            }

            // Simpan file bukti pembayaran
            $pathBayar = $request->file('foto_bukti_bayar')
                ->store('bukti/pembayaran', 'public');

            // Jika sudah ada bukti pembayaran sebelumnya,
            // hapus file lama
            if ($bukti->foto_bukti_bayar) {
                Storage::disk('public')->delete(
                    $bukti->foto_bukti_bayar
                );
            }

            $bukti->foto_bukti_bayar = $pathBayar;

            // Catatan pembayaran
            if ($request->filled('catatan_bayar')) {
                $bukti->catatan_bayar = $request->catatan_bayar;
            }

            $bukti->save();

            // Setelah bukti pembayaran ada,
            // lamaran tersebut dianggap selesai.
            $lamaran->status_lamaran = 'selesai';
            $lamaran->save();

            // Notifikasi ke Pencari
            Notifikasi::kirim(
                'pencari_kerja',
                $lamaran->id_pencari,
                'Pembayaran telah dikonfirmasi',
                'Pemberi Kerja telah mengunggah bukti pembayaran untuk pekerjaan "' .
                $pekerjaan->deskripsi .
                '". Pekerjaan Anda telah dinyatakan selesai.'
            );

            /*
             * Pekerjaan utama baru dinyatakan selesai
             * apabila seluruh pencari yang diterima
             * sudah menyelesaikan pekerjaan.
             */
            $masihBerjalan = Lamaran::where(
                'id_pekerjaan',
                $pekerjaan->id_pekerjaan
            )
            ->where('status_lamaran', 'diterima')
            ->exists();

            if (!$masihBerjalan) {
                $pekerjaan->status_pekerjaan = 'selesai';
                $pekerjaan->save();
            }
        });

        return redirect()
            ->route('pemberi.pekerjaan.show', $pekerjaan)
            ->with(
                'success',
                'Bukti pembayaran berhasil disimpan dan pekerjaan pencari telah diselesaikan.'
            );
    }


    /**
     * Menampilkan file bukti.
     */
    public function file(BuktiPenyelesaian $bukti, string $jenis)
    {
        $lamaran = Lamaran::with('pekerjaan')
            ->findOrFail($bukti->id_lamaran);

        // Pastikan pekerjaan milik Pemberi yang login
        if ($lamaran->pekerjaan->id_pemberi != session('user_id')) {
            abort(403);
        }

        if (!in_array($jenis, ['kerja', 'bayar'])) {
            abort(404);
        }

        $path = $jenis === 'kerja'
            ? $bukti->foto_bukti_kerja
            : $bukti->foto_bukti_bayar;

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('public')->path($path)
        );
    }


    /**
     * Memastikan pekerjaan milik Pemberi yang sedang login.
     */
    private function milik(Pekerjaan $pekerjaan)
    {
        if ($pekerjaan->id_pemberi != session('user_id')) {
            abort(403);
        }
    }
}