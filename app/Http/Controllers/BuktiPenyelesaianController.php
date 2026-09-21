<?php

namespace App\Http\Controllers;

use App\Models\BuktiPenyelesaian;
use App\Models\Lamaran;
use Illuminate\Http\Request;

class BuktiPenyelesaianController extends Controller
{
    public function index()
    {
        $buktiPenyelesaian = BuktiPenyelesaian::with('lamaran')->get();
        return view('bukti_penyelesaian.index', compact('buktiPenyelesaian'));
    }

    public function create()
    {
        $lamaran = Lamaran::all();
        return view('bukti_penyelesaian.create', compact('lamaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_lamaran' => 'required|exists:lamaran,id_lamaran|unique:bukti_penyelesaian,id_lamaran',
            'foto_bukti_kerja' => 'nullable|string|max:255',
            'foto_bukti_bayar' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal_upload' => 'required|date',
        ]);

        BuktiPenyelesaian::create($request->only([
            'id_lamaran', 'foto_bukti_kerja', 'foto_bukti_bayar', 'catatan', 'tanggal_upload',
        ]));

        return redirect()->route('bukti_penyelesaian.index')->with('success', 'Bukti penyelesaian berhasil ditambahkan.');
    }

    public function edit(BuktiPenyelesaian $bukti_penyelesaian)
    {
        $lamaran = Lamaran::all();
        return view('bukti_penyelesaian.edit', ['bukti' => $bukti_penyelesaian, 'lamaran' => $lamaran]);
    }

    public function update(Request $request, BuktiPenyelesaian $bukti_penyelesaian)
    {
        $request->validate([
            'id_lamaran' => 'required|exists:lamaran,id_lamaran|unique:bukti_penyelesaian,id_lamaran,' . $bukti_penyelesaian->id_bukti . ',id_bukti',
            'foto_bukti_kerja' => 'nullable|string|max:255',
            'foto_bukti_bayar' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal_upload' => 'required|date',
        ]);

        $bukti_penyelesaian->update($request->only([
            'id_lamaran', 'foto_bukti_kerja', 'foto_bukti_bayar', 'catatan', 'tanggal_upload',
        ]));

        return redirect()->route('bukti_penyelesaian.index')->with('success', 'Bukti penyelesaian berhasil diperbarui.');
    }

    public function destroy(BuktiPenyelesaian $bukti_penyelesaian)
    {
        $bukti_penyelesaian->delete();
        return redirect()->route('bukti_penyelesaian.index')->with('success', 'Bukti penyelesaian berhasil dihapus.');
    }
}