<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\Pekerjaan;
use App\Models\PencariKerja;
use Illuminate\Http\Request;

class LamaranController extends Controller
{
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

        Lamaran::create($request->only([
            'id_pekerjaan', 'id_pencari', 'status_lamaran', 'tanggal_submit',
        ]));

        return redirect()->route('lamaran.index')->with('success', 'Lamaran berhasil ditambahkan.');
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

        $lamaran->update($request->only([
            'id_pekerjaan', 'id_pencari', 'status_lamaran', 'tanggal_submit',
        ]));

        return redirect()->route('lamaran.index')->with('success', 'Lamaran berhasil diperbarui.');
    }

    public function destroy(Lamaran $lamaran)
    {
        $lamaran->delete();
        return redirect()->route('lamaran.index')->with('success', 'Lamaran berhasil dihapus.');
    }
}