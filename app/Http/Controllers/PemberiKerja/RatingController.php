<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    private const ARAH = 'pemberi_ke_pekerja';

    // Form beri rating / edit rating ke satu pekerja (item 22)
    public function form(Lamaran $lamaran)
    {
        $this->bolehMenilai($lamaran);

        $rating = $this->ratingKu($lamaran);

        return view('pemberi.rating.form', compact('lamaran', 'rating'));
    }

    public function store(Request $request, Lamaran $lamaran)
    {
        $this->bolehMenilai($lamaran);

        if ($this->ratingKu($lamaran)) {
            return back()->with('error', 'Kamu sudah memberi rating untuk pekerja ini.');
        }

        $data = $request->validate($this->rules());

        Rating::create($data + [
            'id_lamaran'      => $lamaran->id_lamaran,
            'arah_rating'     => self::ARAH,
            'pemberi_rating'  => session('user_id'),
            'penerima_rating' => $lamaran->id_pencari,
            'tanggal_rating'  => now(),
        ]);

        return redirect()->route('pemberi.pekerjaan.show', $lamaran->id_pekerjaan)
            ->with('success', 'Rating tersimpan. Rating bisa diedit sampai ' . Rating::BATAS_EDIT_JAM . ' jam ke depan.');
    }

    // Edit rating: hanya dalam batas waktu (item 23). Tidak ada fitur hapus rating.
    public function update(Request $request, Rating $rating)
    {
        abort_unless(
            $rating->arah_rating === self::ARAH && (int) $rating->pemberi_rating === (int) session('user_id'),
            403
        );

        $lamaran = Lamaran::with('pekerjaan')->findOrFail($rating->id_lamaran);

        if (!$rating->bisaDiedit()) {
            return redirect()->route('pemberi.pekerjaan.show', $lamaran->id_pekerjaan)
                ->with('error', 'Batas waktu edit rating (' . Rating::BATAS_EDIT_JAM . ' jam) sudah lewat.');
        }

        $rating->update($request->validate($this->rules()));

        return redirect()->route('pemberi.pekerjaan.show', $lamaran->id_pekerjaan)
            ->with('success', 'Rating diperbarui.');
    }

    // ---------------------------------------------------------------

    private function rules(): array
    {
        return [
            'skor'              => 'required|integer|min:1|max:5',
            'kategori_komentar' => 'nullable|string|max:100',
        ];
    }

    // Lamaran harus di lowongan milik saya DAN sudah selesai
    private function bolehMenilai(Lamaran $lamaran): void
    {
        $lamaran->loadMissing('pekerjaan');

        abort_unless((int) $lamaran->pekerjaan->id_pemberi === (int) session('user_id'), 403);
        abort_unless($lamaran->status_lamaran === 'selesai', 403, 'Rating baru bisa diberikan setelah pekerjaan selesai.');
    }

    private function ratingKu(Lamaran $lamaran): ?Rating
    {
        return Rating::where('id_lamaran', $lamaran->id_lamaran)
            ->where('arah_rating', self::ARAH)
            ->where('pemberi_rating', session('user_id'))
            ->first();
    }
}
