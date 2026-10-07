<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PencariKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PencariKerjaController extends Controller
{
    /**
     * Menampilkan seluruh data pencari kerja.
     */
    public function index()
    {
        $pencariKerja = PencariKerja::with('admin')
            ->orderBy('nama')
            ->get();

        return view('pencari_kerja.index', compact('pencariKerja'));
    }

    /**
     * Menampilkan form tambah pencari kerja.
     */
    public function create()
    {
        $admins = Admin::orderBy('nama')->get();

        return view('pencari_kerja.create', compact('admins'));
    }

    /**
     * Menyimpan data pencari kerja baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => [
                'required',
                'digits:16',
                'unique:pencari_kerja,nik',
                'unique:pemberi_kerja,nik',
            ],

            'nama' => 'required|string|max:100',

            'alamat' => 'required|string',

            'no_telpon' => 'required|string|max:20',

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:pencari_kerja,email',
                'unique:pemberi_kerja,email',
                'unique:admin,email',
            ],

            'password' => 'required|string|min:6|confirmed',

            'latitude' => 'nullable|numeric|between:-90,90',

            'longitude' => 'nullable|numeric|between:-180,180',

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

        PencariKerja::create([
            'nik' => $data['nik'],
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'no_telpon' => $data['no_telpon'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'status_verifikasi' => $data['status_verifikasi'],
            'status_akun' => $data['status_akun'] ?? 'aktif',
            'id_admin' => $data['id_admin'] ?? null,
            'tanggal_daftar' => $data['tanggal_daftar'],
        ]);

        return redirect()
            ->route('pencari_kerja.index')
            ->with('success', 'Pencari kerja berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit pencari kerja.
     */
    public function edit(PencariKerja $pencari_kerja)
    {
        $admins = Admin::orderBy('nama')->get();

        return view('pencari_kerja.edit', [
            'pencariKerja' => $pencari_kerja,
            'admins' => $admins,
        ]);
    }

    /**
     * Memperbarui data pencari kerja.
     *
     * NIK TIDAK DIUBAH karena merupakan identitas pemilik akun.
     */
    public function update(
        Request $request,
        PencariKerja $pencari_kerja
    ) {
        $data = $request->validate([
            'nama' => 'required|string|max:100',

            'alamat' => 'required|string',

            'no_telpon' => 'required|string|max:20',

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:pencari_kerja,email,' .
                    $pencari_kerja->id_pencari . ',id_pencari',
                'unique:pemberi_kerja,email',
                'unique:admin,email',
            ],

            'password' => 'nullable|string|min:6|confirmed',

            'latitude' => 'nullable|numeric|between:-90,90',

            'longitude' => 'nullable|numeric|between:-180,180',

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

        $pencari_kerja->nama = $data['nama'];

        $pencari_kerja->alamat = $data['alamat'];

        $pencari_kerja->no_telpon = $data['no_telpon'];

        $pencari_kerja->email = $data['email'];

        $pencari_kerja->latitude =
            $data['latitude'] ?? null;

        $pencari_kerja->longitude =
            $data['longitude'] ?? null;

        $pencari_kerja->status_verifikasi =
            $data['status_verifikasi'];

        $pencari_kerja->status_akun =
            $data['status_akun'];

        $pencari_kerja->id_admin =
            $data['id_admin'] ?? null;

        $pencari_kerja->tanggal_daftar =
            $data['tanggal_daftar'];

        // Password hanya diubah jika Admin mengisi password baru.
        if (!empty($data['password'])) {
            $pencari_kerja->password =
                Hash::make($data['password']);
        }

        $pencari_kerja->save();

        return redirect()
            ->route('pencari_kerja.index')
            ->with('success', 'Data pencari kerja berhasil diperbarui.');
    }

    /**
     * Menghapus data pencari kerja.
     */
    public function destroy(PencariKerja $pencari_kerja)
    {
        $pencari_kerja->delete();

        return redirect()
            ->route('pencari_kerja.index')
            ->with('success', 'Pencari kerja berhasil dihapus.');
    }
}