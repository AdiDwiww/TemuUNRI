<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test untuk Dashboard Pengguna (Orang 2).
 * Memastikan dashboard hanya menampilkan laporan milik pengguna yang login.
 */
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman dashboard dapat diakses oleh pengguna yang sudah login.
     */
    public function test_dashboard_dapat_diakses_pengguna_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Pengguna yang belum login tidak bisa mengakses dashboard.
     */
    public function test_dashboard_ditolak_untuk_pengguna_belum_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Dashboard menampilkan nama pengguna yang sedang login.
     */
    public function test_dashboard_menampilkan_nama_pengguna(): void
    {
        $user = User::factory()->create(['name' => 'Mahasiswa UNRI']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Mahasiswa UNRI');
    }

    /**
     * Dashboard hanya menampilkan laporan milik pengguna yang sedang login,
     * bukan laporan pengguna lain.
     */
    public function test_dashboard_hanya_menampilkan_laporan_milik_sendiri(): void
    {
        $user     = User::factory()->create();
        $pengguna = User::factory()->create();

        // Laporan milik $user
        $laporanSaya = Laporan::factory()->create([
            'user_id' => $user->id,
            'judul'   => 'Dompet Hilang di Kantin',
        ]);

        // Laporan milik pengguna lain
        $laporanOrang = Laporan::factory()->create([
            'user_id' => $pengguna->id,
            'judul'   => 'Tas Ditemukan di Perpus',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dompet Hilang di Kantin');
        $response->assertDontSee('Tas Ditemukan di Perpus');
    }

    /**
     * Dashboard menampilkan empty state saat pengguna belum punya laporan.
     */
    public function test_dashboard_menampilkan_empty_state_jika_belum_ada_laporan(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Belum ada laporan');
    }
}
