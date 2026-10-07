<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PemberiKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PemberiKerjaController extends Controller
{
    /**
     * Menampilkan seluruh data pemberi kerja.
     */
    public function index()
    {
        $pemberiKerja = PemberiKerja::with('admin')
            ->orderBy('nama')
            ->get();

        return view('pemberi_kerja.index', compact('pemberiKerja'));
    }

    /**
     * Menampilkan form tambah pemberi kerja.
     */
    public function create()
    {
        $admins = Admin::orderBy('nama')->get();

        return view('pemberi_kerja.create', compact('admins'));
    }

    /**
     * Menyimpan data pemberi kerja baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => [
                'required',
                'digits:16',
                'unique:pemberi_kerja,nik',
                'unique:pencari_kerja,nik',
            ],

            'nama' => 'required|string|max:100',

            'alamat' => 'required|string',

            'no_telpon' => 'required|string|max:20',

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:pemberi_kerja,email',
                'unique:pencari_kerja,email',
                'unique:admin,email',
            ],

            'password' => 'required|string|min:6|confirmed',

            'status_verifikasi' => [
                'required',
                'in:menunggu,terverifikasi,ditolak',
            ],

            'status_akun' => [
                'nullable',
                'in:aktif,nonaktif',
            ],

            'id_admin' => [
                'nullable',
                'exists:admin,id_admin',
            ],

            'tanggal_daftar' => 'required|date',
        ]);

        PemberiKerja::create([
            'nik' => $data['nik'],
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'no_telpon' => $data['no_telpon'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status_verifikasi' => $data['status_verifikasi'],
            'status_akun' => $data['status_akun'] ?? 'aktif',
            'id_admin' => $data['id_admin'] ?? null,
            'tanggal_daftar' => $data['tanggal_daftar'],
        ]);

        return redirect()
            ->route('pemberi_kerja.index')
            ->with('success', 'Pemberi kerja berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit pemberi kerja.
     */
    public function edit(PemberiKerja $pemberi_kerja)
    {
        $admins = Admin::orderBy('nama')->get();

        return view('pemberi_kerja.edit', [
            'pemberiKerja' => $pemberi_kerja,
            'admins' => $admins,
        ]);
    }

    /**
     * Memperbarui data pemberi kerja.
     *
     * NIK TIDAK DIUBAH karena merupakan identitas pemilik akun.
     */
    public function update(
        Request $request,
        PemberiKerja $pemberi_kerja
    ) {
        $data = $request->validate([
            'nama' => 'required|string|max:100',

            'alamat' => 'required|string',

            'no_telpon' => 'required|string|max:20',

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:pemberi_kerja,email,' .
                    $pemberi_kerja->id_pemberi . ',id_pemberi',
                'unique:pencari_kerja,email',
                'unique:admin,email',
            ],

            'password' => 'nullable|string|min:6|confirmed',

            'status_verifikasi' => [
                'required',
                'in:menunggu,terverifikasi,ditolak',
            ],

            'status_akun' => [
                'required',
                'in:aktif,nonaktif',
            ],

            'id_admin' => [
                'nullable',
                'exists:admin,id_admin',
            ],

            'tanggal_daftar' => 'required|date',
        ]);

        // =========================================================
        // NIK SENGAJA TIDAK DIUBAH
        // =========================================================

        $pemberi_kerja->nama = $data['nama'];

        $pemberi_kerja->alamat = $data['alamat'];

        $pemberi_kerja->no_telpon = $data['no_telpon'];

        $pemberi_kerja->email = $data['email'];

        $pemberi_kerja->status_verifikasi =
            $data['status_verifikasi'];

        $pemberi_kerja->status_akun =
            $data['status_akun'];

        $pemberi_kerja->id_admin =
            $data['id_admin'] ?? null;

        $pemberi_kerja->tanggal_daftar =
            $data['tanggal_daftar'];

        // Password hanya diubah jika Admin mengisi password baru.
        if (!empty($data['password'])) {
            $pemberi_kerja->password =
                Hash::make($data['password']);
        }

        $pemberi_kerja->save();

        return redirect()
            ->route('pemberi_kerja.index')
            ->with('success', 'Data pemberi kerja berhasil diperbarui.');
    }

    /**
     * Menghapus data pemberi kerja.
     */
    public function destroy(PemberiKerja $pemberi_kerja)
    {
        $pemberi_kerja->delete();

        return redirect()
            ->route('pemberi_kerja.index')
            ->with('success', 'Pemberi kerja berhasil dihapus.');
    }
}