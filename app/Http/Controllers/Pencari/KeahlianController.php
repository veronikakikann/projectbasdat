<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\KeahlianPencariKerja;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class KeahlianController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'menunggu');

        abort_unless(
            in_array(
                $status,
                ['menunggu', 'terverifikasi', 'ditolak'],
                true
            ),
            404
        );

        $data = KeahlianPencariKerja::with('keahlian')
            ->where('id_pencari', session('user_id'))
            ->where('status_verifikasi_keahlian', $status)
            ->orderByDesc('tanggal_upload')
            ->get();

        return view(
            'pencari.keahlian.index',
            compact('data', 'status')
        );
    }

    public function create(): View
    {
        return view(
            'pencari.keahlian.create'
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $data = $request->validate(
            $this->rules(true)
        );

        $path = Storage::disk('public')->putFile(
            'surat_rekomendasi',
            $request->file('file_surat_rekomendasi')
        );

        try {
            KeahlianPencariKerja::create([
                'id_pencari' => session('user_id'),
                'id_keahlian' => null,
                'judul_keahlian' =>
                    $data['judul_keahlian'],
                'deskripsi_keahlian' =>
                    $data['deskripsi_keahlian'],
                'file_surat_rekomendasi' =>
                    $path,
                'status_verifikasi_keahlian' =>
                    'menunggu',
                'tanggal_upload' => now(),
            ]);
        } catch (Throwable $exception) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }

        return redirect()
            ->route(
                'keahlian_pencari_kerja.index',
                ['status' => 'menunggu']
            )
            ->with(
                'success',
                'Keahlian menunggu verifikasi admin.'
            );
    }

    public function edit(
        int $id_keahlian_pencari
    ): View {
        $row = $this->pengajuan(
            $id_keahlian_pencari
        );

        $this->bolehMengubah($row);

        return view(
            'pencari.keahlian.edit',
            compact('row')
        );
    }

    public function update(
        Request $request,
        int $id_keahlian_pencari
    ): RedirectResponse {
        $this->pengajuan(
            $id_keahlian_pencari
        );

        $data = $request->validate(
            $this->rules(false)
        );

        $newPath = null;
        $oldPath = null;

        try {
            DB::transaction(
                function () use (
                    $request,
                    $id_keahlian_pencari,
                    $data,
                    &$newPath,
                    &$oldPath
                ): void {
                    $row = $this->pengajuan(
                        $id_keahlian_pencari,
                        true
                    );

                    $this->bolehMengubah($row);

                    $updates = [
                        'judul_keahlian' =>
                            $data['judul_keahlian'],

                        'deskripsi_keahlian' =>
                            $data['deskripsi_keahlian'],

                        'status_verifikasi_keahlian' =>
                            'menunggu',

                        'id_keahlian' => null,

                        'tanggal_upload' => now(),
                    ];

                    if (
                        $request->hasFile(
                            'file_surat_rekomendasi'
                        )
                    ) {
                        $oldPath =
                            $row->file_surat_rekomendasi;

                        $newPath = Storage::disk('public')->putFile(
                            'surat_rekomendasi',
                            $request->file(
                                'file_surat_rekomendasi'
                            )
                        );

                        $updates[
                            'file_surat_rekomendasi'
                        ] = $newPath;
                    }

                    $row->update($updates);
                }
            );
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()
            ->route(
                'keahlian_pencari_kerja.index',
                ['status' => 'menunggu']
            )
            ->with(
                'success',
                'Pengajuan kembali menunggu verifikasi.'
            );
    }

    public function destroy(
        int $id_keahlian_pencari
    ): RedirectResponse {
        $path = DB::transaction(
            function () use (
                $id_keahlian_pencari
            ): ?string {
                $row = $this->pengajuan(
                    $id_keahlian_pencari,
                    true
                );

                $this->bolehMengubah($row);

                $path =
                    $row->file_surat_rekomendasi;

                $row->delete();

                return $path;
            }
        );

        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
            ->route(
                'keahlian_pencari_kerja.index',
                ['status' => 'menunggu']
            )
            ->with(
                'success',
                'Pengajuan dihapus.'
            );
    }

    private function pengajuan(
        int $id,
        bool $lock = false
    ): KeahlianPencariKerja {
        $query = KeahlianPencariKerja::where(
            'id_pencari',
            session('user_id')
        );

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->findOrFail($id);
    }

    private function bolehMengubah(
        KeahlianPencariKerja $row
    ): void {
        abort_unless(
            in_array(
                $row->status_verifikasi_keahlian,
                ['menunggu', 'ditolak'],
                true
            ),
            403
        );
    }

    private function rules(
        bool $creating
    ): array {
        return [
            'judul_keahlian' =>
                'required|string|max:255',

            'deskripsi_keahlian' =>
                'required|string|max:2000',

            'file_surat_rekomendasi' =>
                ($creating ? 'required' : 'nullable')
                . '|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}