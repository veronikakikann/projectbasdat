<?php

namespace App\Http\Controllers;

use App\Models\PencariKerja;
use App\Models\Keahlian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeahlianPencariKerjaController extends Controller
{
    public function verifikasi()
    {
    if (session('role') !== 'admin') {
        return redirect()
            ->route('login')
            ->with('error', 'Akses hanya untuk Admin.');
    }

    $data = DB::table('keahlian_pencari_kerja')
        ->leftJoin(
            'pencari_kerja',
            'keahlian_pencari_kerja.id_pencari',
            '=',
            'pencari_kerja.id_pencari'
        )
        ->leftJoin(
            'keahlian',
            'keahlian_pencari_kerja.id_keahlian',
            '=',
            'keahlian.id_keahlian'
        )
        ->select(
            'keahlian_pencari_kerja.*',
            'pencari_kerja.nama as nama_pencari',
            'keahlian.nama_keahlian'
        )
        ->whereIn(
            'keahlian_pencari_kerja.status_verifikasi_keahlian',
            ['menunggu', 'ditolak']
        )
        ->orderByDesc('keahlian_pencari_kerja.tanggal_upload')
        ->get();

    $keahlian = Keahlian::all();

    return view(
        'admin.verifikasi-keahlian',
        compact('data', 'keahlian')
    );
    }

    public function index()
    {
    $idPencari = session('user_id');

    $data = DB::table('keahlian_pencari_kerja')
        ->leftJoin(
            'keahlian',
            'keahlian_pencari_kerja.id_keahlian',
            '=',
            'keahlian.id_keahlian'
        )
        ->where('keahlian_pencari_kerja.id_pencari', $idPencari)
        ->select(
            'keahlian_pencari_kerja.*',
            'keahlian.nama_keahlian'
        )
        ->get();

    return view('keahlian_pencari_kerja.index', compact('data'));
    }

    public function create()
    {
        $pencariKerja = PencariKerja::all();
        $keahlian = Keahlian::all();
        return view('keahlian_pencari_kerja.create', compact('pencariKerja', 'keahlian'));
    }

    public function store(Request $request)
    {
    $idPencari = session('user_id');

    $pencari = PencariKerja::find($idPencari);

    if (!$pencari) {
        return redirect()
            ->route('login')
            ->with('error', 'Silakan login sebagai Pencari Kerja terlebih dahulu.');
    }

    $request->validate([
        'judul_keahlian' => 'required|string|max:255',
        'deskripsi_keahlian' => 'required|string',
        'file_surat_rekomendasi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $filePath = $request->file('file_surat_rekomendasi')
        ->store('surat_rekomendasi', 'public');

    DB::table('keahlian_pencari_kerja')->insert([
        'id_pencari' => $idPencari,
        'id_keahlian' => null,
        'judul_keahlian' => $request->judul_keahlian,
        'deskripsi_keahlian' => $request->deskripsi_keahlian,
        'file_surat_rekomendasi' => $filePath,
        'status_verifikasi_keahlian' => 'menunggu',
        'tanggal_upload' => now()->toDateString(),
    ]);

    return redirect()
        ->route('keahlian_pencari_kerja.index')
        ->with(
            'success',
            'Keahlian berhasil diajukan dan menunggu verifikasi admin.'
        );
    }

    public function edit($id_pencari, $id_keahlian)
    {
        $row = DB::table('keahlian_pencari_kerja')
            ->where('id_pencari', $id_pencari)
            ->where('id_keahlian', $id_keahlian)
            ->first();

        $pencariKerja = PencariKerja::all();
        $keahlian = Keahlian::all();

        return view('keahlian_pencari_kerja.edit', compact('row', 'pencariKerja', 'keahlian'));
    }

    public function update(Request $request, $id_pencari, $id_keahlian)
    {
        $request->validate([
            'file_surat_rekomendasi' => 'nullable|string|max:255',
            'status_verifikasi_keahlian' => 'required|in:menunggu,terverifikasi,ditolak',
            'tanggal_upload' => 'required|date',
        ]);

        DB::table('keahlian_pencari_kerja')
            ->where('id_pencari', $id_pencari)
            ->where('id_keahlian', $id_keahlian)
            ->update([
                'file_surat_rekomendasi' => $request->file_surat_rekomendasi,
                'status_verifikasi_keahlian' => $request->status_verifikasi_keahlian,
                'tanggal_upload' => $request->tanggal_upload,
            ]);

        return redirect()->route('keahlian_pencari_kerja.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id_pencari, $id_keahlian)
    {
        DB::table('keahlian_pencari_kerja')
            ->where('id_pencari', $id_pencari)
            ->where('id_keahlian', $id_keahlian)
            ->delete();

        return redirect()->route('keahlian_pencari_kerja.index')->with('success', 'Data berhasil dihapus.');
    }
}