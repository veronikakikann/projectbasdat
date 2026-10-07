<?php

namespace Database\Factories;

use App\Models\BuktiPenyelesaian;
use App\Models\Lamaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<BuktiPenyelesaian> */
class BuktiPenyelesaianFactory extends Factory
{
    protected $model = BuktiPenyelesaian::class;

    public function definition(): array
    {
        return ['id_lamaran' => Lamaran::factory(), 'foto_bukti_kerja' => null, 'foto_bukti_bayar' => null, 'tanggal_upload' => now()];
    }
}
