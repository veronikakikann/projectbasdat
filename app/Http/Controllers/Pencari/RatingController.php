<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    private const ARAH = 'pekerja_ke_pemberi';

    public function form(Lamaran $lamaran)
    {
        $this->bolehMenilai($lamaran);

        $rating = Rating::where(
            'id_lamaran',
            $lamaran->id_lamaran
        )
            ->where(
                'arah_rating',
                self::ARAH
            )
            ->where(
                'pemberi_rating',
                session('user_id')
            )
            ->first();

        return view(
            'pencari.rating.form',
            compact('lamaran', 'rating')
        );
    }

    public function store(
        Request $request,
        Lamaran $lamaran
    ) {
        $this->bolehMenilai($lamaran);

        $sudahAda = Rating::where(
            'id_lamaran',
            $lamaran->id_lamaran
        )
            ->where(
                'arah_rating',
                self::ARAH
            )
            ->where(
                'pemberi_rating',
                session('user_id')
            )
            ->exists();

        if ($sudahAda) {
            return back()->with(
                'error',
                'Kamu sudah memberikan rating untuk pemberi kerja ini.'
            );
        }

        $data = $request->validate([
            'skor' =>
                'required|integer|min:1|max:5',

            'kategori_komentar' =>
                'nullable|string|max:100',
        ]);

        Rating::create([
            'id_lamaran' =>
                $lamaran->id_lamaran,

            'pemberi_rating' =>
                session('user_id'),

            'penerima_rating' =>
                $lamaran->pekerjaan->id_pemberi,

            'arah_rating' =>
                self::ARAH,

            'skor' =>
                $data['skor'],

            'kategori_komentar' =>
                $data['kategori_komentar'] ?? null,

            'tanggal_rating' =>
                now(),
        ]);

        return redirect()
            ->route(
                'pencari.lamaran-saya'
            )
            ->with(
                'success',
                'Rating berhasil diberikan.'
            );
    }

    public function update(
        Request $request,
        Rating $rating
    ) {
        abort_unless(
            $rating->arah_rating === self::ARAH
                && (int) $rating->pemberi_rating
                    === (int) session('user_id'),
            403
        );

        if (!$rating->bisaDiedit()) {
            return back()->with(
                'error',
                'Batas waktu edit rating sudah lewat.'
            );
        }

        $data = $request->validate([
            'skor' =>
                'required|integer|min:1|max:5',

            'kategori_komentar' =>
                'nullable|string|max:100',
        ]);

        $rating->update($data);

        return back()->with(
            'success',
            'Rating berhasil diperbarui.'
        );
    }

    private function bolehMenilai(Lamaran $lamaran): void
    {
        $lamaran->load('pekerjaan');

        abort_unless(
            (int) $lamaran->id_pencari
                === (int) session('user_id'),
            403
        );

        abort_unless(
            $lamaran->status_lamaran === 'selesai',
            403,
            'Rating hanya dapat diberikan setelah pekerjaan selesai.'
        );
    }
}