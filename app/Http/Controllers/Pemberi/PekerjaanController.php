<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\BuktiPenyelesaian;
use App\Models\Keahlian;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\Pekerjaan;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PekerjaanController extends Controller
{
    // ---------------------------------------------------------------
    // LOWONGAN SAYA (item 17)
    // ---------------------------------------------------------------
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Pekerjaan::with('keahlian')
            ->withCount([
                'lamaran',
                'lamaran as menunggu_count' => fn ($q) => $q->where('status_lamaran', 'menunggu'),
                'lamaran as diterima_count' => fn ($q) => $q->whereIn('status_lamaran', ['diterima', 'selesai']),
            ])
            ->where('id_pemberi', session('user_id'))
            ->orderByDesc('tanggal_posting');

        if (is_string($status) && isset(Pekerjaan::LABEL_STATUS[$status])) {
            $query->where('status_pekerjaan', $status);
        } else {
            $status = null;
        }

        return view('pemberi.pekerjaan.index', [
            'pekerjaan' => $query->get(),
            'status'    => $status,
        ]);
    }

    // ---------------------------------------------------------------
    // DETAIL LOWONGAN (item 17): info, pekerja yang diterima, bukti, rating
    // ---------------------------------------------------------------
    public function show(Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        $pekerjaan->load('keahlian');

        $jumlahPelamar = $pekerjaan->lamaran()->count();

        $pekerja = Lamaran::with('pencariKerja')
            ->where('id_pekerjaan', $pekerjaan->id_pekerjaan)
            ->whereIn('status_lamaran', ['diterima', 'selesai'])
            ->get();

        $idLamaran = $pekerja->pluck('id_lamaran');

        $bukti = BuktiPenyelesaian::whereIn('id_lamaran', $idLamaran)->first();

        // rating yang SAYA berikan ke tiap pekerja, di-index per id_lamaran
        $ratingKu = Rating::where('arah_rating', 'pemberi_ke_pekerja')
            ->where('pemberi_rating', session('user_id'))
            ->whereIn('id_lamaran', $idLamaran)
            ->get()
            ->keyBy('id_lamaran');

        return view('pemberi.pekerjaan.show', compact('pekerjaan', 'jumlahPelamar', 'pekerja', 'bukti', 'ratingKu'));
    }

    // ---------------------------------------------------------------
    // BUAT LOWONGAN (item 14)
    // ---------------------------------------------------------------
    public function create()
    {
        $keahlian = Keahlian::orderBy('nama_keahlian')->get();

        return view('pemberi.pekerjaan.create', compact('keahlian'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        // id_pemberi, status awal, dan tanggal_posting TIDAK diambil dari form
        Pekerjaan::create($data + [
            'id_pemberi'       => session('user_id'),
            'status_pekerjaan' => 'tersedia',
        ]);

        return redirect()->route('pemberi.pekerjaan.index')
            ->with('success', 'Lowongan berhasil dipublikasikan.');
    }

    public function edit(Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        if ($pekerjaan->status_pekerjaan !== 'tersedia') {
            return redirect()->route('pemberi.pekerjaan.show', $pekerjaan)
                ->with('error', 'Lowongan hanya bisa diedit saat statusnya Aktif.');
        }

        $keahlian = Keahlian::orderBy('nama_keahlian')->get();

        return view('pemberi.pekerjaan.edit', [
            'pekerjaan'      => $pekerjaan,
            'keahlian'       => $keahlian,
            'sudahDiterima'  => $pekerjaan->jumlahDiterima(),
        ]);
    }

    public function update(Request $request, Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        if ($pekerjaan->status_pekerjaan !== 'tersedia') {
            return redirect()->route('pemberi.pekerjaan.show', $pekerjaan)
                ->with('error', 'Lowongan hanya bisa diedit saat statusnya Aktif.');
        }

        // Jumlah pekerja tidak boleh lebih kecil/sama dengan yang sudah diterima
        // (kalau kuota sudah cukup, pakai tombol "Mulai Pekerjaan")
        $minimal = max(1, $pekerjaan->jumlahDiterima() + 1);

        $rules = $this->rules();
        $rules['jumlah_pekerja'] = 'required|integer|min:' . $minimal . '|max:100';

        $pekerjaan->update($request->validate($rules));

        return redirect()->route('pemberi.pekerjaan.show', $pekerjaan)
            ->with('success', 'Lowongan diperbarui.');
    }

    public function destroy(Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        // Lowongan yang sudah punya pelamar tidak dihapus (menjaga riwayat). Pakai "Tutup" saja.
        if ($pekerjaan->lamaran()->exists()) {
            return back()->with('error', 'Lowongan ini sudah punya pelamar, jadi tidak bisa dihapus.');
        }

        $pekerjaan->delete();

        return redirect()->route('pemberi.pekerjaan.index')
            ->with('success', 'Lowongan dihapus.');
    }

    // ---------------------------------------------------------------
    // PROGRESS PEKERJAAN (item 16 & 20)
    // Aktif -> (Penuh) -> Sedang Dikerjakan -> Selesai   |   Aktif/Penuh -> Ditutup
    // ---------------------------------------------------------------

    // Mulai pekerjaan: butuh minimal 1 pekerja diterima. Pelamar yang masih menunggu ditolak.
    public function mulai(Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        $error = DB::transaction(function () use ($pekerjaan) {
            $p = Pekerjaan::lockForUpdate()->findOrFail($pekerjaan->id_pekerjaan);

            if (!in_array($p->status_pekerjaan, ['tersedia', 'penuh'], true)) {
                return 'Pekerjaan tidak bisa dimulai pada status ini.';
            }

            $pekerja = $p->lamaran()->where('status_lamaran', 'diterima')->get();

            if ($pekerja->isEmpty()) {
                return 'Belum ada pelamar yang diterima. Terima minimal satu pelamar dulu.';
            }

            $p->tolakPelamarMenunggu('pekerjaan sudah dimulai');
            $p->update(['status_pekerjaan' => 'sedang_dikerjakan']);

            foreach ($pekerja as $l) {
                Notifikasi::kirim(
                    $l->id_pencari,
                    'pencari_kerja',
                    'Pekerjaan "' . $p->nama_pekerjaan . '" sudah dimulai. Selamat bekerja!'
                );
            }

            return null;
        });

        return $error
            ? back()->with('error', $error)
            : back()->with('success', 'Pekerjaan dimulai. Status: Sedang dikerjakan.');
    }

    // Tutup lowongan = batalkan rekrutmen. Hanya jika belum ada pekerja diterima.
    public function tutup(Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        $error = DB::transaction(function () use ($pekerjaan) {
            $p = Pekerjaan::lockForUpdate()->findOrFail($pekerjaan->id_pekerjaan);

            if (!in_array($p->status_pekerjaan, ['tersedia', 'penuh'], true)) {
                return 'Lowongan tidak bisa ditutup pada status ini.';
            }

            if ($p->jumlahDiterima() > 0) {
                return 'Sudah ada pekerja yang diterima. Gunakan "Mulai Pekerjaan" untuk melanjutkan.';
            }

            $p->tolakPelamarMenunggu('lowongan ditutup oleh pemberi kerja');
            $p->update(['status_pekerjaan' => 'ditutup']);

            return null;
        });

        return $error
            ? back()->with('error', $error)
            : back()->with('success', 'Lowongan ditutup.');
    }

    // ---------------------------------------------------------------

    private function rules(): array
    {
        return [
            'nama_pekerjaan'     => 'required|string|max:150',
            'id_keahlian'        => 'required|exists:keahlian,id_keahlian',
            'deskripsi'          => 'required|string|max:2000',
            'lokasi'             => 'required|string|max:500',
            'tanggal_pengerjaan' => 'required|date|after_or_equal:today',
            'jumlah_pekerja'     => 'required|integer|min:1|max:100',
            'upah'               => 'required|numeric|min:0|max:9999999999',
            'persyaratan'        => 'nullable|string|max:2000',
            'latitude'           => 'nullable|numeric|between:-90,90',
            'longitude'          => 'nullable|numeric|between:-180,180',
        ];
    }

    // Pastikan lowongan ini benar-benar milik pemberi yang sedang login
    private function milik(Pekerjaan $pekerjaan): void
    {
        abort_unless((int) $pekerjaan->id_pemberi === (int) session('user_id'), 403);
    }
}
