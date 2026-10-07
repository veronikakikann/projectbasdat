<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuktiPenyelesaian;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\Pekerjaan;
use App\Models\Rating;
use Illuminate\Contracts\View\View;

class TransaksiController extends Controller
{
    public function index(string $jenis): View
    {
        $definitions = [
            'pekerjaan' => [Pekerjaan::class, ['pemberiKerja', 'keahlian'], ['nama_pekerjaan', 'lokasi', 'upah', 'status_pekerjaan']],
            'lamaran' => [Lamaran::class, ['pekerjaan', 'pencariKerja'], ['id_pekerjaan', 'id_pencari', 'status_lamaran', 'tanggal_submit']],
            'bukti' => [BuktiPenyelesaian::class, ['lamaran.pekerjaan', 'lamaran.pencariKerja'], ['id_lamaran', 'catatan', 'catatan_bayar', 'tanggal_upload']],
            'rating' => [Rating::class, ['lamaran.pekerjaan'], ['id_lamaran', 'arah_rating', 'skor', 'kategori_komentar', 'tanggal_rating']],
            'notifikasi' => [Notifikasi::class, [], ['tipe_user', 'id_user', 'isi_pesan', 'status_baca', 'tanggal']],
        ];
        abort_unless(isset($definitions[$jenis]), 404);
        [$model, $relations, $columns] = $definitions[$jenis];
        $records = $model::with($relations)->orderByDesc((new $model)->getKeyName())->paginate(25);

        return view('admin.transaksi', compact('jenis', 'columns', 'records'));
    }
}
