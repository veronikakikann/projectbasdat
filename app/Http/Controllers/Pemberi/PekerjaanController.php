<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\Keahlian;
use App\Models\KeahlianPencariKerja;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\Pekerjaan;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        if (
            is_string($status)
            && isset(Pekerjaan::LABEL_STATUS[$status])
        ) {
            $query->where(
                'status_pekerjaan',
                $status
            );
        } else {
            $status = null;
        }

        return view('pemberi.pekerjaan.index', [
            'pekerjaan' => $query->get(),
            'status' => $status,
        ]);
    }

    // ---------------------------------------------------------------
    // DETAIL LOWONGAN (item 17)
    // ---------------------------------------------------------------
    public function show(Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        $pekerjaan->load('keahlian');

        $jumlahPelamar = $pekerjaan
            ->lamaran()
            ->count();

        $pekerja = Lamaran::with([
            'pencariKerja',
            'buktiPenyelesaian',
        ])
            ->where(
                'id_pekerjaan',
                $pekerjaan->id_pekerjaan
            )
            ->whereIn(
                'status_lamaran',
                ['diterima', 'selesai']
            )
            ->get();

        $idLamaran = $pekerja->pluck(
            'id_lamaran'
        );

        // Rating yang saya berikan ke tiap pekerja,
        // di-index berdasarkan id_lamaran.
        $ratingKu = Rating::where(
            'arah_rating',
            'pemberi_ke_pekerja'
        )
            ->where(
                'pemberi_rating',
                session('user_id')
            )
            ->whereIn(
                'id_lamaran',
                $idLamaran
            )
            ->get()
            ->keyBy('id_lamaran');

        return view(
            'pemberi.pekerjaan.show',
            compact(
                'pekerjaan',
                'jumlahPelamar',
                'pekerja',
                'ratingKu'
            )
        );
    }

    // ---------------------------------------------------------------
    // BUAT LOWONGAN (item 14)
    // ---------------------------------------------------------------
    public function create()
    {
        $keahlian = Keahlian::orderBy(
            'nama_keahlian'
        )->get();

        return view(
            'pemberi.pekerjaan.create',
            compact('keahlian')
        );
    }

    public function store(
        Request $request
    ) {
        $data = $request->validate(
            $this->rules()
        );

        $pekerjaan = DB::transaction(
            function () use ($data) {
                $pekerjaan = Pekerjaan::create([
                    ...$data,

                    'id_pemberi' =>
                        session('user_id'),

                    'status_pekerjaan' =>
                        'tersedia',

                    'tanggal_posting' =>
                        now(),
                ]);

                /*
                 * Kirim notifikasi ke Pencari Kerja
                 * yang memiliki keahlian terverifikasi
                 * sesuai dengan lowongan.
                 */
                $pencariCocok =
                    KeahlianPencariKerja::where(
                        'id_keahlian',
                        $pekerjaan->id_keahlian
                    )
                        ->where(
                            'status_verifikasi_keahlian',
                            'terverifikasi'
                        )
                        ->pluck('id_pencari')
                        ->unique();

                foreach ($pencariCocok as $idPencari) {
                    Notifikasi::kirim(
                        $idPencari,
                        'pencari_kerja',
                        'Ada lowongan baru yang sesuai dengan keahlian kamu: "'
                        . $pekerjaan->nama_pekerjaan
                        . '". Yuk cek lowongan tersebut di Cari Lowongan.'
                    );
                }

                return $pekerjaan;
            }
        );

        return redirect()
            ->route(
                'pemberi.pekerjaan.index'
            )
            ->with(
                'success',
                'Lowongan berhasil dipublikasikan.'
            );
    }

    public function edit(Pekerjaan $pekerjaan)
    {
        $this->milik($pekerjaan);

        if (
            $pekerjaan->status_pekerjaan !==
            'tersedia'
        ) {
            return redirect()
                ->route(
                    'pemberi.pekerjaan.show',
                    $pekerjaan
                )
                ->with(
                    'error',
                    'Lowongan hanya bisa diedit saat statusnya Aktif.'
                );
        }

        $keahlian = Keahlian::orderBy(
            'nama_keahlian'
        )->get();

        return view(
            'pemberi.pekerjaan.edit',
            [
                'pekerjaan' => $pekerjaan,
                'keahlian' => $keahlian,
                'sudahDiterima' =>
                    $pekerjaan->jumlahDiterima(),
            ]
        );
    }

    public function update(
        Request $request,
        Pekerjaan $pekerjaan
    ) {
        $this->milik($pekerjaan);

        $data = $request->validate(
            $this->rules()
        );

        $error = DB::transaction(
            function () use (
                $pekerjaan,
                $data
            ): ?string {
                $current = Pekerjaan::lockForUpdate()
                    ->findOrFail(
                        $pekerjaan->id_pekerjaan
                    );

                $this->milik($current);

                if (
                    $current->status_pekerjaan !==
                    'tersedia'
                ) {
                    return 'Lowongan hanya bisa diedit saat statusnya Aktif.';
                }

                $accepted =
                    $current->jumlahDiterima();

                if (
                    $data['jumlah_pekerja']
                    < $accepted
                ) {
                    throw ValidationException::withMessages([
                        'jumlah_pekerja' =>
                            'Kuota tidak boleh lebih kecil dari jumlah pekerja yang diterima.',
                    ]);
                }

                $current->update($data);

                if (
                    $accepted ===
                    (int) $current->jumlah_pekerja
                ) {
                    $current->update([
                        'status_pekerjaan' =>
                            'penuh',
                    ]);

                    $current->tolakPelamarMenunggu(
                        'kuota pekerjaan sudah terpenuhi'
                    );
                }

                return null;
            }
        );

        return redirect()
            ->route(
                'pemberi.pekerjaan.show',
                $pekerjaan
            )
            ->with(
                $error
                    ? 'error'
                    : 'success',
                $error
                    ?? 'Lowongan diperbarui.'
            );
    }

    public function destroy(
        Pekerjaan $pekerjaan
    ) {
        $this->milik($pekerjaan);

        $error = DB::transaction(
            function () use (
                $pekerjaan
            ): ?string {
                $current = Pekerjaan::lockForUpdate()
                    ->findOrFail(
                        $pekerjaan->id_pekerjaan
                    );

                $this->milik($current);

                if (
                    $current->lamaran()->exists()
                ) {
                    return 'Lowongan ini sudah punya pelamar, jadi tidak bisa dihapus.';
                }

                $current->delete();

                return null;
            }
        );

        return $error
            ? back()->with(
                'error',
                $error
            )
            : redirect()
                ->route(
                    'pemberi.pekerjaan.index'
                )
                ->with(
                    'success',
                    'Lowongan dihapus.'
                );
    }

    // ---------------------------------------------------------------
    // PROGRESS PEKERJAAN (item 16 & 20)
    // Aktif -> Penuh -> Sedang Dikerjakan -> Selesai
    // Aktif/Penuh -> Ditutup
    // ---------------------------------------------------------------

    // Mulai pekerjaan:
    // butuh minimal 1 pekerja diterima.
    // Pelamar yang masih menunggu ditolak.
    public function mulai(
        Pekerjaan $pekerjaan
    ) {
        $this->milik($pekerjaan);

        $error = DB::transaction(
            function () use (
                $pekerjaan
            ) {
                $p = Pekerjaan::lockForUpdate()
                    ->findOrFail(
                        $pekerjaan->id_pekerjaan
                    );

                if (
                    ! in_array(
                        $p->status_pekerjaan,
                        ['tersedia', 'penuh'],
                        true
                    )
                ) {
                    return 'Pekerjaan tidak bisa dimulai pada status ini.';
                }

                $pekerja = $p->lamaran()
                    ->where(
                        'status_lamaran',
                        'diterima'
                    )
                    ->get();

                if (
                    $pekerja->isEmpty()
                ) {
                    return 'Belum ada pelamar yang diterima. Terima minimal satu pelamar dulu.';
                }

                $p->tolakPelamarMenunggu(
                    'pekerjaan sudah dimulai'
                );

                $p->update([
                    'status_pekerjaan' =>
                        'sedang_dikerjakan',
                ]);

                foreach ($pekerja as $l) {
                    Notifikasi::kirim(
                        $l->id_pencari,
                        'pencari_kerja',
                        'Pekerjaan "'
                        . $p->nama_pekerjaan
                        . '" sudah dimulai. Selamat bekerja!'
                    );
                }

                return null;
            }
        );

        return $error
            ? back()->with(
                'error',
                $error
            )
            : back()->with(
                'success',
                'Pekerjaan dimulai. Status: Sedang dikerjakan.'
            );
    }

    // Tutup lowongan = batalkan rekrutmen.
    // Hanya jika belum ada pekerja diterima.
    public function tutup(
        Pekerjaan $pekerjaan
    ) {
        $this->milik($pekerjaan);

        $error = DB::transaction(
            function () use (
                $pekerjaan
            ) {
                $p = Pekerjaan::lockForUpdate()
                    ->findOrFail(
                        $pekerjaan->id_pekerjaan
                    );

                if (
                    ! in_array(
                        $p->status_pekerjaan,
                        ['tersedia', 'penuh'],
                        true
                    )
                ) {
                    return 'Lowongan tidak bisa ditutup pada status ini.';
                }

                if (
                    $p->jumlahDiterima() > 0
                ) {
                    return 'Sudah ada pekerja yang diterima. Gunakan "Mulai Pekerjaan" untuk melanjutkan.';
                }

                $p->tolakPelamarMenunggu(
                    'lowongan ditutup oleh pemberi kerja'
                );

                $p->update([
                    'status_pekerjaan' =>
                        'ditutup',
                ]);

                return null;
            }
        );

        return $error
            ? back()->with(
                'error',
                $error
            )
            : back()->with(
                'success',
                'Lowongan ditutup.'
            );
    }

    // ---------------------------------------------------------------

    private function rules(): array
    {
        return [
            'nama_pekerjaan' =>
                'required|string|max:150',

            'id_keahlian' =>
                'required|exists:keahlian,id_keahlian',

            'deskripsi' =>
                'required|string|max:2000',

            'lokasi' =>
                'required|string|max:500',

            'tanggal_pengerjaan' =>
                'required|date|after_or_equal:today',

            'jumlah_pekerja' =>
                'required|integer|min:1|max:100',

            'upah' =>
                'required|numeric|min:0|max:9999999999',

            'persyaratan' =>
                'nullable|string|max:2000',

            'latitude' =>
                'nullable|numeric|between:-90,90',

            'longitude' =>
                'nullable|numeric|between:-180,180',
        ];
    }

    // Pastikan lowongan ini benar-benar milik
    // pemberi yang sedang login.
    private function milik(
        Pekerjaan $pekerjaan
    ): void {
        abort_unless(
            (int) $pekerjaan->id_pemberi ===
            (int) session('user_id'),
            403
        );
    }
}