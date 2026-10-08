<?php

namespace App\Http\Controllers;

use App\Support\PrivateDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function showRegister()
    {
        return view('register');
    }

    /**
     * Memproses registrasi Pemberi Kerja / Pencari Kerja.
     */
    public function register(Request $request)
    {
        $request->validate([
            'role' => [
                'required',
                'in:pemberi_kerja,pencari_kerja',
            ],

            'nik' => [
                'required',
                'numeric',
                'digits:16',
            ],

            'file_ktp' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],

            'nama' => [
                'required',
                'string',
                'max:100',
            ],

            'alamat' => [
                'required',
                'string',
            ],

            'no_telpon' => [
                'required',
                'string',
                'max:15',
            ],

            'email' => [
                'required',
                'email',
                'max:100',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-zA-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],

            /*
             * Koordinat hanya dibutuhkan untuk Pencari Kerja.
             * Nilainya berasal dari map, bukan input manual.
             */
            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
                'required_if:role,pencari_kerja',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
                'required_if:role,pencari_kerja',
            ],
        ], [
            'nik.required' =>
                'NIK wajib diisi.',

            'nik.numeric' =>
                'NIK hanya boleh berisi angka.',

            'nik.digits' =>
                'NIK harus tepat 16 digit angka.',

            'file_ktp.required' =>
                'File KTP wajib diunggah.',

            'file_ktp.mimes' =>
                'Format file KTP harus berupa jpg, jpeg, png, atau pdf.',

            'file_ktp.max' =>
                'Ukuran file KTP terlalu besar (maksimal 2 MB).',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'password.required' =>
                'Password wajib diisi.',

            'password.min' =>
                'Password minimal 8 karakter.',

            'password.regex' =>
                'Password harus mengandung kombinasi huruf dan angka.',

            'password.confirmed' =>
                'Konfirmasi password tidak cocok.',

            'latitude.required_if' =>
                'Pilih titik lokasi pada peta.',

            'latitude.numeric' =>
                'Koordinat latitude tidak valid.',

            'latitude.between' =>
                'Koordinat latitude tidak valid.',

            'longitude.required_if' =>
                'Pilih titik lokasi pada peta.',

            'longitude.numeric' =>
                'Koordinat longitude tidak valid.',

            'longitude.between' =>
                'Koordinat longitude tidak valid.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK NIK
        |--------------------------------------------------------------------------
        */

        $nikSudahAda =
            DB::table('pencari_kerja')
                ->where('nik', $request->nik)
                ->exists()
            ||
            DB::table('pemberi_kerja')
                ->where('nik', $request->nik)
                ->exists();

        if ($nikSudahAda) {
            return back()
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                    ])
                )
                ->with(
                    'error',
                    'NIK sudah terdaftar.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK EMAIL
        |--------------------------------------------------------------------------
        */

        $emailSudahAda =
            DB::table('pencari_kerja')
                ->where('email', $request->email)
                ->exists()
            ||
            DB::table('pemberi_kerja')
                ->where('email', $request->email)
                ->exists()
            ||
            DB::table('admin')
                ->where('email', $request->email)
                ->exists();

        if ($emailSudahAda) {
            return back()
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                    ])
                )
                ->with(
                    'error',
                    'Email sudah terdaftar.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FILE KTP
        |--------------------------------------------------------------------------
        */

        $pathKtp = $request
            ->file('file_ktp')
            ->store('ktp', 'local');

        /*
        |--------------------------------------------------------------------------
        | DATA UMUM
        |--------------------------------------------------------------------------
        */

        $dataUmum = [
            'nik' => $request->nik,
            'file_ktp' => $pathKtp,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telpon' => $request->no_telpon,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status_verifikasi' => 'menunggu',
            'status_akun' => 'aktif',
            'id_admin' => null,
            'tanggal_daftar' => now(),
        ];

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA
        |--------------------------------------------------------------------------
        */

        try {
            DB::transaction(function () use (
                $request,
                $dataUmum
            ) {
                if ($request->role === 'pemberi_kerja') {

                    DB::table('pemberi_kerja')
                        ->insert($dataUmum);

                    return;
                }

                if ($request->role === 'pencari_kerja') {

                    DB::table('pencari_kerja')
                        ->insert([
                            ...$dataUmum,

                            'foto_profil' => null,

                            'latitude' => $request->latitude,

                            'longitude' => $request->longitude,

                            'file_surat_pengantar' => null,
                        ]);
                }
            });
        } catch (\Throwable $e) {
            PrivateDocuments::deleteUnused($pathKtp);

            report($e);

            return back()
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                    ])
                )
                ->with(
                    'error',
                    'Registrasi gagal. Silakan coba lagi.'
                );
        }

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Registrasi berhasil. Akun kamu menunggu verifikasi admin.'
            );
    }
}