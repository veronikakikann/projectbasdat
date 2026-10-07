<?php

namespace Database\Factories;

use App\Models\Lamaran;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Rating> */
class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return ['id_lamaran' => Lamaran::factory(), 'pemberi_rating' => fn (array $attributes) => Lamaran::findOrFail($attributes['id_lamaran'])->pekerjaan->id_pemberi, 'penerima_rating' => fn (array $attributes) => Lamaran::findOrFail($attributes['id_lamaran'])->id_pencari, 'arah_rating' => 'pemberi_ke_pekerja', 'skor' => 4, 'kategori_komentar' => 'Tepat waktu', 'tanggal_rating' => now()];
    }
}
