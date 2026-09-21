<?php

namespace App\Http\Controllers;

use App\Models\PencariKerja;
use App\Models\Keahlian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeahlianPencariKerjaController extends Controller
{
    public function index()
    {
        $data = DB::table('keahlian_pencari_kerja')
            ->join('pencari_kerja', 'keahlian_pencari_kerja.id_pencari', '=', 'pencari_kerja.id_pencari')
            ->join('keahlian', 'keahlian_pencari_kerja.id_keahlian', '=', 'keahlian.id_keahlian')
            ->select(
                'keahlian_pencari_kerja.*',
                'pencari_kerja.nama as nama_pencari',
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
        $request->validate([
            'id_pencari' => 'required|exists:pencari_kerja,id_pencari',
            'id_keahlian' => 'required|exists:keahlian,id_keahlian',
            'file_surat_rekomendasi' => 'nullable|string|max:255',
            'status_verifikasi_keahlian' => 'required|in:menunggu,terverifikasi,ditolak',
            'tanggal_upload' => 'required|date',
        ]);

        DB::table('keahlian_pencari_kerja')->insert([
            'id_pencari' => $request->id_pencari,
            'id_keahlian' => $request->id_keahlian,
            'file_surat_rekomendasi' => $request->file_surat_rekomendasi,
            'status_verifikasi_keahlian' => $request->status_verifikasi_keahlian,
            'tanggal_upload' => $request->tanggal_upload,
        ]);

        return redirect()->route('keahlian_pencari_kerja.index')->with('success', 'Data berhasil ditambahkan.');
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