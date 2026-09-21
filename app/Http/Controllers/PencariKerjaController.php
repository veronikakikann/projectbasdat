<?php

namespace App\Http\Controllers;

use App\Models\PencariKerja;
use App\Models\Admin;
use Illuminate\Http\Request;

class PencariKerjaController extends Controller
{
    public function index()
    {
        $pencariKerja = PencariKerja::with('admin')->get();
        return view('pencari_kerja.index', compact('pencariKerja'));
    }

    public function create()
    {
        $admins = Admin::all();
        return view('pencari_kerja.create', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:pencari_kerja,nik',
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:pencari_kerja,email',
            'password' => 'required|string|min:6',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak',
            'id_admin' => 'nullable|exists:admin,id_admin',
            'tanggal_daftar' => 'required|date',
        ]);

        PencariKerja::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telpon' => $request->no_telpon,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status_verifikasi' => $request->status_verifikasi,
            'id_admin' => $request->id_admin,
            'tanggal_daftar' => $request->tanggal_daftar,
        ]);

        return redirect()->route('pencari_kerja.index')->with('success', 'Pencari kerja berhasil ditambahkan.');
    }

    public function edit(PencariKerja $pencari_kerja)
    {
        $admins = Admin::all();
        return view('pencari_kerja.edit', ['pencariKerja' => $pencari_kerja, 'admins' => $admins]);
    }

    public function update(Request $request, PencariKerja $pencari_kerja)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:pencari_kerja,nik,' . $pencari_kerja->id_pencari . ',id_pencari',
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:pencari_kerja,email,' . $pencari_kerja->id_pencari . ',id_pencari',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak',
            'id_admin' => 'nullable|exists:admin,id_admin',
            'tanggal_daftar' => 'required|date',
        ]);

        $pencari_kerja->nik = $request->nik;
        $pencari_kerja->nama = $request->nama;
        $pencari_kerja->alamat = $request->alamat;
        $pencari_kerja->no_telpon = $request->no_telpon;
        $pencari_kerja->email = $request->email;
        if ($request->filled('password')) {
            $pencari_kerja->password = bcrypt($request->password);
        }
        $pencari_kerja->latitude = $request->latitude;
        $pencari_kerja->longitude = $request->longitude;
        $pencari_kerja->status_verifikasi = $request->status_verifikasi;
        $pencari_kerja->id_admin = $request->id_admin;
        $pencari_kerja->tanggal_daftar = $request->tanggal_daftar;
        $pencari_kerja->save();

        return redirect()->route('pencari_kerja.index')->with('success', 'Pencari kerja berhasil diperbarui.');
    }

    public function destroy(PencariKerja $pencari_kerja)
    {
        $pencari_kerja->delete();
        return redirect()->route('pencari_kerja.index')->with('success', 'Pencari kerja berhasil dihapus.');
    }
}