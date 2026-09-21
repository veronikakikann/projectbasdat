<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $role = $request->role;
        $email = $request->email;
        $password = $request->password;

        // =========================
        // LOGIN ADMIN
        // =========================
        if ($role == 'admin') {

            $user = DB::table('admin')
                ->where('email', $email)
                ->first();

            if ($user && Hash::check($password, $user->password)) {

                session([
                    'login' => true,
                    'role' => 'admin',
                    'user_id' => $user->id_admin,
                    'user_name' => $user->nama,
                ]);

                return redirect()->route('admin.dashboard');
            }
        }

        // =========================
        // LOGIN PEMBERI KERJA
        // =========================
        if ($role == 'pemberi_kerja') {

            $user = DB::table('pemberi_kerja')
                ->where('email', $email)
                ->first();

            if ($user && Hash::check($password, $user->password)) {

                session([
                    'login' => true,
                    'role' => 'pemberi_kerja',
                    'user_id' => $user->id_pemberi,
                    'user_name' => $user->nama,
                ]);

                return redirect()->route('pemberi.dashboard');
            }
        }

        // =========================
        // LOGIN PENCARI KERJA
        // =========================
        if ($role == 'pencari_kerja') {

            $user = DB::table('pencari_kerja')
                ->where('email', $email)
                ->first();

            if ($user && Hash::check($password, $user->password)) {

                session([
                    'login' => true,
                    'role' => 'pencari_kerja',
                    'user_id' => $user->id_pencari,
                    'user_name' => $user->nama,
                ]);

                return redirect()->route('pencari.dashboard');
            }
        }

        return back()
            ->withInput()
            ->with('error', 'Email, password, atau role tidak sesuai.');
    }

    // Logout
    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login');
    }
}