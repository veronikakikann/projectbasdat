<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * CRUD akun Admin. Akses dijaga middleware role:admin (routes/web.php).
 * Dashboard admin ada di DashboardController.
 */
class AdminController extends Controller
{
    public function index(): View
    {
        $admins = Admin::orderBy('id_admin')->get();

        return view('admin.index', compact('admins'));
    }

    public function create(): View
    {
        return view('admin.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admin,email|unique:pencari_kerja,email|unique:pemberi_kerja,email',
            'password' => 'required|string|min:6|confirmed',
            'tanggal_bergabung' => 'required|date',
        ]);

        $data['password'] = Hash::make($data['password']);
        Admin::create($data);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(Admin $admin): View
    {
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admin,email,'.$admin->id_admin.',id_admin|unique:pencari_kerja,email|unique:pemberi_kerja,email',
            'password' => 'nullable|string|min:6|confirmed',
            'tanggal_bergabung' => 'required|date',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $admin->update($data);

        // Kalau admin mengubah namanya sendiri, nama di sidebar ikut berubah.
        if ((int) session('user_id') === (int) $admin->id_admin) {
            session(['user_name' => $admin->nama]);
        }

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin): RedirectResponse
    {
        // Admin tidak boleh menghapus akunnya sendiri (sesi akan rusak).
        if ((int) session('user_id') === (int) $admin->id_admin) {
            return redirect()->route('admin.index')
                ->with('error', 'Kamu tidak bisa menghapus akun yang sedang kamu pakai.');
        }

        // Minimal harus tersisa satu admin.
        if (Admin::count() <= 1) {
            return redirect()->route('admin.index')
                ->with('error', 'Admin terakhir tidak boleh dihapus.');
        }

        // Admin yang pernah memverifikasi akun tidak dihapus, supaya riwayat
        // "siapa yang memverifikasi" (kolom id_admin) tetap utuh.
        $punyaRiwayat = DB::table('pencari_kerja')->where('id_admin', $admin->id_admin)->exists()
            || DB::table('pemberi_kerja')->where('id_admin', $admin->id_admin)->exists();

        if ($punyaRiwayat) {
            return redirect()->route('admin.index')
                ->with('error', 'Admin ini sudah tercatat memverifikasi akun, jadi tidak bisa dihapus.');
        }

        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}
