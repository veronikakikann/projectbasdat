<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keahlian;
use App\Models\KeahlianPencariKerja;
use App\Models\Notifikasi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin memverifikasi keahlian yang diajukan pencari kerja,
 * lalu menentukan kategori besarnya (Tukang AC, Tukang Bangunan, ART, dll).
 */
class VerifikasiKeahlianController extends Controller
{
    private const STATUS = ['menunggu', 'terverifikasi', 'ditolak'];

    public function index(Request $request): View
    {
        $filter = $this->filter($request->query('status'));

        $data = KeahlianPencariKerja::with(['pencariKerja', 'keahlian'])
            ->when($filter !== 'semua', fn ($q) => $q->where('status_verifikasi_keahlian', $filter))
            ->orderByRaw("CASE status_verifikasi_keahlian WHEN 'menunggu' THEN 0 ELSE 1 END")
            ->orderByDesc('tanggal_upload')
            ->orderByDesc('id_keahlian_pencari')
            ->get();

        $hitung = KeahlianPencariKerja::query()
            ->select('status_verifikasi_keahlian as status', DB::raw('COUNT(*) as total'))
            ->groupBy('status_verifikasi_keahlian')
            ->pluck('total', 'status');

        $jumlah = [
            'menunggu' => (int) ($hitung['menunggu'] ?? 0),
            'terverifikasi' => (int) ($hitung['terverifikasi'] ?? 0),
            'ditolak' => (int) ($hitung['ditolak'] ?? 0),
            'semua' => (int) $hitung->sum(),
        ];

        return view('admin.verifikasi-keahlian', [
            'data' => $data,
            'keahlian' => Keahlian::orderBy('nama_keahlian')->get(),
            'filter' => $filter,
            'jumlah' => $jumlah,
        ]);
    }

    public function keputusan(Request $request, int $id_keahlian_pencari): RedirectResponse
    {
        $request->validate([
            'status_verifikasi_keahlian' => 'required|in:'.implode(',', self::STATUS),
            'id_keahlian' => 'nullable|exists:keahlian,id_keahlian',
            'kategori_baru' => 'nullable|string|max:100',
            'kembali_ke' => 'nullable|in:'.implode(',', [...self::STATUS, 'semua']),
        ]);

        $pengajuan = KeahlianPencariKerja::findOrFail($id_keahlian_pencari);
        $status = $request->input('status_verifikasi_keahlian');

        $idKeahlian = $request->filled('id_keahlian')
            ? (int) $request->input('id_keahlian')
            : $pengajuan->id_keahlian;

        // Admin boleh membuat kategori baru langsung dari form ini.
        if ($request->filled('kategori_baru')) {
            $idKeahlian = Keahlian::firstOrCreate(
                ['nama_keahlian' => trim($request->input('kategori_baru'))],
                ['deskripsi' => null]
            )->id_keahlian;
        }

        // Untuk menyetujui, admin wajib menentukan kategorinya.
        if ($status === 'terverifikasi' && ! $idKeahlian) {
            return back()
                ->withInput()
                ->withErrors(['id_keahlian' => 'Pilih kategori keahlian (atau isi kategori baru) sebelum menyetujui.']);
        }

        $pengajuan->update([
            'id_keahlian' => $idKeahlian,
            'status_verifikasi_keahlian' => $status,
        ]);

        $judul = $pengajuan->judul_keahlian ?: 'keahlianmu';

        if ($status === 'terverifikasi') {
            Notifikasi::kirim((int) $pengajuan->id_pencari, 'pencari_kerja', "Keahlian \"{$judul}\" sudah diverifikasi admin.");
            $pesan = 'Keahlian berhasil diverifikasi.';
        } elseif ($status === 'ditolak') {
            Notifikasi::kirim((int) $pengajuan->id_pencari, 'pencari_kerja', "Keahlian \"{$judul}\" ditolak admin. Periksa kembali surat rekomendasi kamu.");
            $pesan = 'Keahlian ditolak.';
        } else {
            $pesan = 'Status keahlian dikembalikan ke menunggu.';
        }

        return redirect()
            ->route('admin.verifikasi-keahlian', ['status' => $request->input('kembali_ke', 'menunggu')])
            ->with('success', $pesan);
    }

    private function filter(?string $status): string
    {
        return in_array($status, [...self::STATUS, 'semua'], true) ? $status : 'menunggu';
    }
}
