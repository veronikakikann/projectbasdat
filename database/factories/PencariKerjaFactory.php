<?php

namespace Database\Factories;

use App\Models\PencariKerja;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/** @extends Factory<PencariKerja> */
class PencariKerjaFactory extends Factory
{
    protected $model = PencariKerja::class;

    public function definition(): array
    {
        return ['nik' => fake()->unique()->numerify('################'), 'nama' => fake()->name(), 'alamat' => fake()->address(), 'no_telpon' => '08123456789', 'email' => fake()->unique()->safeEmail(), 'password' => Hash::make('password123'), 'status_verifikasi' => 'terverifikasi', 'status_akun' => 'aktif', 'tanggal_daftar' => now()->toDateString()];
    }
}
