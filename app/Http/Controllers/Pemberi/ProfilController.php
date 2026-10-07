<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\PemberiKerja;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    // Profil + rating yang diterima dari para pekerja (item 19)
    public function show()
    {
        $user = $this->user();

        $penilaian = fn () => Rating::where('arah_rating', 'pekerja_ke_pemberi')
            ->where('penerima_rating', $user->id_pemberi);

        $stat   = $penilaian()->selectRaw('AVG(skor) as rata, COUNT(*) as jumlah')->first();
        $ulasan = $penilaian()->orderByDesc('tanggal_rating')->limit(5)->get();

        return view('pemberi.profil.show', compact('user', 'stat', 'ulasan'));
    }

    public function edit()
    {
        return view('pemberi.profil.edit', ['user' => $this->user()]);
    }

    // User hanya mengubah akunnya sendiri (id diambil dari session, bukan dari URL/form).
    // NIK dan status verifikasi tidak bisa diubah dari sini.
    public function update(Request $request)
    {
        $user = $this->user();

        $data = $request->validate([
            'nama'        => 'required|string|max:100',
            'alamat'      => 'required|string|max:500',
            'no_telpon'   => 'required|string|max:15',
            'email'       => [
                'required', 'email', 'max:100',
                Rule::unique('pemberi_kerja', 'email')->ignore($user->id_pemberi, 'id_pemberi'),
                Rule::unique('pencari_kerja', 'email'),
            ],
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('foto_profil');
        } else {
            unset($data['foto_profil']);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        session(['user_name' => $user->nama]);

        return redirect()->route('pemberi.profil.show')->with('success', 'Profil diperbarui.');
    }

    // Foto profil disimpan privat, jadi disajikan lewat controller
    public function foto()
    {
        $user = $this->user();

        abort_unless($user->foto_profil && Storage::exists($user->foto_profil), 404);

        return Storage::response($user->foto_profil);
    }

    private function user(): PemberiKerja
    {
        return PemberiKerja::findOrFail(session('user_id'));
    }
}
