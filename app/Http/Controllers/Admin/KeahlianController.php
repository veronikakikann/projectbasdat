<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keahlian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Master data kategori keahlian (Tukang AC, Tukang Bangunan, ART, dll).
 * Kategori ini yang dipilih admin saat memverifikasi keahlian pencari kerja.
 */
class KeahlianController extends Controller
{
    public function index(): View
    {
        $keahlian = Keahlian::withCount(['pengajuanPencari', 'pekerjaan'])
            ->orderBy('nama_keahlian')
            ->get();

        return view('admin.keahlian.index', compact('keahlian'));
    }

    public function create(): View
    {
        return view('admin.keahlian.form');
    }

    public function store(Request $request): RedirectResponse
    {
        Keahlian::create($this->validated($request));

        return redirect()->route('keahlian.index')->with('success', 'Keahlian berhasil ditambahkan.');
    }

    public function edit(Keahlian $keahlian): View
    {
        return view('admin.keahlian.form', compact('keahlian'));
    }

    public function update(Request $request, Keahlian $keahlian): RedirectResponse
    {
        $keahlian->update($this->validated($request, $keahlian));

        return redirect()->route('keahlian.index')->with('success', 'Keahlian berhasil diperbarui.');
    }

    public function destroy(Keahlian $keahlian): RedirectResponse
    {
        // Jangan hapus kalau masih dipakai, supaya kategori pencari/lowongan tidak hilang diam-diam.
        if ($keahlian->pengajuanPencari()->exists() || $keahlian->pekerjaan()->exists()) {
            return redirect()->route('keahlian.index')
                ->with('error', 'Keahlian tidak bisa dihapus karena masih dipakai oleh pengajuan pencari kerja atau lowongan.');
        }

        $keahlian->delete();

        return redirect()->route('keahlian.index')->with('success', 'Keahlian berhasil dihapus.');
    }

    private function validated(Request $request, ?Keahlian $keahlian = null): array
    {
        return $request->validate([
            'nama_keahlian' => [
                'required', 'string', 'max:100',
                Rule::unique('keahlian', 'nama_keahlian')->ignore($keahlian?->getKey(), 'id_keahlian'),
            ],
            'deskripsi' => 'nullable|string',
        ]);
    }
}
