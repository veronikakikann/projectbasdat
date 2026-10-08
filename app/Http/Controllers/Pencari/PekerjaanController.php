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

        /*
        |--------------------------------------------------------------------------
        | Ambil hanya keahlian Pencari yang sudah terverifikasi
        |--------------------------------------------------------------------------
        */

        $keahlianTerverifikasi = $pencari
            ->keahlian()
            ->wherePivot(
                'status_verifikasi_keahlian',
                'terverifikasi'
            )
            ->orderBy('nama_keahlian')
            ->get();

        $keahlianIds = $keahlianTerverifikasi
            ->pluck('id_keahlian');

        /*
        |--------------------------------------------------------------------------
        | Query pekerjaan
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Filter kategori keahlian
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id_keahlian')) {
            $query->where(
                'id_keahlian',
                $request->input('id_keahlian')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter lokasi berdasarkan teks alamat
        |--------------------------------------------------------------------------
        */

        if ($request->filled('lokasi')) {
            $lokasi = trim(
                $request->input('lokasi')
            );

            $query->where(
                'lokasi',
                'like',
                '%' . $lokasi . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Matching lokasi berdasarkan koordinat
        |--------------------------------------------------------------------------
        */

        if (
            $pencari->latitude !== null
            && $pencari->longitude !== null
        ) {
            $latitude = (float) $pencari->latitude;
            $longitude = (float) $pencari->longitude;

            $latitudeMargin = rad2deg(10 / 6371);

            $pekerjaan = $query
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereBetween(
                    'latitude',
                    [
                        $latitude - $latitudeMargin,
                        $latitude + $latitudeMargin,
                    ]
                )
                ->get()
                ->each(
                    function (
                        Pekerjaan $job
                    ) use (
                        $latitude,
                        $longitude
                    ): void {
                        $deltaLat = deg2rad(
                            (float) $job->latitude
                            - $latitude
                        );

                        $deltaLon = deg2rad(
                            (float) $job->longitude
                            - $longitude
                        );

                        $a =
                            sin($deltaLat / 2) ** 2
                            +
                            cos(deg2rad($latitude))
                            *
                            cos(
                                deg2rad(
                                    (float) $job->latitude
                                )
                            )
                            *
                            sin($deltaLon / 2) ** 2;

                        $a = min(
                            1,
                            max(0, $a)
                        );

                        $job->jarak_km =
                            6371
                            *
                            2
                            *
                            asin(
                                sqrt($a)
                            );
                    }
                )
                ->filter(
                    fn (
                        Pekerjaan $job
                    ): bool =>
                        $job->jarak_km <= 10
                )
                ->sortBy('jarak_km')
                ->values();
        } else {
            $pekerjaan = $query
                ->orderByDesc('tanggal_posting')
                ->get();
        }

        return view(
            'pencari.pekerjaan.index',
            compact(
                'pekerjaan',
                'keahlianTerverifikasi'
            )
        );
    }

    public function show(Pekerjaan $pekerjaan)
    {
        /*
         * Ambil lamaran milik Pencari yang sedang login
         * untuk lowongan ini.
         */
        $lamaranSaya = $pekerjaan
            ->lamaran()
            ->where(
                'id_pencari',
                session('user_id')
            )
            ->first();

        $sudahMelamar = $lamaranSaya !== null;

        /*
         * Pencari boleh membuka:
         * 1. lowongan yang masih tersedia, atau
         * 2. lowongan yang memang sudah pernah dia lamar.
         *
         * Ini membuat detail tetap bisa dibuka setelah
         * lowongan berubah menjadi penuh/sedang dikerjakan.
         */
        abort_unless(
            $pekerjaan->status_pekerjaan === 'tersedia'
            || $sudahMelamar,
            404
        );

        $pekerjaan->load([
            'pemberiKerja',
            'keahlian',
        ]);

        $jumlahDiterima = $pekerjaan
            ->jumlahDiterima();

        return view(
            'pencari.pekerjaan.show',
            compact(
                'pekerjaan',
                'sudahMelamar',
                'lamaranSaya',
                'jumlahDiterima'
            )
        );
    }
}