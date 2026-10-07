<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,pemberi_kerja,pencari_kerja',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $role = $request->role;

        // =========================================================
        // LOGIN ADMIN
        // =========================================================
        if ($role === 'admin') {

            $user = DB::table('admin')
                ->where('email', $request->email)
                ->first();

            if (
                $user &&
                Hash::check($request->password, $user->password)
            ) {
                // Regenerasi session setelah login berhasil
                $request->session()->regenerate();

                session([
                    'login' => true,
                    'role' => 'admin',
                    'user_id' => $user->id_admin,
                    'user_name' => $user->nama,
                ]);

                return redirect()
                    ->route('admin.dashboard');
            }
        }

        // =========================================================
        // LOGIN PEMBERI KERJA
        // =========================================================
        if ($role === 'pemberi_kerja') {

            $user = DB::table('pemberi_kerja')
                ->where('email', $request->email)
                ->first();

            // Cek apakah email ada di database
            if (!$user) {
                return back()
                    ->withErrors(['email' => 'Email belum terdaftar sebagai pemberi kerja.'])
                    ->withInput($request->except('password'));
            }

            // Cek apakah password cocok
            if (!Hash::check($request->password, $user->password)) {
                return back()
                    ->withErrors(['password' => 'Password yang kamu masukkan salah.'])
                    ->withInput($request->except('password'));
            }

            // Cek status verifikasi admin
            if ($user->status_verifikasi === 'menunggu') {
                return back()->withInput($request->except('password'))
                    ->with('error', 'Akun kamu masih menunggu verifikasi admin.');
            }

            if ($user->status_verifikasi !== 'terverifikasi') {
                return back()->withInput($request->except('password'))
                    ->with('error', 'Akun belum diverifikasi admin.');
            }

            // Cek status akun
            if ($user->status_akun !== 'aktif') {
                return back()->withInput($request->except('password'))
                    ->with('error', 'Akun sedang dinonaktifkan.');
            }

            // Regenerasi session setelah semua pengecekan lolos
            $request->session()->regenerate();

            // SIMPAN SESSION LOGIN
            $request->session()->put('login', true);
            $request->session()->put('role', 'pemberi_kerja');
            $request->session()->put('user_id', $user->id_pemberi); // Sesuaikan dengan nama ID di database
            $request->session()->put('user_name', $user->nama);

            return redirect()->route('pemberi.dashboard');
        }

        // =========================================================
        // LOGIN PENCARI KERJA
        // =========================================================
        if ($role === 'pencari_kerja') {

            $user = DB::table('pencari_kerja')
                ->where('email', $request->email)
                ->first();

            // Cek apakah email ada di database
            if (!$user) {
                return back()
                    ->withErrors(['email' => 'Email belum terdaftar sebagai pencari kerja.'])
                    ->withInput($request->except('password'));
            }

            // Cek apakah password cocok
            if (!Hash::check($request->password, $user->password)) {
                return back()
                    ->withErrors(['password' => 'Password yang kamu masukkan salah.'])
                    ->withInput($request->except('password'));
            }

            // Cek status verifikasi admin
            if ($user->status_verifikasi === 'menunggu') {
                return back()->withInput($request->except('password'))
                    ->with('error', 'Akun kamu masih menunggu verifikasi admin.');
            }

            if ($user->status_verifikasi !== 'terverifikasi') {
                return back()->withInput($request->except('password'))
                    ->with('error', 'Akun belum diverifikasi admin.');
            }

            // Cek status akun
            if ($user->status_akun !== 'aktif') {
                return back()->withInput($request->except('password'))
                    ->with('error', 'Akun sedang dinonaktifkan.');
            }

            // Regenerasi session setelah semua pengecekan lolos
            $request->session()->regenerate();

            // SIMPAN SESSION LOGIN
            $request->session()->put('login', true);
            $request->session()->put('role', 'pencari_kerja');
            $request->session()->put('user_id', $user->id_pencari); // Sesuaikan dengan nama ID di database
            $request->session()->put('user_name', $user->nama);

            return redirect()->route('pencari.dashboard');
        }

        // =========================================================
        // LOGIN GAGAL
        // =========================================================
        return back()
            ->withInput($request->except('password'))
            ->with(
                'error',
                'Email, password, atau role tidak sesuai.'
            );
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login');
    }
}
