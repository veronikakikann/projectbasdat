<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use App\Models\PemberiKerja;
use App\Models\Keahlian;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    public function index()
    {
        $pekerjaan = Pekerjaan::with(['pemberiKerja', 'keahlian'])->get();
        return view('pekerjaan.index', compact('pekerjaan'));
    }

    public function create()
    {
        $pemberiKerja = PemberiKerja::all();
        $keahlian = Keahlian::all();
        return view('pekerjaan.create', compact('pemberiKerja', 'keahlian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pemberi' => 'required|exists:pemberi_kerja,id_pemberi',
            'id_keahlian' => 'required|exists:keahlian,id_keahlian',
            'deskripsi' => 'nullable|string',
            'upah' => 'required|numeric|min:0',
            'lokasi' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'tanggal_pengerjaan' => 'nullable|date',
            'status_pekerjaan' => 'required|in:tersedia,sedang_dikerjakan,selesai',
            'tanggal_posting' => 'required|date',
        ]);

        Pekerjaan::create([
            'id_pemberi' => $request->id_pemberi,
            'id_keahlian' => $request->id_keahlian,
            'deskripsi' => $request->deskripsi,
            'upah' => $request->upah,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'tanggal_pengerjaan' => $request->tanggal_pengerjaan,
            'status_pekerjaan' => $request->status_pekerjaan,
            'tanggal_posting' => $request->tanggal_posting,
        ]);

        return redirect()->route('pekerjaan.index')->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function edit(Pekerjaan $pekerjaan)
    {
        $pemberiKerja = PemberiKerja::all();
        $keahlian = Keahlian::all();
        return view('pekerjaan.edit', compact('pekerjaan', 'pemberiKerja', 'keahlian'));
    }

    public function update(Request $request, Pekerjaan $pekerjaan)
    {
        $request->validate([
            'id_pemberi' => 'required|exists:pemberi_kerja,id_pemberi',
            'id_keahlian' => 'required|exists:keahlian,id_keahlian',
            'deskripsi' => 'nullable|string',
            'upah' => 'required|numeric|min:0',
            'lokasi' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'tanggal_pengerjaan' => 'nullable|date',
            'status_pekerjaan' => 'required|in:tersedia,sedang_dikerjakan,selesai',
            'tanggal_posting' => 'required|date',
        ]);

        $pekerjaan->update($request->only([
            'id_pemberi', 'id_keahlian', 'deskripsi', 'upah', 'lokasi',
            'latitude', 'longitude', 'tanggal_pengerjaan', 'status_pekerjaan', 'tanggal_posting',
        ]));

        return redirect()->route('pekerjaan.index')->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(Pekerjaan $pekerjaan)
    {
        $pekerjaan->delete();
        return redirect()->route('pekerjaan.index')->with('success', 'Pekerjaan berhasil dihapus.');
    }
}