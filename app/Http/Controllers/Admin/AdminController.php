<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PemberiKerja;
use App\Models\PencariKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Menampilkan daftar admin.
     */
    public function index()
    {
        $admins = Admin::orderBy('nama')->get();

        return view('admin.index', compact('admins'));
    }

    /**
     * Menampilkan form tambah admin.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Menyimpan admin baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admin,email|unique:pemberi_kerja,email|unique:pencari_kerja,email',
            'password' => 'required|string|min:6|confirmed',
            'tanggal_bergabung' => 'required|date',
        ]);

        Admin::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'tanggal_bergabung' => $data['tanggal_bergabung'],
        ]);

        return redirect()
            ->route('admin.index')
            ->with(
                'success',
                'Admin berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit admin.
     */
    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    /**
     * Memperbarui data admin.
     */
    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:pemberi_kerja,email',
                'unique:pencari_kerja,email',
                'unique:admin,email,'.
                $admin->id_admin.
                ',id_admin',
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],

            'tanggal_bergabung' => 'required|date',
        ]);

        $admin->nama = $data['nama'];
        $admin->email = $data['email'];
        $admin->tanggal_bergabung = $data['tanggal_bergabung'];

        // Password hanya diubah jika diisi.
        if (! empty($data['password'])) {
            $admin->password = Hash::make(
                $data['password']
            );
        }

        $admin->save();

        return redirect()
            ->route('admin.index')
            ->with(
                'success',
                'Data admin berhasil diperbarui.'
            );
    }

    /**
     * Menghapus admin.
     */
    public function destroy(Admin $admin)
    {
        $error = DB::transaction(function () use ($admin): ?string {
            // Lock the complete admin set to serialize simultaneous deletions.
            $admins = Admin::orderBy('id_admin')->lockForUpdate()->get();
            $target = $admins->firstWhere('id_admin', $admin->id_admin);
            abort_unless($target, 404);
            if ($admins->count() <= 1) {
                return 'Admin terakhir tidak boleh dihapus.';
            }
            if ((int) session('user_id') === (int) $target->id_admin) {
                return 'Admin yang sedang login tidak dapat menghapus dirinya sendiri.';
            }
            if (PemberiKerja::where('id_admin', $target->id_admin)->exists()
                || PencariKerja::where('id_admin', $target->id_admin)->exists()) {
                return 'Admin masih memiliki riwayat verifikasi akun dan tidak dapat dihapus.';
            }
            $target->delete();

            return null;
        });

        return $error ? back()->with('error', $error)
            : redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}
