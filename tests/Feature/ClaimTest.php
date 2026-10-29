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
        $response->assertSessionHas('success', 'Klaim berhasil diajukan dan menunggu keputusan pelapor.');
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

    public function test_klaim_ditolak_jika_laporan_tidak_berstatus_proses(): void
    {
        $pelapor = User::factory()->create();
        $pengaju = User::factory()->create();
        $laporan = Laporan::factory()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pengaju)->post(route('claims.store', $laporan), [
            'description' => 'Saya merasa ini milik saya.',
        ]);

        $response->assertRedirect(route('reports.show', $laporan));
        $this->assertDatabaseCount('claims', 0);
    }

    public function test_pengguna_tidak_dapat_mengajukan_klaim_baru_setelah_laporan_selesai(): void
    {
        $pelapor = User::factory()->create();
        $pengaju = User::factory()->create();
        $laporan = Laporan::factory()->selesai()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
        ]);

        $response = $this->actingAs($pengaju)->post(route('claims.store', $laporan), [
            'description' => 'Mencoba klaim pada laporan yang sudah selesai.',
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

    public function test_pengguna_dapat_melihat_klaim_miliknya_sendiri(): void
    {
        $pengaju = User::factory()->create();
        $pelapor = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
            'judul' => 'Dompet Kulit Coklat',
        ]);

        $claim = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju->id,
            'description' => 'Deskripsi rahasia kepemilikan dompet saya.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pengaju)->get(route('claims.index'));

        $response->assertOk();
        $response->assertSee('Dompet Kulit Coklat');
        $response->assertSee('Deskripsi rahasia kepemilikan dompet saya.');
        $response->assertSee('Menunggu');
    }

    public function test_pelapor_dapat_melihat_klaim_masuk_pada_laporan_penemuannya(): void
    {
        $pelapor = User::factory()->create();
        $pengaju = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
            'judul' => 'Kunci Motor Vario',
        ]);

        $claim = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju->id,
            'description' => 'Ada gantungan kunci berbentuk robot perak.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pelapor)->get(route('claims.incoming'));

        $response->assertOk();
        $response->assertSee('Kunci Motor Vario');
        $response->assertSee($pengaju->name);
        $response->assertSee('Ada gantungan kunci berbentuk robot perak.');
        $response->assertSee('Terima');
        $response->assertSee('Tolak');
    }

    public function test_pengguna_tidak_dapat_melihat_klaim_masuk_pada_laporan_milik_orang_lain(): void
    {
        $pelaporA = User::factory()->create();
        $pelaporB = User::factory()->create();
        $pengaju = User::factory()->create();

        $laporanB = Laporan::factory()->proses()->create([
            'user_id' => $pelaporB->id,
            'tipe' => 'penemuan',
            'judul' => 'Laptop Asus Hitam',
        ]);

        Claim::factory()->create([
            'laporan_id' => $laporanB->id,
            'user_id' => $pengaju->id,
            'description' => 'Laptop Asus dengan stiker Python di punggung.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pelaporA)->get(route('claims.incoming'));

        $response->assertOk();
        $response->assertDontSee('Laptop Asus Hitam');
        $response->assertDontSee('Laptop Asus dengan stiker Python di punggung.');
    }

    public function test_hanya_pelapor_terkait_yang_dapat_menerima_atau_menolak_klaim(): void
    {
        $pelapor = User::factory()->create();
        $pengaju = User::factory()->create();
        $orangLain = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
        ]);

        $claim = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju->id,
            'status' => 'pending',
        ]);

        // Orang lain mencoba memutuskan klaim -> 403
        $responseOrangLain = $this->actingAs($orangLain)->patch(route('claims.decide', $claim), [
            'decision' => 'accepted',
        ]);
        $responseOrangLain->assertStatus(403);

        // Pengaju mencoba memutuskan klaimnya sendiri -> 403
        $responsePengaju = $this->actingAs($pengaju)->patch(route('claims.decide', $claim), [
            'decision' => 'accepted',
        ]);
        $responsePengaju->assertStatus(403);

        // Admin yang BUKAN pelapor laporan ini tidak boleh memutuskan -> 403
        $responseAdmin = $this->actingAs($admin)->patch(route('claims.decide', $claim), [
            'decision' => 'accepted',
        ]);
        $responseAdmin->assertStatus(403);

        // Pelapor sah menerima klaim -> Berhasil
        $responsePelapor = $this->actingAs($pelapor)->patch(route('claims.decide', $claim), [
            'decision' => 'accepted',
        ]);
        $responsePelapor->assertRedirect(route('claims.incoming'));
        $this->assertEquals('accepted', $claim->fresh()->status);
    }

    public function test_klaim_diterima_mengubah_status_laporan_menjadi_selesai(): void
    {
        $pelapor = User::factory()->create();
        $pengaju = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
            'status' => 'proses',
        ]);

        $claim = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pelapor)->patch(route('claims.decide', $claim), [
            'decision' => 'accepted',
        ]);

        $response->assertRedirect(route('claims.incoming'));
        $this->assertEquals('accepted', $claim->fresh()->status);
        $this->assertEquals('selesai', $laporan->fresh()->status);
    }

    public function test_klaim_pending_lain_pada_laporan_yang_sama_otomatis_ditolak_saat_satu_klaim_diterima(): void
    {
        $pelapor = User::factory()->create();
        $pengaju1 = User::factory()->create();
        $pengaju2 = User::factory()->create();
        $pengaju3 = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
            'status' => 'proses',
        ]);

        $claim1 = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju1->id,
            'status' => 'pending',
        ]);

        $claim2 = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju2->id,
            'status' => 'pending',
        ]);

        $claim3 = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju3->id,
            'status' => 'pending',
        ]);

        // Pelapor menerima claim1
        $this->actingAs($pelapor)->patch(route('claims.decide', $claim1), [
            'decision' => 'accepted',
        ]);

        $this->assertEquals('accepted', $claim1->fresh()->status);
        $this->assertEquals('rejected', $claim2->fresh()->status);
        $this->assertEquals('rejected', $claim3->fresh()->status);
        $this->assertEquals('selesai', $laporan->fresh()->status);
    }

    public function test_penolakan_klaim_tidak_mengubah_status_laporan_menjadi_selesai(): void
    {
        $pelapor = User::factory()->create();
        $pengaju1 = User::factory()->create();
        $pengaju2 = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
            'status' => 'proses',
        ]);

        $claim1 = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju1->id,
            'status' => 'pending',
        ]);

        $claim2 = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju2->id,
            'status' => 'pending',
        ]);

        // Pelapor menolak claim1
        $response = $this->actingAs($pelapor)->patch(route('claims.decide', $claim1), [
            'decision' => 'rejected',
        ]);

        $response->assertRedirect(route('claims.incoming'));
        $this->assertEquals('rejected', $claim1->fresh()->status);
        // Laporan tetap berstatus proses
        $this->assertEquals('proses', $laporan->fresh()->status);
        // Klaim kedua tetap berstatus pending
        $this->assertEquals('pending', $claim2->fresh()->status);
    }

    public function test_klaim_yang_sudah_diproses_tidak_dapat_diproses_ulang(): void
    {
        $pelapor = User::factory()->create();
        $pengaju = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
            'status' => 'proses',
        ]);

        $claim = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($pelapor)->patch(route('claims.decide', $claim), [
            'decision' => 'rejected',
        ]);

        $response->assertSessionHas('error', 'Klaim ini sudah diproses sebelumnya.');
        $this->assertEquals('accepted', $claim->fresh()->status);
    }

    public function test_keputusan_klaim_memvalidasi_pilihan_hanya_accepted_atau_rejected(): void
    {
        $pelapor = User::factory()->create();
        $pengaju = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pelapor->id,
            'tipe' => 'penemuan',
            'status' => 'proses',
        ]);

        $claim = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id' => $pengaju->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pelapor)->patch(route('claims.decide', $claim), [
            'decision' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('decision');
        $this->assertEquals('pending', $claim->fresh()->status);
    }
}
