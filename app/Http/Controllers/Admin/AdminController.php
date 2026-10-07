<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('nama')->get();

        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admin,email',
            'password' => 'required|string|min:6|confirmed',
            'tanggal_bergabung' => 'required|date',
        ]);

        Admin::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'tanggal_bergabung' => $data['tanggal_bergabung'],
        ]);

        return redirect()
            ->route('admin.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admin,email,' .
                $admin->id_admin . ',id_admin',
            'password' => 'nullable|string|min:6|confirmed',
            'tanggal_bergabung' => 'required|date',
        ]);

        $admin->nama = $data['nama'];
        $admin->email = $data['email'];
        $admin->tanggal_bergabung = $data['tanggal_bergabung'];

        if (!empty($data['password'])) {
            $admin->password = bcrypt($data['password']);
        }

        $admin->save();

        return redirect()
            ->route('admin.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        // Jangan sampai admin terakhir terhapus
        if (Admin::count() <= 1) {
            return back()->with(
                'error',
                'Admin terakhir tidak boleh dihapus.'
            );
        }

        $admin->delete();

        return redirect()
            ->route('admin.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}