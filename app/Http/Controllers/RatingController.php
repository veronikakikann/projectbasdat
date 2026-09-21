<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Lamaran;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $rating = Rating::with('lamaran')->get();
        return view('rating.index', compact('rating'));
    }

    public function create()
    {
        $lamaran = Lamaran::all();
        return view('rating.create', compact('lamaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_lamaran' => 'required|exists:lamaran,id_lamaran',
            'pemberi_rating' => 'required|integer',
            'penerima_rating' => 'required|integer',
            'arah_rating' => 'required|in:pekerja_ke_pemberi,pemberi_ke_pekerja',
            'skor' => 'required|integer|min:1|max:5',
            'kategori_komentar' => 'nullable|string|max:100',
            'tanggal_rating' => 'required|date',
        ]);

        Rating::create($request->only([
            'id_lamaran', 'pemberi_rating', 'penerima_rating', 'arah_rating',
            'skor', 'kategori_komentar', 'tanggal_rating',
        ]));

        return redirect()->route('rating.index')->with('success', 'Rating berhasil ditambahkan.');
    }

    public function edit(Rating $rating)
    {
        $lamaran = Lamaran::all();
        return view('rating.edit', compact('rating', 'lamaran'));
    }

    public function update(Request $request, Rating $rating)
    {
        $request->validate([
            'id_lamaran' => 'required|exists:lamaran,id_lamaran',
            'pemberi_rating' => 'required|integer',
            'penerima_rating' => 'required|integer',
            'arah_rating' => 'required|in:pekerja_ke_pemberi,pemberi_ke_pekerja',
            'skor' => 'required|integer|min:1|max:5',
            'kategori_komentar' => 'nullable|string|max:100',
            'tanggal_rating' => 'required|date',
        ]);

        $rating->update($request->only([
            'id_lamaran', 'pemberi_rating', 'penerima_rating', 'arah_rating',
            'skor', 'kategori_komentar', 'tanggal_rating',
        ]));

        return redirect()->route('rating.index')->with('success', 'Rating berhasil diperbarui.');
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return redirect()->route('rating.index')->with('success', 'Rating berhasil dihapus.');
    }
}