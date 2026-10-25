<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian Visibilitas Laporan TemuUNRI.
 *
 * Aturan visibilitas sesuai kesepakatan tim:
 * - pending: menunggu persetujuan admin, hanya terlihat oleh pemilik dan admin (tidak di beranda publik).
 * - proses: sudah disetujui admin dan tampil di beranda publik.
 * - selesai: masuk bagian riwayat laporan selesai secara terpisah, tidak di daftar laporan aktif.
 * - Ditolak: tidak tampil di beranda publik, tetapi tetap terlihat oleh pemilik di dashboard.
 */
class VisibilitasLaporanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Laporan berstatus 'proses' tampil di daftar laporan aktif di beranda.
     */
    public function test_beranda_menampilkan_laporan_berstatus_proses(): void
    {
        $user = User::factory()->create();
        $laporanProses = Laporan::factory()->proses()->create([
            'judul' => 'Kunci Motor Beat Ditemukan di Fekon',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Kunci Motor Beat Ditemukan di Fekon');
        $this->assertTrue(
            $response->viewData('laporans')->contains('id', $laporanProses->id)
        );
    }

    /**
     * Laporan berstatus 'pending' TIDAK tampil di beranda kepada pengguna lain.
     */
    public function test_beranda_tidak_menampilkan_laporan_pending_kepada_pengguna_lain(): void
    {
        $pemilik = User::factory()->create();
        $penggunaLain = User::factory()->create();

        $laporanPending = Laporan::factory()->create([
            'user_id' => $pemilik->id,
            'status'  => 'pending',
            'judul'   => 'HP iPhone Tertinggal di Lab Komputer',
        ]);

        $response = $this->actingAs($penggunaLain)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertDontSee('HP iPhone Tertinggal di Lab Komputer');
        $this->assertFalse(
            $response->viewData('laporans')->contains('id', $laporanPending->id)
        );
    }

    /**
     * Laporan berstatus 'pending' TIDAK tampil di daftar laporan aktif beranda
     * bahkan untuk pemiliknya sendiri (karena masih menunggu persetujuan admin).
     */
    public function test_beranda_tidak_menampilkan_laporan_pending_kepada_pemilik(): void
    {
        $pemilik = User::factory()->create();

        $laporanPending = Laporan::factory()->create([
            'user_id' => $pemilik->id,
            'status'  => 'pending',
            'judul'   => 'Helm KYT Hilang di Parkiran FKIP',
        ]);

        $response = $this->actingAs($pemilik)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Helm KYT Hilang di Parkiran FKIP');
        $this->assertFalse(
            $response->viewData('laporans')->contains('id', $laporanPending->id)
        );
    }

    /**
     * Laporan berstatus 'Ditolak' TIDAK tampil di beranda publik.
     */
    public function test_beranda_tidak_menampilkan_laporan_ditolak(): void
    {
        $pemilik = User::factory()->create();
        $penggunaLain = User::factory()->create();

        $laporanDitolak = Laporan::factory()->ditolak()->create([
            'user_id' => $pemilik->id,
            'judul'   => 'Laporan Palsu yang Ditolak Admin',
        ]);

        // Tidak terlihat oleh pengguna lain di beranda
        $responseLain = $this->actingAs($penggunaLain)->get(route('reports.index'));
        $responseLain->assertDontSee('Laporan Palsu yang Ditolak Admin');
        $this->assertFalse(
            $responseLain->viewData('laporans')->contains('id', $laporanDitolak->id)
        );

        // Juga tidak terlihat di beranda publik oleh pemiliknya sendiri
        $responsePemilik = $this->actingAs($pemilik)->get(route('reports.index'));
        $responsePemilik->assertDontSee('Laporan Palsu yang Ditolak Admin');
        $this->assertFalse(
            $responsePemilik->viewData('laporans')->contains('id', $laporanDitolak->id)
        );
    }

    /**
     * Laporan berstatus 'selesai' TIDAK muncul pada daftar laporan aktif,
     * melainkan HANYA muncul pada bagian Riwayat Laporan Selesai.
     */
    public function test_laporan_selesai_hanya_muncul_pada_bagian_riwayat_selesai(): void
    {
        $user = User::factory()->create();

        $laporanSelesai = Laporan::factory()->selesai()->create([
            'judul' => 'Dompet Kulit Cokelat Sudah Dikembalikan',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertStatus(200);

        // Harus ada di viewData 'laporanSelesai' (bagian riwayat selesai)
        $this->assertTrue(
            $response->viewData('laporanSelesai')->contains('id', $laporanSelesai->id)
        );

        // TIDAK boleh ada di viewData 'laporans' (daftar laporan aktif)
        $this->assertFalse(
            $response->viewData('laporans')->contains('id', $laporanSelesai->id)
        );

        // Tampil pada teks riwayat selesai di HTML
        $response->assertSee('Dompet Kulit Cokelat Sudah Dikembalikan');
        $response->assertSee('Riwayat Laporan Selesai');
    }

    /**
     * Bagian Barang Hilang Terbaru hanya menampilkan laporan hilang berstatus 'proses'.
     */
    public function test_bagian_barang_hilang_terbaru_hanya_menampilkan_status_proses(): void
    {
        $user = User::factory()->create();

        $hilangProses = Laporan::factory()->proses()->create([
            'tipe'  => 'hilang',
            'judul' => 'Jaket Hitam Hilang Aktif',
        ]);
        $hilangPending = Laporan::factory()->create([
            'tipe'   => 'hilang',
            'status' => 'pending',
            'judul'  => 'Jaket Hitam Hilang Menunggu',
        ]);
        $hilangDitolak = Laporan::factory()->ditolak()->create([
            'tipe'  => 'hilang',
            'judul' => 'Jaket Hitam Hilang Ditolak',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $barangHilang = $response->viewData('barangHilang');
        $this->assertTrue($barangHilang->contains('id', $hilangProses->id));
        $this->assertFalse($barangHilang->contains('id', $hilangPending->id));
        $this->assertFalse($barangHilang->contains('id', $hilangDitolak->id));
    }

    /**
     * Bagian Barang Penemuan Terbaru hanya menampilkan laporan penemuan berstatus 'proses'.
     */
    public function test_bagian_barang_penemuan_terbaru_hanya_menampilkan_status_proses(): void
    {
        $user = User::factory()->create();

        $penemuanProses = Laporan::factory()->proses()->create([
            'tipe'  => 'penemuan',
            'judul' => 'Kacamata Minus Ditemukan Aktif',
        ]);
        $penemuanPending = Laporan::factory()->create([
            'tipe'   => 'penemuan',
            'status' => 'pending',
            'judul'  => 'Kacamata Minus Ditemukan Menunggu',
        ]);
        $penemuanDitolak = Laporan::factory()->ditolak()->create([
            'tipe'  => 'penemuan',
            'judul' => 'Kacamata Minus Ditemukan Ditolak',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $barangPenemuan = $response->viewData('barangPenemuan');
        $this->assertTrue($barangPenemuan->contains('id', $penemuanProses->id));
        $this->assertFalse($barangPenemuan->contains('id', $penemuanPending->id));
        $this->assertFalse($barangPenemuan->contains('id', $penemuanDitolak->id));
    }

    /**
     * Pencarian di beranda hanya mencari laporan berstatus 'proses',
     * dan tidak membocorkan laporan pending atau ditolak.
     */
    public function test_pencarian_hanya_menampilkan_laporan_berstatus_proses(): void
    {
        $user = User::factory()->create();

        $proses = Laporan::factory()->proses()->create([
            'judul'  => 'Flashdisk Sandisk 64GB Hilang',
            'lokasi' => 'Gedung Rektorat UNRI',
        ]);
        $pending = Laporan::factory()->create([
            'status' => 'pending',
            'judul'  => 'Flashdisk Toshiba 32GB Hilang',
            'lokasi' => 'Gedung Rektorat UNRI',
        ]);
        $ditolak = Laporan::factory()->ditolak()->create([
            'judul'  => 'Flashdisk Kingston 16GB Hilang',
            'lokasi' => 'Gedung Rektorat UNRI',
        ]);

        // Cari berdasarkan kata kunci pada judul
        $responseJudul = $this->actingAs($user)->get(route('reports.index', ['search' => 'Flashdisk']));
        $responseJudul->assertSee('Flashdisk Sandisk 64GB Hilang');
        $responseJudul->assertDontSee('Flashdisk Toshiba 32GB Hilang');
        $responseJudul->assertDontSee('Flashdisk Kingston 16GB Hilang');

        // Cari berdasarkan kata kunci pada lokasi (memastikan OR logic tidak membocorkan status lain)
        $responseLokasi = $this->actingAs($user)->get(route('reports.index', ['search' => 'Rektorat']));
        $this->assertTrue($responseLokasi->viewData('laporans')->contains('id', $proses->id));
        $this->assertFalse($responseLokasi->viewData('laporans')->contains('id', $pending->id));
        $this->assertFalse($responseLokasi->viewData('laporans')->contains('id', $ditolak->id));
    }

    /**
     * Filter tipe di beranda hanya menampilkan laporan berstatus 'proses'.
     */
    public function test_filter_tipe_hanya_menampilkan_laporan_berstatus_proses(): void
    {
        $user = User::factory()->create();

        $hilangProses = Laporan::factory()->proses()->create([
            'tipe'  => 'hilang',
            'judul' => 'Buku Catatan Hilang Proses',
        ]);
        $hilangPending = Laporan::factory()->create([
            'tipe'   => 'hilang',
            'status' => 'pending',
            'judul'  => 'Buku Catatan Hilang Pending',
        ]);
        $penemuanProses = Laporan::factory()->proses()->create([
            'tipe'  => 'penemuan',
            'judul' => 'Buku Cetak Ditemukan Proses',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index', ['tipe' => 'hilang']));

        $laporans = $response->viewData('laporans');
        $this->assertTrue($laporans->contains('id', $hilangProses->id));
        $this->assertFalse($laporans->contains('id', $hilangPending->id));
        $this->assertFalse($laporans->contains('id', $penemuanProses->id));
    }

    /**
     * Filter kategori di beranda hanya menampilkan laporan berstatus 'proses'.
     */
    public function test_filter_kategori_hanya_menampilkan_laporan_berstatus_proses(): void
    {
        $user = User::factory()->create();

        $elektronikProses = Laporan::factory()->proses()->create([
            'kategori' => 'Elektronik',
            'judul'    => 'Powerbank Xiaomi Ditemukan',
        ]);
        $elektronikPending = Laporan::factory()->create([
            'kategori' => 'Elektronik',
            'status'   => 'pending',
            'judul'    => 'Powerbank Robot Hilang',
        ]);
        $dokumenProses = Laporan::factory()->proses()->create([
            'kategori' => 'Dokumen',
            'judul'    => 'KTM Mahasiswa Ditemukan',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index', ['kategori' => 'Elektronik']));

        $laporans = $response->viewData('laporans');
        $this->assertTrue($laporans->contains('id', $elektronikProses->id));
        $this->assertFalse($laporans->contains('id', $elektronikPending->id));
        $this->assertFalse($laporans->contains('id', $dokumenProses->id));
    }

    /**
     * Dashboard pemilik tetap menampilkan semua laporan miliknya beserta statusnya:
     * pending, proses, selesai, dan Ditolak.
     */
    public function test_dashboard_pemilik_tetap_menampilkan_semua_status_laporannya(): void
    {
        $pemilik = User::factory()->create();

        $laporanPending = Laporan::factory()->create([
            'user_id' => $pemilik->id,
            'status'  => 'pending',
            'judul'   => 'Laporan Saya Status Pending',
        ]);
        $laporanProses = Laporan::factory()->proses()->create([
            'user_id' => $pemilik->id,
            'judul'   => 'Laporan Saya Status Proses',
        ]);
        $laporanSelesai = Laporan::factory()->selesai()->create([
            'user_id' => $pemilik->id,
            'judul'   => 'Laporan Saya Status Selesai',
        ]);
        $laporanDitolak = Laporan::factory()->ditolak()->create([
            'user_id' => $pemilik->id,
            'judul'   => 'Laporan Saya Status Ditolak',
        ]);

        $response = $this->actingAs($pemilik)->get('/dashboard');

        $response->assertStatus(200);

        // Keempat laporan harus muncul di dashboard pemilik
        $response->assertSee('Laporan Saya Status Pending');
        $response->assertSee('Laporan Saya Status Proses');
        $response->assertSee('Laporan Saya Status Selesai');
        $response->assertSee('Laporan Saya Status Ditolak');

        // Statistik dashboard pemilik harus akurat
        $this->assertEquals(1, $response->viewData('jumlahPending'));
        $this->assertEquals(1, $response->viewData('jumlahProses'));
        $this->assertEquals(1, $response->viewData('jumlahSelesai'));
        $this->assertEquals(1, $response->viewData('jumlahDitolak'));
        $this->assertEquals(4, $response->viewData('jumlahTotal'));
    }
}
