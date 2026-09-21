<?php

namespace App\Http\Controllers;

use App\Models\Keahlian;
use Illuminate\Http\Request;

class KeahlianController extends Controller
{
    public function index()
    {
        $keahlian = Keahlian::all();
        return view('keahlian.index', compact('keahlian'));
    }

    public function create()
    {
        return view('keahlian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_keahlian' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        Keahlian::create([
            'nama_keahlian' => $request->nama_keahlian,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('keahlian.index')->with('success', 'Keahlian berhasil ditambahkan.');
    }

    public function edit(Keahlian $keahlian)
    {
        return view('keahlian.edit', compact('keahlian'));
    }

    public function update(Request $request, Keahlian $keahlian)
    {
        $request->validate([
            'nama_keahlian' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $keahlian->nama_keahlian = $request->nama_keahlian;
        $keahlian->deskripsi = $request->deskripsi;
        $keahlian->save();

        return redirect()->route('keahlian.index')->with('success', 'Keahlian berhasil diperbarui.');
    }

    public function destroy(Keahlian $keahlian)
    {
        $keahlian->delete();
        return redirect()->route('keahlian.index')->with('success', 'Keahlian berhasil dihapus.');
    }
}