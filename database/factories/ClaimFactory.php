<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Claim>
 */
class ClaimFactory extends Factory
{
    protected $model = Claim::class;

    public function definition(): array
    {
        return [
            'laporan_id' => Laporan::factory()->proses()->state(['tipe' => 'penemuan']),
            'user_id' => User::factory(),
            'description' => $this->faker->paragraph(),
            'status' => 'pending',
        ];
    }
}
