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
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $status = in_array($status, ['menunggu', 'terverifikasi', 'ditolak'], true) ? $status : null;

        $accounts = PemberiKerja::with('admin')
            ->when($status, fn ($q) => $q->where('status_verifikasi', $status))
            ->orderByRaw("CASE status_verifikasi WHEN 'menunggu' THEN 0 ELSE 1 END")
            ->orderBy('nama')
            ->get();

        return view('admin.accounts.index', [
            'type' => 'pemberi_kerja',
            'accounts' => $accounts,
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.accounts.form', ['type' => 'pemberi_kerja']);
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
        return view('admin.accounts.form', ['type' => 'pemberi_kerja', 'account' => $pemberi_kerja]);
    }

    public function update(Request $request, PemberiKerja $pemberi_kerja): RedirectResponse
    {
        $data = $request->validate($this->rules($pemberi_kerja));
        $statusLama = $pemberi_kerja->status_verifikasi;
        unset($data['nik']);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['id_admin'] = $data['status_verifikasi'] === 'menunggu' ? null : session('user_id');
        $pemberi_kerja->update($data);
        $this->kirimNotifikasiVerifikasi($pemberi_kerja, $statusLama, $data['status_verifikasi']);

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

    // Beri tahu pengguna kalau status verifikasinya berubah.
    private function kirimNotifikasiVerifikasi(PemberiKerja $account, string $statusLama, string $statusBaru): void
    {
        if ($statusLama === $statusBaru) {
            return;
        }

        $pesan = match ($statusBaru) {
            'terverifikasi' => 'Akun kamu sudah diverifikasi oleh admin. Selamat bergabung di Teman Kerja!',
            'ditolak' => 'Verifikasi akun kamu ditolak oleh admin. Pastikan data dan foto KTP kamu jelas dan sesuai.',
            default => null,
        };

        if ($pesan) {
            Notifikasi::kirim((int) $account->getKey(), 'pemberi_kerja', $pesan);
        }
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
