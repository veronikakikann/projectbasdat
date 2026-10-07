<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | 1. CEK LOGIN
        |--------------------------------------------------------------------------
        */

        if (! session('login')) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. CEK ROLE
        |--------------------------------------------------------------------------
        */

        $role = session('role');

        if (! in_array($role, $roles, true)) {
            abort(
                403,
                'Kamu tidak memiliki akses ke halaman ini.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. ADMIN
        |--------------------------------------------------------------------------
        */

        if ($role === 'admin') {

            $admin = DB::table('admin')
                ->where(
                    'id_admin',
                    session('user_id')
                )
                ->first();

            if (! $admin) {

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Akun admin tidak ditemukan.'
                    );
            }

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. PEMBERI KERJA
        |--------------------------------------------------------------------------
        */

        if ($role === 'pemberi_kerja') {

            $pemberi = DB::table('pemberi_kerja')
                ->where(
                    'id_pemberi',
                    session('user_id')
                )
                ->first();

            if (! $pemberi) {

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Akun pemberi kerja tidak ditemukan.'
                    );
            }

            // Belum diverifikasi
            if (
                $pemberi->status_verifikasi
                !== 'terverifikasi'
            ) {

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Akun pemberi kerja belum diverifikasi admin.'
                    );
            }

            // Akun dinonaktifkan
            if (
                $pemberi->status_akun
                !== 'aktif'
            ) {

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Akun pemberi kerja sedang dinonaktifkan.'
                    );
            }

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 5. PENCARI KERJA
        |--------------------------------------------------------------------------
        */

        if ($role === 'pencari_kerja') {

            $pencari = DB::table('pencari_kerja')
                ->where(
                    'id_pencari',
                    session('user_id')
                )
                ->first();

            if (! $pencari) {

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Akun pencari kerja tidak ditemukan.'
                    );
            }

            // Belum diverifikasi
            if (
                $pencari->status_verifikasi
                !== 'terverifikasi'
            ) {

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Akun pencari kerja belum diverifikasi admin.'
                    );
            }

            // Akun dinonaktifkan
            if (
                $pencari->status_akun
                !== 'aktif'
            ) {

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Akun pencari kerja sedang dinonaktifkan.'
                    );
            }

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 6. ROLE TIDAK DIKENALI
        |--------------------------------------------------------------------------
        */

        abort(
            403,
            'Role tidak valid.'
        );
    }
}
