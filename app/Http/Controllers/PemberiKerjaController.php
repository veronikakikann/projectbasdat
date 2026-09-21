<?php

namespace App\Http\Controllers;

use App\Models\PemberiKerja;
use App\Models\Admin;
use Illuminate\Http\Request;

class PemberiKerjaController extends Controller
{
    public function index()
    {
        $pemberiKerja = PemberiKerja::with('admin')->get();
        return view('pemberi_kerja.index', compact('pemberiKerja'));
    }

    public function create()
    {
        $admins = Admin::all();
        return view('pemberi_kerja.create', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:pemberi_kerja,nik',
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:pemberi_kerja,email',
            'password' => 'required|string|min:6',
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak',
            'id_admin' => 'nullable|exists:admin,id_admin',
            'tanggal_daftar' => 'required|date',
        ]);

        PemberiKerja::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telpon' => $request->no_telpon,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status_verifikasi' => $request->status_verifikasi,
            'id_admin' => $request->id_admin,
            'tanggal_daftar' => $request->tanggal_daftar,
        ]);

        return redirect()->route('pemberi_kerja.index')->with('success', 'Pemberi kerja berhasil ditambahkan.');
    }

    public function edit(PemberiKerja $pemberi_kerja)
    {
        $admins = Admin::all();
        return view('pemberi_kerja.edit', ['pemberiKerja' => $pemberi_kerja, 'admins' => $admins]);
    }

    public function update(Request $request, PemberiKerja $pemberi_kerja)
    {
        $request->validate([
            'nik' => 'required|string|max:16|unique:pemberi_kerja,nik,' . $pemberi_kerja->id_pemberi . ',id_pemberi',
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:pemberi_kerja,email,' . $pemberi_kerja->id_pemberi . ',id_pemberi',
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak',
            'id_admin' => 'nullable|exists:admin,id_admin',
            'tanggal_daftar' => 'required|date',
        ]);

        $pemberi_kerja->nik = $request->nik;
        $pemberi_kerja->nama = $request->nama;
        $pemberi_kerja->alamat = $request->alamat;
        $pemberi_kerja->no_telpon = $request->no_telpon;
        $pemberi_kerja->email = $request->email;
        if ($request->filled('password')) {
            $pemberi_kerja->password = bcrypt($request->password);
        }
        $pemberi_kerja->status_verifikasi = $request->status_verifikasi;
        $pemberi_kerja->id_admin = $request->id_admin;
        $pemberi_kerja->tanggal_daftar = $request->tanggal_daftar;
        $pemberi_kerja->save();

        return redirect()->route('pemberi_kerja.index')->with('success', 'Pemberi kerja berhasil diperbarui.');
    }

    public function destroy(PemberiKerja $pemberi_kerja)
    {
        $pemberi_kerja->delete();
        return redirect()->route('pemberi_kerja.index')->with('success', 'Pemberi kerja berhasil dihapus.');
    }
}