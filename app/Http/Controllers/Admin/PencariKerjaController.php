<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\PencariKerja;
use App\Support\PrivateDocuments;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PencariKerjaController extends Controller
{
    public function index(): View
    {
        $pencariKerja = PencariKerja::with('admin')->orderBy('nama')->get();

        return view('pencari_kerja.index', compact('pencariKerja'));
    }

    public function create(): View
    {
        return view('pencari_kerja.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $data['password'] = Hash::make($data['password']);
        $data['id_admin'] = $data['status_verifikasi'] === 'menunggu' ? null : session('user_id');
        PencariKerja::create($data);

        return redirect()->route('pencari_kerja.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(PencariKerja $pencari_kerja): View
    {
        return view('pencari_kerja.edit', ['pencariKerja' => $pencari_kerja]);
    }

    public function update(Request $request, PencariKerja $pencari_kerja): RedirectResponse
    {
        $data = $request->validate($this->rules($pencari_kerja));
        unset($data['nik']);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['id_admin'] = $data['status_verifikasi'] === 'menunggu' ? null : session('user_id');
        $pencari_kerja->update($data);

        return redirect()->route('pencari_kerja.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(PencariKerja $pencari_kerja): RedirectResponse
    {
        $paths = [];
        $error = DB::transaction(function () use ($pencari_kerja, &$paths): ?string {
            $account = PencariKerja::lockForUpdate()->findOrFail($pencari_kerja->getKey());
            if (Lamaran::where('id_pencari', $account->getKey())->exists()) {
                return 'Akun mempunyai riwayat transaksi. Ubah status akun menjadi nonaktif.';
            }
            $paths = [$account->file_ktp, $account->foto_profil, ...$account->pengajuanKeahlian()->pluck('file_surat_rekomendasi')->all()];
            Notifikasi::where('tipe_user', 'pencari_kerja')->where('id_user', $account->getKey())->delete();
            $account->delete();

            return null;
        });
        if ($error) {
            return back()->with('error', $error);
        }
        foreach ($paths as $path) {
            PrivateDocuments::deleteUnused($path);
        }

        return redirect()->route('pencari_kerja.index')->with('success', 'Akun berhasil dihapus.');
    }

    private function rules(?PencariKerja $account = null): array
    {
        return [
            'nik' => $account ? ['sometimes', 'digits:16'] : ['required', 'digits:16', 'unique:pencari_kerja,nik', 'unique:pemberi_kerja,nik'],
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:500',
            'no_telpon' => 'required|string|max:15',
            'email' => [
                'required', 'email', 'max:100',
                Rule::unique('pencari_kerja', 'email')->ignore($account?->getKey(), 'id_pencari'),
                Rule::unique('pemberi_kerja', 'email'), Rule::unique('admin', 'email'),
            ],
            'password' => ($account ? 'nullable' : 'required').'|string|min:6|confirmed',
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak',
            'status_akun' => 'required|in:aktif,nonaktif',
            'tanggal_daftar' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];
    }
}
