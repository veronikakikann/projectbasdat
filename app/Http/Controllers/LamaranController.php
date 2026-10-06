<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\Pekerjaan;
use App\Models\PencariKerja;
use Illuminate\Http\Request;

class LamaranController extends Controller
{
    public function lamaranSaya()
    {
    $idPencari = session('user_id');

    $lamaran = Lamaran::with('pekerjaan')
        ->where('id_pencari', $idPencari)
        ->orderBy('tanggal_submit', 'desc')
        ->get();

    return view('pencari_kerja.lamaran-saya', compact('lamaran'));
    }

    public function index()
    {
        $lamaran = Lamaran::with(['pekerjaan', 'pencariKerja'])->get();

        return view('lamaran.index', compact('lamaran'));
    }

    public function create()
    {
        $pekerjaan = Pekerjaan::all();
        $pencariKerja = PencariKerja::all();

        return view('lamaran.create', compact('pekerjaan', 'pencariKerja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pekerjaan' => 'required|exists:pekerjaan,id_pekerjaan',
            'id_pencari' => 'required|exists:pencari_kerja,id_pencari',
            'status_lamaran' => 'required|in:menunggu,diterima,ditolak',
            'tanggal_submit' => 'required|date',
        ]);

        Lamaran::create([
            'id_pekerjaan' => $request->id_pekerjaan,
            'id_pencari' => $request->id_pencari,
            'status_lamaran' => $request->status_lamaran,
            'tanggal_submit' => $request->tanggal_submit,
        ]);

        return redirect()
            ->route('lamaran.index')
            ->with('success', 'Lamaran berhasil ditambahkan.');
    }

    public function lamar(Pekerjaan $pekerjaan)
    {
        $idPencari = session('user_id');

        if (!$idPencari || !PencariKerja::where('id_pencari', $idPencari)->exists()) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login sebagai Pencari Kerja terlebih dahulu.');
        }

        if ($pekerjaan->status_pekerjaan !== 'tersedia') {
            return back()->with('error', 'Pekerjaan ini sudah tidak tersedia.');
        }

        $sudahMelamar = Lamaran::where('id_pekerjaan', $pekerjaan->id_pekerjaan)
            ->where('id_pencari', $idPencari)
            ->exists();

        if ($sudahMelamar) {
            return back()->with('error', 'Kamu sudah melamar pekerjaan ini.');
        }

        $jumlahDiterima = Lamaran::where('id_pekerjaan', $pekerjaan->id_pekerjaan)
            ->where('status_lamaran', 'diterima')
            ->count();

        if ($jumlahDiterima >= $pekerjaan->jumlah_pekerja) {
            return back()->with('error', 'Kuota pekerja untuk pekerjaan ini sudah penuh.');
        }

        Lamaran::create([
            'id_pekerjaan' => $pekerjaan->id_pekerjaan,
            'id_pencari' => $idPencari,
            'status_lamaran' => 'menunggu',
            'tanggal_submit' => now()->toDateString(),
        ]);

        return redirect()
            ->route('pencari.cari-pekerjaan')
            ->with('success', 'Lamaran berhasil dikirim.');
    }

    public function edit(Lamaran $lamaran)
    {
        $pekerjaan = Pekerjaan::all();
        $pencariKerja = PencariKerja::all();

        return view('lamaran.edit', compact('lamaran', 'pekerjaan', 'pencariKerja'));
    }

    public function update(Request $request, Lamaran $lamaran)
    {
        $request->validate([
            'id_pekerjaan' => 'required|exists:pekerjaan,id_pekerjaan',
            'id_pencari' => 'required|exists:pencari_kerja,id_pencari',
            'status_lamaran' => 'required|in:menunggu,diterima,ditolak',
            'tanggal_submit' => 'required|date',
        ]);

        $lamaran->update([
            'id_pekerjaan' => $request->id_pekerjaan,
            'id_pencari' => $request->id_pencari,
            'status_lamaran' => $request->status_lamaran,
            'tanggal_submit' => $request->tanggal_submit,
        ]);

        return redirect()
            ->route('lamaran.index')
            ->with('success', 'Lamaran berhasil diperbarui.');
    }

    public function destroy(Lamaran $lamaran)
    {
        $lamaran->delete();

        return redirect()
            ->route('lamaran.index')
            ->with('success', 'Lamaran berhasil dihapus.');
    }
}