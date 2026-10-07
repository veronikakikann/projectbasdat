<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\PencariKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KeahlianController extends Controller
{
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
            ->where(
                'keahlian_pencari_kerja.id_pencari',
                $idPencari
            )
            ->select(
                'keahlian_pencari_kerja.*',
                'keahlian.nama_keahlian'
            )
            ->orderByDesc(
                'keahlian_pencari_kerja.tanggal_upload'
            )
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

        if (
            !PencariKerja::where(
                'id_pencari',
                $idPencari
            )->exists()
        ) {
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

        DB::table('keahlian_pencari_kerja')
            ->insert([
                'id_pencari' => $idPencari,
                'id_keahlian' => null,
                'judul_keahlian' =>
                    $data['judul_keahlian'],
                'deskripsi_keahlian' =>
                    $data['deskripsi_keahlian'],
                'file_surat_rekomendasi' =>
                    $filePath,
                'status_verifikasi_keahlian' =>
                    'menunggu',
                'tanggal_upload' =>
                    now()->toDateString(),
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
        $row = DB::table('keahlian_pencari_kerja')
            ->where(
                'id_keahlian_pencari',
                $id_keahlian_pencari
            )
            ->where(
                'id_pencari',
                session('user_id')
            )
            ->first();

        abort_unless($row, 404);

        // Hanya data yang belum final yang boleh diedit
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
        $row = DB::table('keahlian_pencari_kerja')
            ->where(
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

        $update = [
            'judul_keahlian' =>
                $data['judul_keahlian'],

            'deskripsi_keahlian' =>
                $data['deskripsi_keahlian'],

            // Set kembali ke menunggu setelah perubahan
            'status_verifikasi_keahlian' =>
                'menunggu',
        ];

        if ($request->hasFile('file_surat_rekomendasi')) {
            if ($row->file_surat_rekomendasi) {
                Storage::disk('public')->delete(
                    $row->file_surat_rekomendasi
                );
            }

            $update['file_surat_rekomendasi'] =
                $request
                    ->file('file_surat_rekomendasi')
                    ->store(
                        'surat_rekomendasi',
                        'public'
                    );
        }

        DB::table('keahlian_pencari_kerja')
            ->where(
                'id_keahlian_pencari',
                $id_keahlian_pencari
            )
            ->update($update);

        return redirect()
            ->route('keahlian_pencari_kerja.index')
            ->with(
                'success',
                'Pengajuan keahlian diperbarui dan menunggu verifikasi ulang.'
            );
    }

    public function destroy(int $id_keahlian_pencari)
    {
        $row = DB::table('keahlian_pencari_kerja')
            ->where(
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

        DB::table('keahlian_pencari_kerja')
            ->where(
                'id_keahlian_pencari',
                $id_keahlian_pencari
            )
            ->delete();

        return redirect()
            ->route('keahlian_pencari_kerja.index')
            ->with(
                'success',
                'Pengajuan keahlian berhasil dihapus.'
            );
    }
}