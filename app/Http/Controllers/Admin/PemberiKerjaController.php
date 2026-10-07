<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Pekerjaan;
use App\Models\PemberiKerja;
use App\Support\PrivateDocuments;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PemberiKerjaController extends Controller
{
    public function index(): View
    {
        $pemberiKerja = PemberiKerja::with('admin')->orderBy('nama')->get();

        return view('pemberi_kerja.index', compact('pemberiKerja'));
    }

    public function create(): View
    {
        return view('pemberi_kerja.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $data['password'] = Hash::make($data['password']);
        $data['id_admin'] = $data['status_verifikasi'] === 'menunggu' ? null : session('user_id');
        PemberiKerja::create($data);

        return redirect()->route('pemberi_kerja.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(PemberiKerja $pemberi_kerja): View
    {
        return view('pemberi_kerja.edit', ['pemberiKerja' => $pemberi_kerja]);
    }

    public function update(Request $request, PemberiKerja $pemberi_kerja): RedirectResponse
    {
        $data = $request->validate($this->rules($pemberi_kerja));
        unset($data['nik']);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['id_admin'] = $data['status_verifikasi'] === 'menunggu' ? null : session('user_id');
        $pemberi_kerja->update($data);

        return redirect()->route('pemberi_kerja.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(PemberiKerja $pemberi_kerja): RedirectResponse
    {
        $paths = [];
        $error = DB::transaction(function () use ($pemberi_kerja, &$paths): ?string {
            $account = PemberiKerja::lockForUpdate()->findOrFail($pemberi_kerja->getKey());
            if (Pekerjaan::where('id_pemberi', $account->getKey())->exists()) {
                return 'Akun mempunyai riwayat transaksi. Ubah status akun menjadi nonaktif.';
            }
            $paths = [$account->file_ktp, $account->foto_profil];
            Notifikasi::where('tipe_user', 'pemberi_kerja')->where('id_user', $account->getKey())->delete();
            $account->delete();

            return null;
        });
        if ($error) {
            return back()->with('error', $error);
        }
        foreach ($paths as $path) {
            PrivateDocuments::deleteUnused($path);
        }

        return redirect()->route('pemberi_kerja.index')->with('success', 'Akun berhasil dihapus.');
    }

    private function rules(?PemberiKerja $account = null): array
    {
        return [
            'nik' => $account ? ['sometimes', 'digits:16'] : ['required', 'digits:16', 'unique:pemberi_kerja,nik', 'unique:pencari_kerja,nik'],
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:500',
            'no_telpon' => 'required|string|max:15',
            'email' => [
                'required', 'email', 'max:100',
                Rule::unique('pemberi_kerja', 'email')->ignore($account?->getKey(), 'id_pemberi'),
                Rule::unique('pencari_kerja', 'email'), Rule::unique('admin', 'email'),
            ],
            'password' => ($account ? 'nullable' : 'required').'|string|min:6|confirmed',
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak',
            'status_akun' => 'required|in:aktif,nonaktif',
            'tanggal_daftar' => 'required|date',

        ];
    }
}
