<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageCropTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat file PNG valid dengan dimensi tertentu tanpa dependensi GD.
     */
    private function createPngFile(string $filename, int $width, int $height): UploadedFile
    {
        $sig = "\x89PNG\r\n\x1a\n";
        $ihdrData = pack('NNCCCCC', $width, $height, 8, 2, 0, 0, 0);
        $ihdr = pack('N', 13) . 'IHDR' . $ihdrData . pack('N', crc32('IHDR' . $ihdrData));
        $rawScanlines = str_repeat("\x00" . str_repeat("\x00\x00\x00", $width), $height);
        $compressed = gzcompress($rawScanlines);
        $idat = pack('N', strlen($compressed)) . 'IDAT' . $compressed . pack('N', crc32('IDAT' . $compressed));
        $iend = pack('N', 0) . 'IEND' . pack('N', crc32('IEND'));

        $content = $sig . $ihdr . $idat . $iend;

        return UploadedFile::fake()->createWithContent($filename, $content);
    }

    public function test_user_can_create_report_with_valid_4x5_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = $this->createPngFile('barang_4x5.png', 800, 1000);

        $response = $this->actingAs($user)->post(route('reports.store'), [
            'judul' => 'Dompet Kulit Cokelat',
            'tipe' => 'hilang',
            'kategori' => 'Dompet',
            'deskripsi' => 'Dompet kulit cokelat hilang di dekat perpustakaan.',
            'lokasi' => 'Perpustakaan UNRI',
            'tanggal_kejadian' => date('Y-m-d'),
            'foto' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('reports.index'));

        $laporan = Laporan::first();
        $this->assertNotNull($laporan);
        $this->assertNotNull($laporan->foto);
        Storage::disk('public')->assertExists($laporan->foto);
    }

    public function test_user_cannot_create_report_with_square_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $squareFile = $this->createPngFile('barang_1x1.png', 800, 800);

        $response = $this->actingAs($user)->post(route('reports.store'), [
            'judul' => 'Kunci Motor Honda',
            'tipe' => 'penemuan',
            'kategori' => 'Kunci',
            'deskripsi' => 'Ditemukan gantungan kunci motor di kantin.',
            'lokasi' => 'Kantin Pusat',
            'tanggal_kejadian' => date('Y-m-d'),
            'foto' => $squareFile,
        ]);

        $response->assertSessionHasErrors('foto');
        $this->assertDatabaseCount('laporans', 0);
    }

    public function test_user_cannot_create_report_with_landscape_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $landscapeFile = $this->createPngFile('barang_16x9.png', 1200, 800);

        $response = $this->actingAs($user)->post(route('reports.store'), [
            'judul' => 'Laptop Asus ROG',
            'tipe' => 'hilang',
            'kategori' => 'Laptop',
            'deskripsi' => 'Tertinggal tas laptop di lab komputer.',
            'lokasi' => 'Lab Komputer FMIPA',
            'tanggal_kejadian' => date('Y-m-d'),
            'foto' => $landscapeFile,
        ]);

        $response->assertSessionHasErrors('foto');
        $this->assertDatabaseCount('laporans', 0);
    }

    public function test_user_can_create_report_without_photo(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('reports.store'), [
            'judul' => 'KTM Mahasiswa UNRI',
            'tipe' => 'hilang',
            'kategori' => 'KTM/Dokumen',
            'deskripsi' => 'KTM atas nama Budi hilang di sekitar rektorat.',
            'lokasi' => 'Gedung Rektorat',
            'tanggal_kejadian' => date('Y-m-d'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('reports.index'));

        $laporan = Laporan::first();
        $this->assertNotNull($laporan);
        $this->assertNull($laporan->foto);
    }

    public function test_user_can_update_report_with_valid_4x5_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $laporan = Laporan::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'foto' => null,
        ]);

        $newFile = $this->createPngFile('new_4x5.png', 800, 1000);

        $response = $this->actingAs($user)->patch(route('reports.update', $laporan->id), [
            'judul' => 'Judul Baru Diperbarui',
            'tipe' => $laporan->tipe,
            'kategori' => $laporan->kategori,
            'deskripsi' => $laporan->deskripsi,
            'lokasi' => $laporan->lokasi,
            'tanggal_kejadian' => $laporan->tanggal_kejadian,
            'foto' => $newFile,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('reports.index'));

        $laporan->refresh();
        $this->assertSame('Judul Baru Diperbarui', $laporan->judul);
        $this->assertNotNull($laporan->foto);
        Storage::disk('public')->assertExists($laporan->foto);
    }

    public function test_user_cannot_update_report_with_non_4x5_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $laporan = Laporan::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $invalidFile = $this->createPngFile('square.png', 500, 500);

        $response = $this->actingAs($user)->patch(route('reports.update', $laporan->id), [
            'judul' => 'Judul Gagal',
            'tipe' => $laporan->tipe,
            'kategori' => $laporan->kategori,
            'deskripsi' => $laporan->deskripsi,
            'lokasi' => $laporan->lokasi,
            'tanggal_kejadian' => $laporan->tanggal_kejadian,
            'foto' => $invalidFile,
        ]);

        $response->assertSessionHasErrors('foto');
    }

    public function test_user_keeps_old_photo_when_updating_without_new_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $initialFile = $this->createPngFile('initial_4x5.png', 800, 1000);
        $initialPath = $initialFile->store('laporan_foto', 'public');

        $laporan = Laporan::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'foto' => $initialPath,
        ]);

        $response = $this->actingAs($user)->patch(route('reports.update', $laporan->id), [
            'judul' => 'Judul Diubah Saja',
            'tipe' => $laporan->tipe,
            'kategori' => $laporan->kategori,
            'deskripsi' => $laporan->deskripsi,
            'lokasi' => $laporan->lokasi,
            'tanggal_kejadian' => $laporan->tanggal_kejadian,
        ]);

        $response->assertSessionHasNoErrors();
        $laporan->refresh();

        $this->assertSame('Judul Diubah Saja', $laporan->judul);
        $this->assertSame($initialPath, $laporan->foto);
        Storage::disk('public')->assertExists($initialPath);
    }

    public function test_homepage_renders_card_photos_in_4x5_ratio(): void
    {
        $user = User::factory()->create();
        Laporan::factory()->proses()->create([
            'user_id' => $user->id,
            'tipe' => 'hilang',
            'foto' => 'laporan_foto/sample.jpg',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee('aspect-[4/5]', false);
        $response->assertSee('aspect-ratio: 4 / 5', false);
    }
}

