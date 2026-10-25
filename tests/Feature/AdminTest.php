<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian Dashboard Admin TemuUNRI.
 *
 * Memverifikasi:
 * 1. Akses dashboard admin
 * 2. Proteksi akses route admin
 * 3. Fungsi setujui dan tolak laporan
 * 4. Validasi server-side (status bukan pending tidak bisa diproses ulang)
 * 5. Laporan yang disetujui tampil di beranda publik
 * 6. Laporan yang ditolak tidak tampil di beranda publik
 * 7. Fitur dashboard pengguna biasa tidak terganggu
 */
class AdminTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // Test: Akses Dashboard Admin
    // =========================================================

    /**
     * Admin dapat membuka dashboard admin.
     */
    public function test_admin_dapat_mengakses_dashboard_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Panel Admin');
    }

    /**
     * Pengguna yang belum login tidak dapat mengakses dashboard admin.
     */
    public function test_tamu_ditolak_dari_dashboard_admin(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect('/login');
    }

    /**
     * Pengguna biasa yang sudah login tetap ditolak dari dashboard admin dengan 403.
     */
    public function test_pengguna_biasa_ditolak_dari_dashboard_admin(): void
    {
        $pengguna = User::factory()->create(); // role default: 'user'

        $response = $this->actingAs($pengguna)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    /**
     * Dashboard admin menampilkan ringkasan statistik laporan.
     */
    public function test_dashboard_admin_menampilkan_statistik_laporan(): void
    {
        $admin = User::factory()->admin()->create();

        Laporan::factory()->create(['status' => 'pending']);
        Laporan::factory()->proses()->create();
        Laporan::factory()->selesai()->create();
        Laporan::factory()->ditolak()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('jumlahPending', 1);
        $response->assertViewHas('jumlahProses', 1);
        $response->assertViewHas('jumlahSelesai', 1);
        $response->assertViewHas('jumlahDitolak', 1);
        $response->assertViewHas('jumlahTotal', 4);
    }

    /**
     * Dashboard admin menampilkan daftar laporan yang berstatus pending.
     */
    public function test_dashboard_admin_menampilkan_laporan_pending(): void
    {
        $admin = User::factory()->admin()->create();

        $laporanPending = Laporan::factory()->create([
            'status' => 'pending',
            'judul'  => 'Tas Ransel Biru Menunggu Persetujuan',
        ]);
        $laporanProses = Laporan::factory()->proses()->create([
            'judul' => 'Kunci Motor Yang Sudah Aktif',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Tas Ransel Biru Menunggu Persetujuan');
        $response->assertDontSee('Kunci Motor Yang Sudah Aktif');
    }

    // =========================================================
    // Test: Setujui Laporan
    // =========================================================

    /**
     * Admin dapat menyetujui laporan pending sehingga statusnya menjadi proses.
     */
    public function test_admin_dapat_menyetujui_laporan_pending(): void
    {
        $admin   = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)
            ->post(route('admin.laporan.setujui', $laporan->id));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $this->assertEquals('proses', $laporan->fresh()->status);
    }

    /**
     * Laporan yang disetujui admin tampil di beranda publik.
     */
    public function test_laporan_disetujui_tampil_di_beranda(): void
    {
        $admin   = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create([
            'status' => 'pending',
            'judul'  => 'Dompet Hitam Menunggu Persetujuan',
        ]);

        // Setujui melalui route admin
        $this->actingAs($admin)
            ->post(route('admin.laporan.setujui', $laporan->id));

        // Setelah disetujui, pengguna biasa bisa melihat di beranda
        $pengguna = User::factory()->create();
        $responseIndex = $this->actingAs($pengguna)->get(route('reports.index'));

        $responseIndex->assertSee('Dompet Hitam Menunggu Persetujuan');
        $this->assertEquals('proses', $laporan->fresh()->status);
    }

    // =========================================================
    // Test: Tolak Laporan
    // =========================================================

    /**
     * Admin dapat menolak laporan pending sehingga statusnya menjadi Ditolak.
     */
    public function test_admin_dapat_menolak_laporan_pending(): void
    {
        $admin   = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)
            ->post(route('admin.laporan.tolak', $laporan->id));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $this->assertEquals('Ditolak', $laporan->fresh()->status);
    }

    /**
     * Laporan yang ditolak admin tidak tampil di beranda publik.
     */
    public function test_laporan_ditolak_tidak_tampil_di_beranda(): void
    {
        $admin   = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create([
            'status' => 'pending',
            'judul'  => 'Laporan Tidak Sesuai Yang Ditolak',
        ]);

        // Tolak melalui route admin
        $this->actingAs($admin)
            ->post(route('admin.laporan.tolak', $laporan->id));

        // Pastikan status sudah berubah ke Ditolak
        $this->assertEquals('Ditolak', $laporan->fresh()->status);

        // Akses beranda sebagai pengguna biasa (sesi baru agar tidak ada session flash dari admin)
        $pengguna = User::factory()->create();
        $responseIndex = $this->actingAs($pengguna)
            ->withSession([]) // sesi bersih tanpa flash message dari tindakan admin sebelumnya
            ->get(route('reports.index'));

        // Laporan Ditolak tidak boleh ada di daftar laporan aktif
        $this->assertFalse(
            $responseIndex->viewData('laporans')->contains('id', $laporan->id)
        );
    }

    // =========================================================
    // Test: Validasi Server-Side (Laporan Non-Pending)
    // =========================================================

    /**
     * Admin tidak dapat menyetujui laporan yang sudah berstatus proses.
     */
    public function test_admin_tidak_dapat_setujui_laporan_berstatus_proses(): void
    {
        $admin   = User::factory()->admin()->create();
        $laporan = Laporan::factory()->proses()->create();

        $response = $this->actingAs($admin)
            ->post(route('admin.laporan.setujui', $laporan->id));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // Status tidak berubah
        $this->assertEquals('proses', $laporan->fresh()->status);
    }

    /**
     * Admin tidak dapat menyetujui laporan yang sudah berstatus selesai.
     */
    public function test_admin_tidak_dapat_setujui_laporan_berstatus_selesai(): void
    {
        $admin   = User::factory()->admin()->create();
        $laporan = Laporan::factory()->selesai()->create();

        $response = $this->actingAs($admin)
            ->post(route('admin.laporan.setujui', $laporan->id));

        $response->assertSessionHas('error');
        $this->assertEquals('selesai', $laporan->fresh()->status);
    }

    /**
     * Admin tidak dapat menolak laporan yang statusnya sudah bukan pending.
     */
    public function test_admin_tidak_dapat_tolak_laporan_berstatus_proses(): void
    {
        $admin   = User::factory()->admin()->create();
        $laporan = Laporan::factory()->proses()->create();

        $response = $this->actingAs($admin)
            ->post(route('admin.laporan.tolak', $laporan->id));

        $response->assertSessionHas('error');

        // Status tidak berubah
        $this->assertEquals('proses', $laporan->fresh()->status);
    }

    // =========================================================
    // Test: Pengguna Biasa Tidak Dapat Bypass Otorisasi
    // =========================================================

    /**
     * Pengguna biasa tidak dapat menyetujui laporan melalui request langsung.
     */
    public function test_pengguna_biasa_tidak_dapat_setujui_laporan(): void
    {
        $pengguna = User::factory()->create();
        $laporan  = Laporan::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($pengguna)
            ->post(route('admin.laporan.setujui', $laporan->id));

        $response->assertStatus(403);

        // Status tidak berubah
        $this->assertEquals('pending', $laporan->fresh()->status);
    }

    /**
     * Pengguna biasa tidak dapat menolak laporan melalui request langsung.
     */
    public function test_pengguna_biasa_tidak_dapat_tolak_laporan(): void
    {
        $pengguna = User::factory()->create();
        $laporan  = Laporan::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($pengguna)
            ->post(route('admin.laporan.tolak', $laporan->id));

        $response->assertStatus(403);

        // Status tidak berubah
        $this->assertEquals('pending', $laporan->fresh()->status);
    }

    /**
     * Tamu tidak dapat menyetujui laporan (diarahkan ke login).
     */
    public function test_tamu_tidak_dapat_setujui_laporan(): void
    {
        $laporan = Laporan::factory()->create(['status' => 'pending']);

        $response = $this->post(route('admin.laporan.setujui', $laporan->id));

        $response->assertRedirect('/login');
        $this->assertEquals('pending', $laporan->fresh()->status);
    }

    /**
     * Tamu tidak dapat menolak laporan (diarahkan ke login).
     */
    public function test_tamu_tidak_dapat_tolak_laporan(): void
    {
        $laporan = Laporan::factory()->create(['status' => 'pending']);

        $response = $this->post(route('admin.laporan.tolak', $laporan->id));

        $response->assertRedirect('/login');
        $this->assertEquals('pending', $laporan->fresh()->status);
    }

    // =========================================================
    // Test: Detail Laporan untuk Admin
    // =========================================================

    /**
     * Admin dapat mengakses halaman detail laporan.
     */
    public function test_admin_dapat_melihat_detail_laporan(): void
    {
        $admin   = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create([
            'status' => 'pending',
            'judul'  => 'HP Samsung Galaxy Hilang di Parkiran',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.show', $laporan->id));

        $response->assertStatus(200);
        $response->assertSee('HP Samsung Galaxy Hilang di Parkiran');
        $response->assertSee('Tinjauan Admin');
    }

    /**
     * Pengguna biasa tidak dapat mengakses halaman detail laporan admin.
     */
    public function test_pengguna_biasa_tidak_dapat_akses_detail_admin(): void
    {
        $pengguna = User::factory()->create();
        $laporan  = Laporan::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($pengguna)
            ->get(route('admin.laporan.show', $laporan->id));

        $response->assertStatus(403);
    }

    // =========================================================
    // Test: Fitur Existing Tidak Terganggu
    // =========================================================

    /**
     * Dashboard pengguna biasa masih berfungsi normal setelah penambahan fitur admin.
     */
    public function test_dashboard_pengguna_biasa_tetap_berfungsi(): void
    {
        $pengguna = User::factory()->create(['name' => 'Budi Mahasiswa']);

        $laporanSendiri = Laporan::factory()->create([
            'user_id' => $pengguna->id,
            'judul'   => 'Kacamata Merah Hilang Milik Budi',
        ]);
        $laporanOrang = Laporan::factory()->create([
            'judul' => 'Dompet Orang Lain',
        ]);

        $response = $this->actingAs($pengguna)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Budi Mahasiswa');
        $response->assertSee('Kacamata Merah Hilang Milik Budi');
        $response->assertDontSee('Dompet Orang Lain');
    }

    /**
     * Setelah admin menyetujui laporan, laporan muncul di beranda via filter tipe.
     */
    public function test_laporan_disetujui_muncul_dalam_filter_tipe(): void
    {
        $admin   = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create([
            'status' => 'pending',
            'tipe'   => 'hilang',
            'judul'  => 'Payung Biru Hilang di Kantin',
        ]);

        // Setujui
        $this->actingAs($admin)->post(route('admin.laporan.setujui', $laporan->id));

        // Cek beranda dengan filter tipe
        $pengguna = User::factory()->create();
        $response = $this->actingAs($pengguna)
            ->get(route('reports.index', ['tipe' => 'hilang']));

        $response->assertSee('Payung Biru Hilang di Kantin');
    }
}
