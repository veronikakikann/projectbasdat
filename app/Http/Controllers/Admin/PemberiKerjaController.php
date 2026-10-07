<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PemberiKerja;
use Illuminate\Http\Request;

class PemberiKerjaController extends Controller
{
    public function index()
    {
        $pemberiKerja = PemberiKerja::with('admin')
            ->orderBy('nama')
            ->get();

        return view('pemberi_kerja.index', compact('pemberiKerja'));
    }

    public function create()
    {
        $admins = Admin::orderBy('nama')->get();

        return view('pemberi_kerja.create', compact('admins'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => 'required|string|max:16|unique:pemberi_kerja,nik',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telpon' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:pemberi_kerja,email|unique:pencari_kerja,email',
            'password' => 'required|string|min:6|confirmed',
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak',
            'status_akun' => 'nullable|in:aktif,nonaktif',
            'id_admin' => 'nullable|exists:admin,id_admin',
            'tanggal_daftar' => 'required|date',
        ]);

        PemberiKerja::create([
            'nik' => $data['nik'],
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'no_telpon' => $data['no_telpon'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'status_verifikasi' => $data['status_verifikasi'],
            'status_akun' => $data['status_akun'] ?? 'aktif',
            'id_admin' => $data['id_admin'] ?? null,
            'tanggal_daftar' => $data['tanggal_daftar'],
        ]);

        return redirect()
            ->route('pemberi_kerja.index')
            ->with('success', 'Pemberi kerja berhasil ditambahkan.');
    }

    public function edit(PemberiKerja $pemberi_kerja)
    {
        $admins = Admin::orderBy('nama')->get();

        return view('pemberi_kerja.edit', [
            'pemberiKerja' => $pemberi_kerja,
            'admins' => $admins,
        ]);
    }

    public function update(
        Request $request,
        PemberiKerja $pemberi_kerja
    ) {
        $data = $request->validate([
            'nik' => 'required|string|max:16|unique:pemberi_kerja,nik,' .
                $pemberi_kerja->id_pemberi . ',id_pemberi',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telpon' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:pemberi_kerja,email,' .
                $pemberi_kerja->id_pemberi . ',id_pemberi',
            'password' => 'nullable|string|min:6|confirmed',
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak',
            'status_akun' => 'required|in:aktif,nonaktif',
            'id_admin' => 'nullable|exists:admin,id_admin',
            'tanggal_daftar' => 'required|date',
        ]);

        $pemberi_kerja->nik = $data['nik'];
        $pemberi_kerja->nama = $data['nama'];
        $pemberi_kerja->alamat = $data['alamat'];
        $pemberi_kerja->no_telpon = $data['no_telpon'];
        $pemberi_kerja->email = $data['email'];
        $pemberi_kerja->status_verifikasi = $data['status_verifikasi'];
        $pemberi_kerja->status_akun = $data['status_akun'];
        $pemberi_kerja->id_admin = $data['id_admin'] ?? null;
        $pemberi_kerja->tanggal_daftar = $data['tanggal_daftar'];

        if (!empty($data['password'])) {
            $pemberi_kerja->password = bcrypt($data['password']);
        }

        $pemberi_kerja->save();

        return redirect()
            ->route('pemberi_kerja.index')
            ->with('success', 'Data pemberi kerja berhasil diperbarui.');
    }

    public function destroy(PemberiKerja $pemberi_kerja)
    {
        $pemberi_kerja->delete();

        return redirect()
            ->route('pemberi_kerja.index')
            ->with('success', 'Pemberi kerja berhasil dihapus.');
    }
}