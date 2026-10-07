<?php

namespace Database\Factories;

use App\Models\Keahlian;
use App\Models\Pekerjaan;
use App\Models\PemberiKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Pekerjaan> */
class PekerjaanFactory extends Factory
{
    protected $model = Pekerjaan::class;

    public function definition(): array
    {
        return ['id_pemberi' => PemberiKerja::factory(), 'id_keahlian' => Keahlian::factory(), 'nama_pekerjaan' => fake()->sentence(3), 'deskripsi' => fake()->sentence(), 'upah' => 150000, 'lokasi' => fake()->address(), 'tanggal_pengerjaan' => now()->addDays(3)->toDateString(), 'jumlah_pekerja' => 2, 'status_pekerjaan' => 'tersedia'];
    }
}
