<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Rating;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    private const ARAH = 'pemberi_ke_pekerja';

    public function form(Lamaran $lamaran): View
    {
        $this->bolehMenilai($lamaran);
        $rating = Rating::where('id_lamaran', $lamaran->id_lamaran)->where('arah_rating', self::ARAH)->first();

        return view('pemberi.rating.form', compact('lamaran', 'rating'));
    }

    public function store(Request $request, Lamaran $lamaran): RedirectResponse
    {
        $this->bolehMenilai($lamaran);
        $data = $request->validate($this->rules());
        DB::transaction(function () use ($lamaran, $data): void {
            $current = Lamaran::lockForUpdate()->findOrFail($lamaran->id_lamaran);
            $this->bolehMenilai($current);
            if (Rating::where('id_lamaran', $current->id_lamaran)->where('arah_rating', self::ARAH)->exists()) {
                throw ValidationException::withMessages(['skor' => 'Rating untuk transaksi ini sudah diberikan.']);
            }
            Rating::create($data + [
                'id_lamaran' => $current->id_lamaran,
                'arah_rating' => self::ARAH,
                'pemberi_rating' => session('user_id'),
                'penerima_rating' => $current->id_pencari,
                'tanggal_rating' => now(),
            ]);
        });

        return redirect()->route('pemberi.pekerjaan.show', $lamaran->id_pekerjaan)->with('success', 'Rating berhasil disimpan.');
    }

    public function update(Request $request, Rating $rating): RedirectResponse
    {
        abort_unless($rating->arah_rating === self::ARAH
            && (int) $rating->pemberi_rating === (int) session('user_id'), 403);
        $data = $request->validate($this->rules());
        DB::transaction(function () use ($rating, $data): void {
            $current = Rating::lockForUpdate()->findOrFail($rating->id_rating);
            abort_unless($current->arah_rating === self::ARAH
                && (int) $current->pemberi_rating === (int) session('user_id'), 403);
            if (! $current->bisaDiedit()) {
                throw ValidationException::withMessages(['skor' => 'Batas waktu edit rating (24 jam) sudah lewat.']);
            }
            $current->update($data);
        });

        return redirect()->route('pemberi.pekerjaan.show', $rating->lamaran->id_pekerjaan)->with('success', 'Rating berhasil diperbarui.');
    }

    private function bolehMenilai(Lamaran $lamaran): void
    {
        $lamaran->loadMissing('pekerjaan');
        abort_unless((int) $lamaran->pekerjaan->id_pemberi === (int) session('user_id'), 403);
        abort_unless($lamaran->status_lamaran === 'selesai', 403, 'Rating hanya dapat diberikan setelah pekerjaan selesai.');
    }

    private function rules(): array
    {
        return ['skor' => 'required|integer|min:1|max:5', 'kategori_komentar' => 'nullable|string|max:100'];
    }
}
