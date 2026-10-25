<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk membuat akun admin khusus pengujian lokal.
 *
 * Cara menjalankan:
 *   php artisan db:seed --class=AdminSeeder
 *
 * PENTING: Jangan gunakan seeder ini di lingkungan produksi.
 * Ganti password setelah pertama kali login.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat atau perbarui akun admin pengujian
        User::updateOrCreate(
            ['email' => 'admin@temuunri.test'],
            [
                'name'     => 'Admin TemuUNRI',
                'email'    => 'admin@temuunri.test',
                'password' => Hash::make('AdminTemu2024!'),
                'role'     => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✓ Akun admin berhasil dibuat.');
        $this->command->info('  Email   : admin@temuunri.test');
        $this->command->info('  Password: AdminTemu2024!');
        $this->command->warn('  PENTING : Ganti password ini setelah pertama kali login!');
    }
}
