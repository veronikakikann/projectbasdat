<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use App\Models\PemberiKerja;
use App\Models\Keahlian;
use App\Models\PencariKerja;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    public function cari(Request $request)
    {
    $idPencari = session('user_id');

    $pencari = PencariKerja::find($idPencari);

    if (!$pencari) {
        return redirect()
            ->route('login')
            ->with('error', 'Data Pencari Kerja tidak ditemukan.');
    }

    $keahlianIds = $pencari->keahlian()
        ->wherePivot('status_verifikasi_keahlian', 'terverifikasi')
        ->pluck('keahlian.id_keahlian');

    $query = Pekerjaan::with(['pemberiKerja', 'keahlian'])
        ->where('status_pekerjaan', 'tersedia')
        ->whereIn('id_keahlian', $keahlianIds);

    if ($pencari->latitude !== null && $pencari->longitude !== null) {

        $latitude = $pencari->latitude;
        $longitude = $pencari->longitude;

        $query->select('*')
            ->selectRaw(
                '(6371 * acos(
                    cos(radians(?))
                    * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?))
                    * sin(radians(latitude))
                )) AS jarak_km',
                [$latitude, $longitude, $latitude]
            )
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->having('jarak_km', '<=', 10)
            ->orderBy('jarak_km');
    } else {
        $query->orderByDesc('tanggal_posting');
    }

    $pekerjaan = $query->get();

    return view(
        'pencari_kerja.cari-pekerjaan',
        compact('pekerjaan')
    );
    }

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
            'nama_pekerjaan' => 'required|string|max:255',
            'jumlah_pekerja' => 'required|integer|min:1',
            'persyaratan' => 'nullable|string',
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
            'nama_pekerjaan' => $request->nama_pekerjaan,
            'jumlah_pekerja' => $request->jumlah_pekerja,
            'persyaratan' => $request->persyaratan,
            'deskripsi' => $request->deskripsi,
            'upah' => $request->upah,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'tanggal_pengerjaan' => $request->tanggal_pengerjaan,
            'status_pekerjaan' => $request->status_pekerjaan,
            'tanggal_posting' => $request->tanggal_posting,
        ]);

        return redirect()
            ->route('pekerjaan.index')
            ->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function show(Pekerjaan $pekerjaan)
    {
    $pekerjaan->load(['pemberiKerja', 'keahlian']);

    return view('pencari_kerja.detail-pekerjaan', compact('pekerjaan'));
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
            'nama_pekerjaan' => 'required|string|max:255',
            'jumlah_pekerja' => 'required|integer|min:1',
            'persyaratan' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'upah' => 'required|numeric|min:0',
            'lokasi' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'tanggal_pengerjaan' => 'nullable|date',
            'status_pekerjaan' => 'required|in:tersedia,sedang_dikerjakan,selesai',
            'tanggal_posting' => 'required|date',
        ]);

        $pekerjaan->update([
            'id_pemberi' => $request->id_pemberi,
            'id_keahlian' => $request->id_keahlian,
            'nama_pekerjaan' => $request->nama_pekerjaan,
            'jumlah_pekerja' => $request->jumlah_pekerja,
            'persyaratan' => $request->persyaratan,
            'deskripsi' => $request->deskripsi,
            'upah' => $request->upah,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'tanggal_pengerjaan' => $request->tanggal_pengerjaan,
            'status_pekerjaan' => $request->status_pekerjaan,
            'tanggal_posting' => $request->tanggal_posting,
        ]);

        return redirect()
            ->route('pekerjaan.index')
            ->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(Pekerjaan $pekerjaan)
    {
        $pekerjaan->delete();

        return redirect()
            ->route('pekerjaan.index')
            ->with('success', 'Pekerjaan berhasil dihapus.');
    }
}