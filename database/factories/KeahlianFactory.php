<?php

namespace Database\Factories;

use App\Models\Keahlian;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Keahlian> */
class KeahlianFactory extends Factory
{
    protected $model = Keahlian::class;

    public function definition(): array
    {
        return ['nama_keahlian' => fake()->unique()->word(), 'deskripsi' => fake()->sentence()];
    }
}
