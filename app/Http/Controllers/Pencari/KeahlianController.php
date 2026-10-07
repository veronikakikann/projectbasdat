<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\KeahlianPencariKerja;
use App\Models\PencariKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KeahlianController extends Controller
{
    public function index()
    {
        $idPencari = session('user_id');

        $data = KeahlianPencariKerja::with('keahlian')
            ->where('id_pencari', $idPencari)
            ->orderByDesc('tanggal_upload')
            ->get();

        return view(
            'keahlian_pencari_kerja.index',
            compact('data')
        );
    }

    public function create()
    {
        return view('keahlian_pencari_kerja.create');
    }

    public function store(Request $request)
    {
        $idPencari = session('user_id');

        if (!PencariKerja::where(
            'id_pencari',
            $idPencari
        )->exists()) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Data Pencari Kerja tidak ditemukan.'
                );
        }

        $data = $request->validate([
            'judul_keahlian' =>
                'required|string|max:255',

            'deskripsi_keahlian' =>
                'required|string',

            'file_surat_rekomendasi' =>
                'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = $request
            ->file('file_surat_rekomendasi')
            ->store(
                'surat_rekomendasi',
                'public'
            );

        KeahlianPencariKerja::create([
            'id_pencari' =>
                $idPencari,

            // Kategori ditentukan Admin
            'id_keahlian' =>
                null,

            'judul_keahlian' =>
                $data['judul_keahlian'],

            'deskripsi_keahlian' =>
                $data['deskripsi_keahlian'],

            'file_surat_rekomendasi' =>
                $filePath,

            'status_verifikasi_keahlian' =>
                'menunggu',

            'tanggal_upload' =>
                now(),
        ]);

        return redirect()
            ->route('keahlian_pencari_kerja.index')
            ->with(
                'success',
                'Keahlian berhasil diajukan dan menunggu verifikasi admin.'
            );
    }

    public function edit(int $id_keahlian_pencari)
    {
        $row = KeahlianPencariKerja::where(
            'id_keahlian_pencari',
            $id_keahlian_pencari
        )
            ->where(
                'id_pencari',
                session('user_id')
            )
            ->first();

        abort_unless($row, 404);

        abort_unless(
            in_array(
                $row->status_verifikasi_keahlian,
                ['menunggu', 'ditolak'],
                true
            ),
            403
        );

        return view(
            'keahlian_pencari_kerja.edit',
            compact('row')
        );
    }

    public function update(
        Request $request,
        int $id_keahlian_pencari
    ) {
        $row = KeahlianPencariKerja::where(
            'id_keahlian_pencari',
            $id_keahlian_pencari
        )
            ->where(
                'id_pencari',
                session('user_id')
            )
            ->first();

        abort_unless($row, 404);

        abort_unless(
            in_array(
                $row->status_verifikasi_keahlian,
                ['menunggu', 'ditolak'],
                true
            ),
            403
        );

        $data = $request->validate([
            'judul_keahlian' =>
                'required|string|max:255',

            'deskripsi_keahlian' =>
                'required|string',

            'file_surat_rekomendasi' =>
                'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $row->judul_keahlian =
            $data['judul_keahlian'];

        $row->deskripsi_keahlian =
            $data['deskripsi_keahlian'];

        // Jika ditolak lalu diperbaiki,
        // pengajuan kembali menunggu verifikasi.
        $row->status_verifikasi_keahlian =
            'menunggu';

        if ($request->hasFile('file_surat_rekomendasi')) {

            if ($row->file_surat_rekomendasi) {
                Storage::disk('public')->delete(
                    $row->file_surat_rekomendasi
                );
            }

            $row->file_surat_rekomendasi =
                $request
                    ->file('file_surat_rekomendasi')
                    ->store(
                        'surat_rekomendasi',
                        'public'
                    );
        }

        $row->tanggal_upload = now();

        $row->save();

        return redirect()
            ->route('keahlian_pencari_kerja.index')
            ->with(
                'success',
                'Pengajuan keahlian diperbarui dan menunggu verifikasi ulang.'
            );
    }

    public function destroy(int $id_keahlian_pencari)
    {
        $row = KeahlianPencariKerja::where(
            'id_keahlian_pencari',
            $id_keahlian_pencari
        )
            ->where(
                'id_pencari',
                session('user_id')
            )
            ->first();

        abort_unless($row, 404);

        abort_unless(
            in_array(
                $row->status_verifikasi_keahlian,
                ['menunggu', 'ditolak'],
                true
            ),
            403
        );

        if ($row->file_surat_rekomendasi) {
            Storage::disk('public')->delete(
                $row->file_surat_rekomendasi
            );
        }

        $row->delete();

        return redirect()
            ->route('keahlian_pencari_kerja.index')
            ->with(
                'success',
                'Pengajuan keahlian berhasil dihapus.'
            );
    }
}