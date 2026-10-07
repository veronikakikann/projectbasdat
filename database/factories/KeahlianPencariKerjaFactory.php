<?php

namespace Database\Factories;

use App\Models\KeahlianPencariKerja;
use App\Models\PencariKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<KeahlianPencariKerja> */
class KeahlianPencariKerjaFactory extends Factory
{
    protected $model = KeahlianPencariKerja::class;

    public function definition(): array
    {
        return ['id_pencari' => PencariKerja::factory(), 'id_keahlian' => null, 'judul_keahlian' => fake()->sentence(3), 'deskripsi_keahlian' => fake()->sentence(), 'status_verifikasi_keahlian' => 'menunggu', 'tanggal_upload' => now()];
    }
}
