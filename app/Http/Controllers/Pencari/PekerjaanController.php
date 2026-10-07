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

        if (! $pencari) {
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
            'keahlian',
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
            // Haversine in PHP also works with SQLite, which has no acos/radians.
            // A latitude bounding box keeps the candidate query small.
            $latitude = (float) $pencari->latitude;
            $longitude = (float) $pencari->longitude;
            $latitudeMargin = rad2deg(10 / 6371);
            $pekerjaan = $query->whereNotNull('longitude')
                ->whereBetween('latitude', [$latitude - $latitudeMargin, $latitude + $latitudeMargin])
                ->get()->each(function (Pekerjaan $job) use ($latitude, $longitude): void {
                    $deltaLat = deg2rad((float) $job->latitude - $latitude);
                    $deltaLon = deg2rad((float) $job->longitude - $longitude);
                    $a = sin($deltaLat / 2) ** 2
                        + cos(deg2rad($latitude)) * cos(deg2rad((float) $job->latitude))
                        * sin($deltaLon / 2) ** 2;
                    $job->jarak_km = 6371 * 2 * asin(sqrt(min(1, max(0, $a))));
                })->filter(fn (Pekerjaan $job): bool => $job->jarak_km <= 10)
                ->sortBy('jarak_km')->values();
        } else {
            $pekerjaan = $query->orderByDesc('tanggal_posting')->get();
        }

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
            'keahlian',
        ]);

        return view(
            'pencari_kerja.detail-pekerjaan',
            compact('pekerjaan')
        );
    }
}
