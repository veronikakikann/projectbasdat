<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Menampilkan semua data admin
    public function index()
    {
        $admins = Admin::all();
        return view('admin.index', compact('admins'));
    }

    // Menampilkan form tambah admin
    public function create()
    {
        return view('admin.create');
    }

    // Menyimpan data admin baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admin,email',
            'password' => 'required|string|min:6',
            'tanggal_bergabung' => 'required|date',
        ]);

        Admin::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tanggal_bergabung' => $request->tanggal_bergabung,
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    // Menampilkan form edit admin
    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    // Mengupdate data admin
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admin,email,' . $admin->id_admin . ',id_admin',
            'tanggal_bergabung' => 'required|date',
        ]);

        $admin->nama = $request->nama;
        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }
        $admin->tanggal_bergabung = $request->tanggal_bergabung;
        $admin->save();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui.');
    }

    // Menghapus data admin
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}