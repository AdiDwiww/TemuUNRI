<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TandaiBarangDitemukanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1 & 2. Pemilik dapat menandai laporan kehilangan berstatus proses sebagai selesai,
     * dan status laporan benar-benar berubah menjadi selesai di database.
     */
    public function test_pemilik_dapat_menandai_laporan_kehilangan_berstatus_proses_sebagai_selesai(): void
    {
        $pemilik = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
            'judul'   => 'Kunci Motor Beat Hitam',
        ]);

        $response = $this->actingAs($pemilik)->patch(route('reports.mark-as-found', $laporan));

        $response->assertRedirect(route('reports.show', $laporan));
        $response->assertSessionHas('success');

        $laporan->refresh();
        $this->assertSame('selesai', $laporan->status);
        $this->assertDatabaseHas('laporans', [
            'id'     => $laporan->id,
            'status' => 'selesai',
        ]);
    }

    /**
     * 3 & 4. Laporan yang selesai tidak muncul lagi dalam daftar aktif dan masuk ke riwayat.
     */
    public function test_laporan_selesai_tidak_muncul_dalam_daftar_aktif_dan_masuk_ke_riwayat(): void
    {
        $pemilik = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
            'judul'   => 'Dompet Kulit Cokelat Unik',
        ]);

        // Sebelum diselesaikan: muncul di daftar aktif beranda
        $responseAktif = $this->actingAs($pemilik)->get(route('reports.index'));
        $responseAktif->assertStatus(200);
        $responseAktif->assertSee('Dompet Kulit Cokelat Unik');

        // Selesaikan laporan
        $this->actingAs($pemilik)->patch(route('reports.mark-as-found', $laporan));

        // Setelah diselesaikan:
        $responseSetelah = $this->actingAs($pemilik)->get(route('reports.index'));
        $responseSetelah->assertStatus(200);
        // Tetap tampil pada halaman (karena masuk ke riwayat laporanSelesai)
        $responseSetelah->assertSee('Dompet Kulit Cokelat Unik');

        // Verifikasi langsung dari query controller: tidak ada di daftar aktif 'proses'
        $laporanAktif = Laporan::where('status', 'proses')->where('id', $laporan->id)->first();
        $this->assertNull($laporanAktif);

        // Muncul di query 'selesai'
        $laporanDiRiwayat = Laporan::where('status', 'selesai')->where('id', $laporan->id)->first();
        $this->assertNotNull($laporanDiRiwayat);
    }

    /**
     * 5. User lain tidak dapat menyelesaikan laporan milik pengguna lain (403).
     */
    public function test_user_lain_tidak_dapat_menyelesaikan_laporan_milik_pengguna_lain(): void
    {
        $pemilik   = User::factory()->create();
        $userLain  = User::factory()->create();
        $laporan   = Laporan::factory()->proses()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
        ]);

        $response = $this->actingAs($userLain)->patch(route('reports.mark-as-found', $laporan));

        $response->assertStatus(403);

        $laporan->refresh();
        $this->assertSame('proses', $laporan->status);
    }

    /**
     * 6. Laporan bertipe penemuan tidak dapat diselesaikan melalui fitur ini.
     */
    public function test_laporan_bertipe_penemuan_tidak_dapat_diselesaikan_melalui_fitur_ini(): void
    {
        $pemilik = User::factory()->create();
        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'penemuan',
        ]);

        $response = $this->actingAs($pemilik)->patch(route('reports.mark-as-found', $laporan));

        $response->assertRedirect(route('reports.show', $laporan));
        $response->assertSessionHas('error');

        $laporan->refresh();
        $this->assertSame('proses', $laporan->status);
    }

    /**
     * 7. Laporan berstatus pending tidak dapat diselesaikan.
     */
    public function test_laporan_berstatus_pending_tidak_dapat_diselesaikan(): void
    {
        $pemilik = User::factory()->create();
        $laporan = Laporan::factory()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($pemilik)->patch(route('reports.mark-as-found', $laporan));

        $response->assertRedirect(route('reports.show', $laporan));
        $response->assertSessionHas('error');

        $laporan->refresh();
        $this->assertSame('pending', $laporan->status);
    }

    /**
     * 8. Laporan yang sudah selesai tidak dapat diproses ulang.
     */
    public function test_laporan_yang_sudah_selesai_tidak_dapat_diproses_ulang(): void
    {
        $pemilik = User::factory()->create();
        $laporan = Laporan::factory()->selesai()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
        ]);

        $response = $this->actingAs($pemilik)->patch(route('reports.mark-as-found', $laporan));

        $response->assertRedirect(route('reports.show', $laporan));
        $response->assertSessionHas('error');

        $laporan->refresh();
        $this->assertSame('selesai', $laporan->status);
    }

    /**
     * 9. Klaim pending pada laporan yang diselesaikan otomatis berubah menjadi rejected.
     */
    public function test_klaim_pending_otomatis_berubah_menjadi_rejected(): void
    {
        $pemilik = User::factory()->create();
        $pengklaim = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
        ]);

        $claimPending = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id'    => $pengklaim->id,
            'status'     => 'pending',
        ]);

        $this->actingAs($pemilik)->patch(route('reports.mark-as-found', $laporan));

        $claimPending->refresh();
        $this->assertSame('rejected', $claimPending->status);
    }

    /**
     * 10. Klaim dengan status selain pending tidak berubah (accepted & rejected tetap).
     */
    public function test_klaim_dengan_status_selain_pending_tidak_berubah(): void
    {
        $pemilik    = User::factory()->create();
        $pengklaim1 = User::factory()->create();
        $pengklaim2 = User::factory()->create();
        $pengklaim3 = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
        ]);

        $claimAccepted = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id'    => $pengklaim1->id,
            'status'     => 'accepted',
        ]);

        $claimRejected = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id'    => $pengklaim2->id,
            'status'     => 'rejected',
        ]);

        $claimPending = Claim::factory()->create([
            'laporan_id' => $laporan->id,
            'user_id'    => $pengklaim3->id,
            'status'     => 'pending',
        ]);

        $this->actingAs($pemilik)->patch(route('reports.mark-as-found', $laporan));

        $claimAccepted->refresh();
        $claimRejected->refresh();
        $claimPending->refresh();

        $this->assertSame('accepted', $claimAccepted->status, 'Klaim accepted tidak boleh berubah');
        $this->assertSame('rejected', $claimRejected->status, 'Klaim rejected lama tidak boleh berubah');
        $this->assertSame('rejected', $claimPending->status, 'Klaim pending harus menjadi rejected');
    }

    /**
     * UI test: Tombol 'Barang Ditemukan' hanya tampil pada kondisi yang sesuai di halaman show.
     */
    public function test_tombol_barang_ditemukan_tampil_hanya_pada_kondisi_yang_sesuai(): void
    {
        $pemilik  = User::factory()->create();
        $userLain = User::factory()->create();

        // Kondisi sesuai: Pemilik + Hilang + Proses -> Tombol Tampil
        $laporanSesuai = Laporan::factory()->proses()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
        ]);
        $response = $this->actingAs($pemilik)->get(route('reports.show', $laporanSesuai));
        $response->assertSee('Barang Ditemukan');
        $response->assertSee(route('reports.mark-as-found', $laporanSesuai));

        // Kondisi tidak sesuai: Pengguna lain melihat laporan yang sama -> Tombol TIDAK tampil
        $responseLain = $this->actingAs($userLain)->get(route('reports.show', $laporanSesuai));
        $responseLain->assertDontSee('Barang Ditemukan');

        // Kondisi tidak sesuai: Pemilik + Hilang + Status Pending -> Tombol TIDAK tampil
        $laporanPending = Laporan::factory()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
            'status'  => 'pending',
        ]);
        $responsePending = $this->actingAs($pemilik)->get(route('reports.show', $laporanPending));
        $responsePending->assertDontSee('Barang Ditemukan');

        // Kondisi tidak sesuai: Pemilik + Hilang + Status Selesai -> Tombol TIDAK tampil
        $laporanSelesai = Laporan::factory()->selesai()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'hilang',
        ]);
        $responseSelesai = $this->actingAs($pemilik)->get(route('reports.show', $laporanSelesai));
        $responseSelesai->assertDontSee('Barang Ditemukan');

        // Kondisi tidak sesuai: Pemilik + Penemuan + Status Proses -> Tombol TIDAK tampil
        $laporanPenemuan = Laporan::factory()->proses()->create([
            'user_id' => $pemilik->id,
            'tipe'    => 'penemuan',
        ]);
        $responsePenemuan = $this->actingAs($pemilik)->get(route('reports.show', $laporanPenemuan));
        $responsePenemuan->assertDontSee('Barang Ditemukan');
    }
}

