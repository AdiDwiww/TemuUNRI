<?php

namespace Database\Factories;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Laporan>
 */
class LaporanFactory extends Factory
{
    protected $model = Laporan::class;

    /**
     * Definisi state default laporan.
     */
    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'judul'            => $this->faker->sentence(4),
            'tipe'             => $this->faker->randomElement(['hilang', 'penemuan']),
            'kategori'         => $this->faker->randomElement(['Dompet', 'Tas', 'HP', 'Kunci', 'KTP', 'Lainnya']),
            'deskripsi'        => $this->faker->paragraph(),
            'lokasi'           => $this->faker->randomElement([
                'Gedung A UNRI', 'Kantin Pusat', 'Perpustakaan', 'Lapangan Parkir', 'Gedung Rektorat'
            ]),
            'tanggal_kejadian' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'foto'             => null,
            'status'           => 'pending',
        ];
    }

    /**
     * State: laporan berstatus 'proses'.
     */
    public function proses(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'proses',
        ]);
    }

    /**
     * State: laporan berstatus 'selesai'.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
        ]);
    }

    /**
     * State: laporan berstatus 'Ditolak'.
     */
    public function ditolak(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Ditolak',
        ]);
    }
}
