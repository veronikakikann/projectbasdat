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
            'password' => 'required',
        ]);

        $role = $request->role;

        if ($role === 'admin') {

            $user = DB::table('admin')
                ->where(
                    'email',
                    $request->email
                )
                ->first();

            if (
                $user
                && Hash::check(
                    $request->password,
                    $user->password
                )
            ) {
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

        if ($role === 'pemberi_kerja') {

            $user = DB::table('pemberi_kerja')
                ->where(
                    'email',
                    $request->email
                )
                ->first();

            if (
                $user
                && Hash::check(
                    $request->password,
                    $user->password
                )
            ) {
                if (
                    $user->status_verifikasi
                    !== 'terverifikasi'
                ) {
                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Akun belum diverifikasi admin.'
                        );
                }

                if (
                    $user->status_akun
                    !== 'aktif'
                ) {
                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Akun sedang dinonaktifkan.'
                        );
                }

                session([
                    'login' => true,
                    'role' => 'pemberi_kerja',
                    'user_id' => $user->id_pemberi,
                    'user_name' => $user->nama,
                ]);

                return redirect()
                    ->route('pemberi.dashboard');
            }
        }

        if ($role === 'pencari_kerja') {

            $user = DB::table('pencari_kerja')
                ->where(
                    'email',
                    $request->email
                )
                ->first();

            if (
                $user
                && Hash::check(
                    $request->password,
                    $user->password
                )
            ) {
                if (
                    $user->status_verifikasi
                    !== 'terverifikasi'
                ) {
                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Akun belum diverifikasi admin.'
                        );
                }

                if (
                    $user->status_akun
                    !== 'aktif'
                ) {
                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Akun sedang dinonaktifkan.'
                        );
                }

                session([
                    'login' => true,
                    'role' => 'pencari_kerja',
                    'user_id' => $user->id_pencari,
                    'user_name' => $user->nama,
                ]);

                return redirect()
                    ->route('pencari.dashboard');
            }
        }

        return back()
            ->withInput()
            ->with(
                'error',
                'Email, password, atau role tidak sesuai.'
            );
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()
            ->route('login');
    }
}