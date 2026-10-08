<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\PencariKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function show()
    {
        $pencari = $this->user();

        return view(
            'pencari.profil.show',
            compact('pencari')
        );
    }

    public function edit()
    {
        $pencari = $this->user();

        return view(
            'pencari.profil.edit',
            compact('pencari')
        );
    }

    public function update(Request $request)
    {
        $pencari = $this->user();

        $data = $request->validate(
            [
                'nama' => 'required|string|max:100',

                'alamat' => 'required|string|max:500',

                'no_telpon' => 'required|string|max:15',

                'email' => [
                    'required',
                    'email',
                    'max:100',

                    Rule::unique(
                        'pencari_kerja',
                        'email'
                    )->ignore(
                        $pencari->id_pencari,
                        'id_pencari'
                    ),

                    Rule::unique(
                        'pemberi_kerja',
                        'email'
                    ),

                    Rule::unique(
                        'admin',
                        'email'
                    ),
                ],

                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'regex:/[a-zA-Z]/',
                    'regex:/[0-9]/',
                    'confirmed',
                ],

                'latitude' => [
                    'nullable',
                    'numeric',
                    'between:-90,90',
                ],

                'longitude' => [
                    'nullable',
                    'numeric',
                    'between:-180,180',
                ],
            ],
            [
                'password.min' =>
                    'Password minimal 8 karakter.',

                'password.regex' =>
                    'Password harus mengandung huruf dan angka.',

                'password.confirmed' =>
                    'Konfirmasi password tidak sama.',
            ]
        );

        $pencari->nama = $data['nama'];

        $pencari->alamat = $data['alamat'];

        $pencari->no_telpon = $data['no_telpon'];

        $pencari->email = $data['email'];

        $pencari->latitude =
            $data['latitude'] ?? null;

        $pencari->longitude =
            $data['longitude'] ?? null;

        if (!empty($data['password'])) {
            $pencari->password = Hash::make(
                $data['password']
            );
        }

        // NIK, status verifikasi, dan status akun
        // tidak dapat diubah melalui halaman profil.
        $pencari->save();

        // Perbarui nama pada session.
        session([
            'user_name' => $pencari->nama,
        ]);

        return redirect()
            ->route('pencari.profil')
            ->with(
                'success',
                'Profil berhasil diperbarui.'
            );
    }

    private function user(): PencariKerja
    {
        return PencariKerja::findOrFail(
            session('user_id')
        );
    }
}