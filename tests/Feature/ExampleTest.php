<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman utama (/) mengarahkan pengguna yang belum login ke halaman login.
     * Ini sesuai dengan konfigurasi proyek: route '/' membutuhkan autentikasi.
     */
    public function test_halaman_utama_mengarahkan_ke_login_jika_belum_login(): void
    {
        $response = $this->get('/');

        // Karena route '/' dilindungi auth middleware, harus redirect ke login
        $response->assertRedirect('/login');
    }

    /**
     * Pengguna yang sudah login dapat mengakses halaman utama.
     */
    public function test_pengguna_login_bisa_akses_halaman_utama(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }
}
