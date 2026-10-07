<?php

namespace App\Http\Controllers;

use App\Models\BuktiPenyelesaian;
use App\Models\KeahlianPencariKerja;
use App\Models\PemberiKerja;
use App\Models\PencariKerja;
use App\Support\PrivateDocuments;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function bukti(BuktiPenyelesaian $bukti, string $jenis): StreamedResponse
    {
        $bukti->loadMissing('lamaran.pekerjaan');
        $lamaran = $bukti->lamaran;
        $role = session('role');
        $id = (int) session('user_id');

        abort_unless(
            $role === 'admin'
            || ($role === 'pencari_kerja' && (int) $lamaran->id_pencari === $id)
            || ($role === 'pemberi_kerja' && (int) $lamaran->pekerjaan->id_pemberi === $id),
            403
        );
        abort_unless(in_array($jenis, ['kerja', 'bayar'], true), 404);

        return PrivateDocuments::response($jenis === 'kerja'
            ? $bukti->foto_bukti_kerja : $bukti->foto_bukti_bayar);
    }

    public function keahlian(KeahlianPencariKerja $pengajuan): StreamedResponse
    {
        abort_unless(session('role') === 'admin'
            || (session('role') === 'pencari_kerja'
                && (int) $pengajuan->id_pencari === (int) session('user_id')), 403);

        return PrivateDocuments::response($pengajuan->file_surat_rekomendasi);
    }

    public function ktp(string $role, int $id): StreamedResponse
    {
        abort_unless(in_array($role, ['pemberi_kerja', 'pencari_kerja'], true), 404);
        $model = $role === 'pemberi_kerja' ? PemberiKerja::class : PencariKerja::class;

        return PrivateDocuments::response($model::findOrFail($id)->file_ktp);
    }
}
