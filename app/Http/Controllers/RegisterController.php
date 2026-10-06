<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // =========================
    // HALAMAN REGISTER
    // =========================
    public function showRegister()
    {
        return view('register');
    }


    // =========================
    // PROSES REGISTER
    // =========================
    public function register(Request $request)
    {
        $request->validate([
            'role' => 'required|in:pemberi_kerja,pencari_kerja',
            'nik' => 'required|unique:pencari_kerja,nik|unique:pemberi_kerja,nik',
            'file_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'nama' => 'required',
            'alamat' => 'required',
            'no_telpon' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);


        // =========================
        // CEK EMAIL
        // =========================

        $emailPencari = DB::table('pencari_kerja')
            ->where('email', $request->email)
            ->exists();

        $emailPemberi = DB::table('pemberi_kerja')
            ->where('email', $request->email)
            ->exists();

        if ($emailPencari || $emailPemberi) {

            return back()
                ->withInput()
                ->with('error', 'Email sudah terdaftar.');
        }

        $fileKtp = $request->file('file_ktp');
        $pathKtp = $fileKtp->store('ktp');


        // =========================
        // REGISTER PEMBERI KERJA
        // =========================

        if ($request->role === 'pemberi_kerja') {

            DB::table('pemberi_kerja')->insert([
                'nik' => $request->nik,
                'file_ktp' => $pathKtp,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_telpon' => $request->no_telpon,
                'email' => $request->email,
                'password' => Hash::make($request->password),

                'status_verifikasi' => 'menunggu',

                'id_admin' => null,

                'tanggal_daftar' => now(),
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Registrasi berhasil! Silakan login setelah akun diverifikasi admin.'
                );
        }


        // =========================
        // REGISTER PENCARI KERJA
        // =========================

        if ($request->role === 'pencari_kerja') {

            DB::table('pencari_kerja')->insert([
                'nik' => $request->nik,
                'file_ktp' => $pathKtp,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_telpon' => $request->no_telpon,
                'email' => $request->email,
                'password' => Hash::make($request->password),

                'foto_profil' => null,
                'latitude' => null,
                'longitude' => null,
                'file_surat_pengantar' => null,

                'status_verifikasi' => 'menunggu',

                'id_admin' => null,

                'tanggal_daftar' => now(),
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Registrasi berhasil! Silakan login setelah akun diverifikasi admin.'
                );
        }


        return back()
            ->withInput()
            ->with('error', 'Registrasi gagal.');
    }
}