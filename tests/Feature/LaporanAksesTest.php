<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test untuk Hak Akses Laporan (Orang 2).
 * Mencakup aturan kepemilikan dan pembatasan berdasarkan status laporan.
 */
class LaporanAksesTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // Test: Pengguna tidak bisa mengedit laporan milik orang lain
    // =========================================================

    /**
     * Pengguna tidak bisa mengakses form edit laporan milik orang lain.
     */
    public function test_pengguna_tidak_bisa_edit_laporan_orang_lain(): void
    {
        $pemilik = User::factory()->create();
        $orang   = User::factory()->create();

        $laporan = Laporan::factory()->create([
            'user_id' => $pemilik->id,
            'status'  => 'pending',
        ]);

        // $orang mencoba edit laporan $pemilik
        $response = $this->actingAs($orang)->get(route('reports.edit', $laporan->id));

        $response->assertStatus(403);
    }

    /**
     * Pengguna tidak bisa mengirim update untuk laporan milik orang lain.
     */
    public function test_pengguna_tidak_bisa_update_laporan_orang_lain(): void
    {
        $pemilik = User::factory()->create();
        $orang   = User::factory()->create();

        $laporan = Laporan::factory()->create([
            'user_id' => $pemilik->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($orang)->patch(route('reports.update', $laporan->id), [
            'judul'            => 'Judul Diubah',
            'tipe'             => $laporan->tipe,
            'kategori'         => $laporan->kategori,
            'deskripsi'        => $laporan->deskripsi,
            'lokasi'           => $laporan->lokasi,
            'tanggal_kejadian' => $laporan->tanggal_kejadian,
        ]);

        $response->assertStatus(403);
    }

    /**
     * Pengguna tidak bisa menghapus laporan milik orang lain.
     */
    public function test_pengguna_tidak_bisa_hapus_laporan_orang_lain(): void
    {
        $pemilik = User::factory()->create();
        $orang   = User::factory()->create();

        $laporan = Laporan::factory()->create([
            'user_id' => $pemilik->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($orang)->delete(route('reports.destroy', $laporan->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('laporans', ['id' => $laporan->id]);
    }

    // =========================================================
    // Test: Laporan berstatus 'pending' bisa diedit dan dihapus
    // =========================================================

    /**
     * Pemilik bisa mengakses form edit laporan berstatus 'pending'.
     */
    public function test_pemilik_bisa_edit_laporan_pending(): void
    {
        $user    = User::factory()->create();
        $laporan = Laporan::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $response = $this->actingAs($user)->get(route('reports.edit', $laporan->id));

        $response->assertStatus(200);
    }

    /**
     * Pemilik bisa menghapus laporan berstatus 'pending'.
     */
    public function test_pemilik_bisa_hapus_laporan_pending(): void
    {
        $user    = User::factory()->create();
        $laporan = Laporan::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $response = $this->actingAs($user)->delete(route('reports.destroy', $laporan->id));

        $response->assertRedirect(route('reports.index'));
        $this->assertDatabaseMissing('laporans', ['id' => $laporan->id]);
    }

    // =========================================================
    // Test: Laporan berstatus selain 'pending' TIDAK bisa diedit/dihapus
    // =========================================================

    /**
     * Pemilik tidak bisa edit laporan berstatus 'proses'.
     */
    public function test_pemilik_tidak_bisa_edit_laporan_berstatus_proses(): void
    {
        $user    = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('reports.edit', $laporan->id));

        // Harus diarahkan ke halaman detail (redirect), bukan 200
        $response->assertRedirect(route('reports.show', $laporan->id));
    }

    /**
     * Pemilik tidak bisa edit laporan berstatus 'selesai'.
     */
    public function test_pemilik_tidak_bisa_edit_laporan_berstatus_selesai(): void
    {
        $user    = User::factory()->create();
        $laporan = Laporan::factory()->selesai()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('reports.edit', $laporan->id));

        $response->assertRedirect(route('reports.show', $laporan->id));
    }

    /**
     * Pemilik tidak bisa edit laporan berstatus 'Ditolak'.
     */
    public function test_pemilik_tidak_bisa_edit_laporan_berstatus_ditolak(): void
    {
        $user    = User::factory()->create();
        $laporan = Laporan::factory()->ditolak()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('reports.edit', $laporan->id));

        $response->assertRedirect(route('reports.show', $laporan->id));
    }

    /**
     * Pemilik tidak bisa menghapus laporan berstatus 'proses'.
     */
    public function test_pemilik_tidak_bisa_hapus_laporan_berstatus_proses(): void
    {
        $user    = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('reports.destroy', $laporan->id));

        $response->assertRedirect(route('reports.show', $laporan->id));
        // Laporan masih ada di database
        $this->assertDatabaseHas('laporans', ['id' => $laporan->id]);
    }

    /**
     * Pemilik tidak bisa menghapus laporan berstatus 'selesai'.
     */
    public function test_pemilik_tidak_bisa_hapus_laporan_berstatus_selesai(): void
    {
        $user    = User::factory()->create();
        $laporan = Laporan::factory()->selesai()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('reports.destroy', $laporan->id));

        $response->assertRedirect(route('reports.show', $laporan->id));
        $this->assertDatabaseHas('laporans', ['id' => $laporan->id]);
    }

    /**
     * Pemilik tidak bisa menghapus laporan berstatus 'Ditolak'.
     */
    public function test_pemilik_tidak_bisa_hapus_laporan_berstatus_ditolak(): void
    {
        $user    = User::factory()->create();
        $laporan = Laporan::factory()->ditolak()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('reports.destroy', $laporan->id));

        $response->assertRedirect(route('reports.show', $laporan->id));
        $this->assertDatabaseHas('laporans', ['id' => $laporan->id]);
    }

    // =========================================================
    // Test: Pengguna belum login ditolak dari route laporan
    // =========================================================

    /**
     * Pengguna yang belum login tidak bisa mengakses daftar laporan.
     */
    public function test_pengguna_belum_login_ditolak_dari_halaman_laporan(): void
    {
        $response = $this->get(route('reports.index'));

        $response->assertRedirect('/login');
    }

    /**
     * Pengguna yang belum login tidak bisa membuat laporan.
     */
    public function test_pengguna_belum_login_ditolak_dari_buat_laporan(): void
    {
        $response = $this->get(route('reports.create'));

        $response->assertRedirect('/login');
    }

    // =========================================================
    // Test: user_id tidak bisa dimanipulasi dari form
    // =========================================================

    /**
     * Saat membuat laporan, user_id harus diambil dari session login,
     * bukan dari data yang dikirim form.
     */
    public function test_user_id_laporan_diambil_dari_session_bukan_form(): void
    {
        $user   = User::factory()->create();
        $lainnya = User::factory()->create();

        // Kirim user_id palsu (ID pengguna lain) dalam form
        $this->actingAs($user)->post(route('reports.store'), [
            'user_id'          => $lainnya->id, // Dicoba manipulasi
            'judul'            => 'Laporan Test',
            'tipe'             => 'hilang',
            'kategori'         => 'Dompet',
            'deskripsi'        => 'Deskripsi test laporan',
            'lokasi'           => 'Gedung A',
            'tanggal_kejadian' => '2026-10-25',
        ]);

        // Laporan harus tercatat atas nama $user yang login, bukan $lainnya
        $this->assertDatabaseHas('laporans', [
            'judul'   => 'Laporan Test',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('laporans', [
            'judul'   => 'Laporan Test',
            'user_id' => $lainnya->id,
        ]);
    }
}
