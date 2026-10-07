<?php

namespace Database\Factories;

use App\Models\Lamaran;
use App\Models\Pekerjaan;
use App\Models\PencariKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Lamaran> */
class LamaranFactory extends Factory
{
    protected $model = Lamaran::class;

    public function definition(): array
    {
        return ['id_pekerjaan' => Pekerjaan::factory(), 'id_pencari' => PencariKerja::factory(), 'status_lamaran' => 'menunggu', 'tanggal_submit' => now()];
    }
}
