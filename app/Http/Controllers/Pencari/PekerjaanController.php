<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\Pekerjaan;
use App\Models\PencariKerja;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    public function index(Request $request)
    {
        $idPencari = session('user_id');

        $pencari = PencariKerja::find($idPencari);

        if (!$pencari) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Data Pencari Kerja tidak ditemukan.'
                );
        }

        $keahlianIds = $pencari
            ->keahlian()
            ->wherePivot(
                'status_verifikasi_keahlian',
                'terverifikasi'
            )
            ->pluck('keahlian.id_keahlian');

        $query = Pekerjaan::with([
            'pemberiKerja',
            'keahlian'
        ])
            ->where(
                'status_pekerjaan',
                'tersedia'
            )
            ->whereIn(
                'id_keahlian',
                $keahlianIds
            );

        if (
            $pencari->latitude !== null
            && $pencari->longitude !== null
        ) {
            $query
                ->select('*')
                ->selectRaw(
                    '(6371 * acos(
                        cos(radians(?))
                        * cos(radians(latitude))
                        * cos(
                            radians(longitude)
                            - radians(?)
                        )
                        + sin(radians(?))
                        * sin(radians(latitude))
                    )) AS jarak_km',
                    [
                        $pencari->latitude,
                        $pencari->longitude,
                        $pencari->latitude
                    ]
                )
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->having('jarak_km', '<=', 10)
                ->orderBy('jarak_km');
        } else {
            $query->orderByDesc(
                'tanggal_posting'
            );
        }

        $pekerjaan = $query->get();

        return view(
            'pencari_kerja.cari-pekerjaan',
            compact('pekerjaan')
        );
    }

    public function show(Pekerjaan $pekerjaan)
    {
        abort_unless(
            $pekerjaan->status_pekerjaan === 'tersedia',
            404
        );

        $pekerjaan->load([
            'pemberiKerja',
            'keahlian'
        ]);

        return view(
            'pencari_kerja.detail-pekerjaan',
            compact('pekerjaan')
        );
    }
}