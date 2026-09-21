<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::all();
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function create()
    {
        return view('notifikasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'tipe_user' => 'required|in:pencari_kerja,pemberi_kerja',
            'isi_pesan' => 'required|string',
            'status_baca' => 'required|boolean',
            'tanggal' => 'required|date',
        ]);

        Notifikasi::create($request->only([
            'id_user', 'tipe_user', 'isi_pesan', 'status_baca', 'tanggal',
        ]));

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil ditambahkan.');
    }

    public function edit(Notifikasi $notifikasi)
    {
        return view('notifikasi.edit', compact('notifikasi'));
    }

    public function update(Request $request, Notifikasi $notifikasi)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'tipe_user' => 'required|in:pencari_kerja,pemberi_kerja',
            'isi_pesan' => 'required|string',
            'status_baca' => 'required|boolean',
            'tanggal' => 'required|date',
        ]);

        $notifikasi->update($request->only([
            'id_user', 'tipe_user', 'isi_pesan', 'status_baca', 'tanggal',
        ]));

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil diperbarui.');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dihapus.');
    }
}