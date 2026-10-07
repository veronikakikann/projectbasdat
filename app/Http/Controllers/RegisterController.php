<?php

namespace App\Http\Controllers;

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
                'in:pemberi_kerja,pencari_kerja'
            ],

            'nik' => [
                'required',
                'string',
                'max:16',
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
                'max:20',
            ],

            'email' => [
                'required',
                'email',
                'max:100',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK NIK
        |--------------------------------------------------------------------------
        */

        $nikSudahAda = DB::table('pencari_kerja')
            ->where('nik', $request->nik)
            ->exists()
            ||
            DB::table('pemberi_kerja')
                ->where('nik', $request->nik)
                ->exists();

        if ($nikSudahAda) {
            return back()
                ->withInput()
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

        $emailSudahAda = DB::table('pencari_kerja')
            ->where('email', $request->email)
            ->exists()
            ||
            DB::table('pemberi_kerja')
                ->where('email', $request->email)
                ->exists();

        if ($emailSudahAda) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Email sudah terdaftar.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FILE KTP
        |--------------------------------------------------------------------------
        |
        | Disimpan di storage/app/public/ktp
        | sehingga dapat digunakan untuk proses verifikasi Admin.
        |
        */

        $pathKtp = $request
            ->file('file_ktp')
            ->store(
                'ktp',
                'public'
            );

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

            // Akun baru belum diverifikasi admin
            'status_verifikasi' => 'menunggu',

            // Akun aktif secara sistem,
            // tetapi akses nantinya tetap dibatasi oleh status_verifikasi
            'status_akun' => 'aktif',

            // Belum ada admin yang melakukan verifikasi
            'id_admin' => null,

            'tanggal_daftar' => now(),
        ];

        /*
        |--------------------------------------------------------------------------
        | REGISTER PEMBERI KERJA
        |--------------------------------------------------------------------------
        */

        if ($request->role === 'pemberi_kerja') {

            DB::table('pemberi_kerja')
                ->insert($dataUmum);

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Registrasi berhasil. Akun kamu menunggu verifikasi admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | REGISTER PENCARI KERJA
        |--------------------------------------------------------------------------
        */

        if ($request->role === 'pencari_kerja') {

            $dataPencari = array_merge(
                $dataUmum,
                [
                    'foto_profil' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'file_surat_pengantar' => null,
                ]
            );

            DB::table('pencari_kerja')
                ->insert($dataPencari);

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Registrasi berhasil. Akun kamu menunggu verifikasi admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput()
            ->with(
                'error',
                'Registrasi gagal.'
            );
    }
}