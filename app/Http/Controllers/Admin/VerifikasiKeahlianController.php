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
use Illuminate\Validation\ValidationException;

class VerifikasiKeahlianController extends Controller
{
    public function index(): View
    {
        $data = KeahlianPencariKerja::with(['pencariKerja', 'keahlian'])
            ->where('status_verifikasi_keahlian', 'menunggu')->orderByDesc('tanggal_upload')->get();
        $keahlian = Keahlian::orderBy('nama_keahlian')->get();

        return view('admin.verifikasi-keahlian', compact('data', 'keahlian'));
    }

    public function keputusan(Request $request, int $id_keahlian_pencari): RedirectResponse
    {
        $data = $request->validate([
            'status_verifikasi_keahlian' => 'required|in:terverifikasi,ditolak',
            'id_keahlian' => 'required_if:status_verifikasi_keahlian,terverifikasi|nullable|exists:keahlian,id_keahlian',
        ]);

        DB::transaction(function () use ($data, $id_keahlian_pencari): void {
            if ($data['status_verifikasi_keahlian'] === 'terverifikasi') {
                Keahlian::lockForUpdate()->findOrFail($data['id_keahlian']);
            }
            $pengajuan = KeahlianPencariKerja::lockForUpdate()->findOrFail($id_keahlian_pencari);
            if ($pengajuan->status_verifikasi_keahlian !== 'menunggu') {
                throw ValidationException::withMessages(['status_verifikasi_keahlian' => 'Pengajuan sudah diproses.']);
            }
            $pengajuan->update([
                'id_keahlian' => $data['status_verifikasi_keahlian'] === 'terverifikasi' ? $data['id_keahlian'] : null,
                'status_verifikasi_keahlian' => $data['status_verifikasi_keahlian'],
            ]);
            Notifikasi::kirim($pengajuan->id_pencari, 'pencari_kerja',
                'Pengajuan keahlian "'.$pengajuan->judul_keahlian.'" '.$data['status_verifikasi_keahlian'].' oleh admin.');
        });

        return back()->with('success', 'Keputusan verifikasi berhasil disimpan.');
    }
}
