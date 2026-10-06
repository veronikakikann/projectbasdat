<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Pemakaian di route: ->middleware('role:admin') atau 'role:pemberi_kerja,pencari_kerja'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!session('login')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!in_array(session('role'), $roles, true)) {
            abort(403, 'Kamu tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
