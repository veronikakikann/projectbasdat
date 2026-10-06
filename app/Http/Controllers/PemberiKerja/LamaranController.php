<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\Pekerjaan;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LamaranController extends Controller
{
    // Semua pelamar di semua lowongan milik pemberi yang login (filter status opsional)
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Lamaran::with(['pekerjaan.keahlian', 'pencariKerja'])
            ->whereHas('pekerjaan', fn ($q) => $q->where('id_pemberi', session('user_id')))
            ->orderByDesc('tanggal_submit');

        if (in_array($status, ['menunggu', 'diterima', 'ditolak', 'selesai'], true)) {
            $query->where('status_lamaran', $status);
        } else {
            $status = null;
        }

        $lamaran = $query->get();

        return view('pemberi.lamaran.index', [
            'lamaran'   => $lamaran,
            'pekerjaan' => null,
            'diterima'  => null,
            'status'    => $status,
        ] + $this->infoPelamar($lamaran));
    }

    // Pelamar untuk satu lowongan (item 15)
    public function perPekerjaan(Pekerjaan $pekerjaan)
    {
        abort_unless((int) $pekerjaan->id_pemberi === (int) session('user_id'), 403);

        $pekerjaan->load('keahlian');

        $lamaran = Lamaran::with(['pekerjaan.keahlian', 'pencariKerja'])
            ->where('id_pekerjaan', $pekerjaan->id_pekerjaan)
            ->orderByDesc('tanggal_submit')
            ->get();

        return view('pemberi.lamaran.index', [
            'lamaran'   => $lamaran,
            'pekerjaan' => $pekerjaan,
            'diterima'  => $pekerjaan->jumlahDiterima(),
            'status'    => null,
        ] + $this->infoPelamar($lamaran));
    }

    // Terima / tolak lamaran, dengan memperhatikan kapasitas (item 15 & 16)
    public function update(Request $request, Lamaran $lamaran)
    {
        $request->validate(['status_lamaran' => 'required|in:diterima,ditolak']);

        $error = DB::transaction(function () use ($request, $lamaran) {
            // Kunci baris supaya dua klik "Terima" bersamaan tidak melewati kuota
            $p = Pekerjaan::lockForUpdate()->findOrFail($lamaran->id_pekerjaan);

            abort_unless((int) $p->id_pemberi === (int) session('user_id'), 403);

            $l = Lamaran::lockForUpdate()->findOrFail($lamaran->id_lamaran);

            if ($l->status_lamaran !== 'menunggu') {
                return 'Lamaran ini sudah diproses.';
            }

            if ($p->status_pekerjaan !== 'tersedia') {
                return 'Lowongan sudah tidak menerima pekerja (penuh/ditutup/sedang berjalan).';
            }

            $judul = '"' . $p->nama_pekerjaan . '"';

            if ($request->status_lamaran === 'ditolak') {
                $l->update(['status_lamaran' => 'ditolak']);

                Notifikasi::kirim($l->id_pencari, 'pencari_kerja', 'Lamaranmu untuk ' . $judul . ' ditolak.');

                return null;
            }

            // --- diterima ---
            if ($p->jumlahDiterima() >= $p->jumlah_pekerja) {
                return 'Kuota pekerja sudah terpenuhi.';
            }

            $l->update(['status_lamaran' => 'diterima']);

            Notifikasi::kirim($l->id_pencari, 'pencari_kerja', 'Lamaranmu untuk ' . $judul . ' diterima!');

            // Kuota terpenuhi -> lowongan PENUH, sisa pelamar otomatis ditolak
            if ($p->jumlahDiterima() >= $p->jumlah_pekerja) {
                $p->update(['status_pekerjaan' => 'penuh']);
                $p->tolakPelamarMenunggu('kuota pekerja sudah terpenuhi');

                Notifikasi::kirim(
                    $p->id_pemberi,
                    'pemberi_kerja',
                    'Lowongan ' . $judul . ' sudah penuh (' . $p->jumlah_pekerja . ' pekerja). Kamu bisa memulai pekerjaan.'
                );
            }

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Lamaran ' . $request->status_lamaran . '.');
    }

    // Keahlian terverifikasi + rata-rata rating tiap pelamar (2 query saja, tanpa N+1)
    private function infoPelamar($lamaran): array
    {
        $ids = $lamaran->pluck('id_pencari')->unique()->values();

        $rating = Rating::selectRaw('penerima_rating, AVG(skor) as rata, COUNT(*) as jumlah')
            ->where('arah_rating', 'pemberi_ke_pekerja')
            ->whereIn('penerima_rating', $ids)
            ->groupBy('penerima_rating')
            ->get()
            ->keyBy('penerima_rating');

        $keahlian = DB::table('keahlian_pencari_kerja as kp')
            ->join('keahlian as k', 'k.id_keahlian', '=', 'kp.id_keahlian')
            ->whereIn('kp.id_pencari', $ids)
            ->where('kp.status_verifikasi_keahlian', 'terverifikasi')
            ->select('kp.id_pencari', 'k.nama_keahlian')
            ->get()
            ->groupBy('id_pencari');

        return compact('rating', 'keahlian');
    }
}
