<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClaimTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_bisa_mengajukan_klaim_pada_penemuan_proses_milik_orang_lain(): void
    {
        $pelapor = User::factory()->create();
        $pengaju = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
        ]);

        $response = $this->actingAs($pengaju)->post(route('claims.store', $laporan), [
            'description' => 'Ciri khusus: ada stiker UNRI di bagian dalam tas.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('claims', [
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju->id,
            'status' => 'pending',
        ]);
    }

    public function test_pemilik_laporan_tidak_bisa_mengklaim_miliknya_sendiri(): void
    {
        $pelapor = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
        ]);

        $response = $this->actingAs($pelapor)->post(route('claims.store', $laporan), [
            'description' => 'Ini barang saya sendiri sebenarnya.',
        ]);

        $response->assertRedirect(route('reports.show', $laporan));
        $this->assertDatabaseCount('claims', 0);
    }

    public function test_klaim_ditolak_jika_laporan_bukan_penemuan(): void
    {
        $pelapor = User::factory()->create();
        $pengaju = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'hilang',
        ]);

        $response = $this->actingAs($pengaju)->post(route('claims.store', $laporan), [
            'description' => 'Saya merasa ini milik saya.',
        ]);

        $response->assertRedirect(route('reports.show', $laporan));
        $this->assertDatabaseCount('claims', 0);
    }

    public function test_pengguna_lain_tidak_bisa_melihat_klaim_orang_lain(): void
    {
        $pengaju = User::factory()->create();
        $orangLain = User::factory()->create();
        $claim = Claim::factory()->create([
            'user_id' => $pengaju->id,
        ]);

        $response = $this->actingAs($orangLain)->get(route('claims.show', $claim));

        $response->assertStatus(403);
    }
}
