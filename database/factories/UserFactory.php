<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'user',
            'nim' => '2410' . fake()->unique()->numerify('######'),
            'fakultas' => fake()->randomElement([
                'Fakultas Teknik',
                'Fakultas Ekonomi dan Bisnis',
                'Fakultas Hukum',
                'Fakultas Keguruan dan Ilmu Pendidikan',
                'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            ]),
            'program_studi' => fake()->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Teknik Elektro',
                'Manajemen',
                'Akuntansi',
            ]),
            'no_hp' => fake()->optional()->numerify('08##########'),
            'foto_profil' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * State: pengguna dengan role admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * State: set custom NIM.
     */
    public function nim(string $nim): static
    {
        return $this->state(fn (array $attributes) => [
            'nim' => $nim,
        ]);
    }
}
