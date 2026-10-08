<?php

namespace App\Http\Controllers\Pemberi;

use App\Http\Controllers\Controller;
use App\Models\PemberiKerja;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

class ProfilController extends Controller
{
    // Profil + rating yang diterima dari para pekerja (item 19)
    public function show()
    {
        $user = $this->user();

        $penilaian = fn () => Rating::where('arah_rating', 'pekerja_ke_pemberi')
            ->where('penerima_rating', $user->id_pemberi);

        $stat = $penilaian()->selectRaw('AVG(skor) as rata, COUNT(*) as jumlah')->first();
        $ulasan = $penilaian()->with('lamaran.pencariKerja')->orderByDesc('tanggal_rating')->limit(5)->get();

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
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:500',
            'no_telpon' => 'required|string|max:15',
            'email' => [
                'required', 'email', 'max:100',
                Rule::unique('pemberi_kerja', 'email')->ignore($user->id_pemberi, 'id_pemberi'),
                Rule::unique('pencari_kerja', 'email'),
                Rule::unique('admin', 'email'),
            ],
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => [
                'nullable', 
                'string', 
                'min:8',             // Minimal 8 karakter
                'regex:/[a-zA-Z]/',  // Harus mengandung huruf
                'regex:/[0-9]/',     // Harus mengandung angka
                'confirmed'          // Harus cocok dengan kolom konfirmasi
            ],
        ]);

        $newPhoto = null;
        $oldPhoto = null;
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        unset($data['foto_profil']);
        try {
            DB::transaction(function () use ($user, $request, $data, &$newPhoto, &$oldPhoto): void {
                $current = PemberiKerja::lockForUpdate()->findOrFail($user->id_pemberi);
                if ($request->hasFile('foto_profil')) {
                    $oldPhoto = $current->foto_profil;
                    $newPhoto = $request->file('foto_profil')->store('foto_profil', 'local');
                    $data['foto_profil'] = $newPhoto;
                }
                $current->update($data);
            });
        } catch (Throwable $exception) {
            if ($newPhoto) {
                Storage::disk('local')->delete($newPhoto);
            }
            throw $exception;
        }
        if ($oldPhoto) {
            Storage::disk('local')->delete($oldPhoto);
        }
        $user->refresh();

        session(['user_name' => $user->nama]);

        return redirect()->route('pemberi.profil.show')->with('success', 'Profil diperbarui.');
    }

    // Foto profil disimpan privat, jadi disajikan lewat controller
    public function foto()
    {
        $user = $this->user();

        abort_unless($user->foto_profil && Storage::disk('local')->exists($user->foto_profil), 404);

        return Storage::disk('local')->response($user->foto_profil);
    }

    private function user(): PemberiKerja
    {
        return PemberiKerja::findOrFail(session('user_id'));
    }
}
