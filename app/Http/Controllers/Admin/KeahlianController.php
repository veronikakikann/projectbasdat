<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keahlian;
use Illuminate\Http\Request;

class KeahlianController extends Controller
{
    /**
     * Menampilkan seluruh master keahlian.
     */
    public function index()
    {
        $keahlian = Keahlian::orderBy('nama_keahlian')->get();

        return view('keahlian.index', compact('keahlian'));
    }

    /**
     * Menampilkan form tambah keahlian.
     */
    public function create()
    {
        return view('keahlian.create');
    }

    /**
     * Menyimpan master keahlian baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_keahlian' => [
                'required',
                'string',
                'max:100',
                'unique:keahlian,nama_keahlian',
            ],

            'deskripsi' => 'nullable|string',
        ]);

        Keahlian::create($data);

        return redirect()
            ->route('keahlian.index')
            ->with('success', 'Keahlian berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit keahlian.
     */
    public function edit(Keahlian $keahlian)
    {
        return view('keahlian.edit', compact('keahlian'));
    }

    /**
     * Memperbarui master keahlian.
     */
    public function update(
        Request $request,
        Keahlian $keahlian
    ) {
        $data = $request->validate([
            'nama_keahlian' => [
                'required',
                'string',
                'max:100',
                'unique:keahlian,nama_keahlian,' .
                    $keahlian->id_keahlian . ',id_keahlian',
            ],

            'deskripsi' => 'nullable|string',
        ]);

        $keahlian->update($data);

        return redirect()
            ->route('keahlian.index')
            ->with('success', 'Keahlian berhasil diperbarui.');
    }

    /**
     * Menghapus master keahlian.
     */
    public function destroy(Keahlian $keahlian)
    {
        // Tidak boleh menghapus keahlian yang
        // masih digunakan oleh lowongan pekerjaan.
        if ($keahlian->pekerjaan()->exists()) {
            return back()->with(
                'error',
                'Keahlian masih digunakan oleh lowongan pekerjaan.'
            );
        }

        // Tidak boleh menghapus keahlian yang
        // masih digunakan oleh pengajuan pencari kerja.
        if ($keahlian->pengajuanPencari()->exists()) {
            return back()->with(
                'error',
                'Keahlian masih digunakan oleh pengajuan pencari kerja.'
            );
        }

        $keahlian->delete();

        return redirect()
            ->route('keahlian.index')
            ->with(
                'success',
                'Keahlian berhasil dihapus.'
            );
    }
}