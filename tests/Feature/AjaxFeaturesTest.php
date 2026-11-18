<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Laporan;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AjaxFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fitur 1: AJAX Live Search mengembalikan JSON dengan partial HTML kartu laporan.
     */
    public function test_ajax_live_search_returns_json_and_html(): void
    {
        $user = User::factory()->create();

        $laporan1 = Laporan::factory()->proses()->create([
            'judul' => 'Laptop Asus ROG Hilang di Perpustakaan',
            'lokasi' => 'Perpustakaan Pusat',
        ]);

        $laporan2 = Laporan::factory()->proses()->create([
            'judul' => 'Botol Minum Corkcicle Hijau di FMIPA',
            'lokasi' => 'Gedung FMIPA',
        ]);

        $response = $this->actingAs($user)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ])
            ->get(route('reports.index', ['search' => 'Asus ROG']));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'success',
            'message',
            'total',
            'html',
            'data' => [
                'total',
                'current_page',
                'last_page',
                'per_page',
            ],
        ]);

        $this->assertEquals(1, $response->json('total'));
        $this->assertStringContainsString('Laptop Asus ROG Hilang', $response->json('html'));
        $this->assertStringNotContainsString('Botol Minum Corkcicle', $response->json('html'));
    }

    /**
     * Fitur 2: Filter Dinamis berdasarkan tipe dan kategori via AJAX.
     */
    public function test_ajax_filter_tipe_dan_kategori(): void
    {
        $user = User::factory()->create();

        $laporanHilang = Laporan::factory()->proses()->create([
            'judul'    => 'Kunci Motor Beat',
            'tipe'     => 'hilang',
            'kategori' => 'Kendaraan',
        ]);

        $laporanPenemuan = Laporan::factory()->proses()->create([
            'judul'    => 'Dompet Kulit Cokelat',
            'tipe'     => 'penemuan',
            'kategori' => 'Dompet',
        ]);

        $response = $this->actingAs($user)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ])
            ->get(route('reports.index', [
                'tipe'     => 'penemuan',
                'kategori' => 'Dompet',
            ]));

        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('total'));
        $this->assertStringContainsString('Dompet Kulit Cokelat', $response->json('html'));
        $this->assertStringNotContainsString('Kunci Motor Beat', $response->json('html'));
    }

    /**
     * Live Search & Filter yang tidak menemukan hasil menampilkan empty state.
     */
    public function test_ajax_search_empty_state(): void
    {
        $user = User::factory()->create();

        Laporan::factory()->proses()->create([
            'judul' => 'Jas Laboratorium Kimia',
        ]);

        $response = $this->actingAs($user)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ])
            ->get(route('reports.index', ['search' => 'KataKunciYangPastiTidakAda999']));

        $response->assertStatus(200);
        $this->assertEquals(0, $response->json('total'));
        $this->assertStringContainsString('Tidak Ada Laporan yang Sesuai', $response->json('html'));
    }

    /**
     * Pagination bekerja secara asynchronous via AJAX.
     */
    public function test_ajax_pagination(): void
    {
        $user = User::factory()->create();

        // Buat 15 laporan (per_page = 10, total halaman = 2)
        Laporan::factory()->count(15)->proses()->create();

        $response = $this->actingAs($user)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ])
            ->get(route('reports.index', ['page' => 2]));

        $response->assertStatus(200);
        $this->assertEquals(15, $response->json('total'));
        $this->assertEquals(2, $response->json('data.current_page'));
        $this->assertEquals(2, $response->json('data.last_page'));
    }

    /**
     * Permintaan standar tanpa header AJAX tetap mengembalikan view Blade lengkap (Progressive Enhancement).
     */
    public function test_non_ajax_request_returns_regular_blade_view(): void
    {
        $user = User::factory()->create();
        Laporan::factory()->proses()->create(['judul' => 'Helm KYT Biru']);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.index');
        $response->assertViewHas(['laporans', 'barangHilang', 'barangPenemuan', 'laporanSelesai']);
        $response->assertSee('Helm KYT Biru');
    }

    /**
     * Fitur 3: Chat Asynchronous - Pengiriman pesan melalui JSON API.
     */
    public function test_ajax_chat_kirim_pesan_sukses(): void
    {
        $reporter = User::factory()->create();
        $participant = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $reporter->id,
        ]);

        $conversation = Conversation::create([
            'laporan_id'     => $laporan->id,
            'reporter_id'    => $reporter->id,
            'participant_id' => $participant->id,
        ]);

        $response = $this->actingAs($participant)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ])
            ->postJson(route('messages.store', $conversation), [
                'body' => 'Halo pelapor, apakah kunci motor ini masih ada?',
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'success',
            'message' => [
                'id',
                'body',
                'mine',
                'time',
            ],
        ]);

        $this->assertEquals('Halo pelapor, apakah kunci motor ini masih ada?', $response->json('message.body'));
        $this->assertTrue($response->json('message.mine'));

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id'       => $participant->id,
            'body'            => 'Halo pelapor, apakah kunci motor ini masih ada?',
        ]);
    }

    /**
     * Chat Asynchronous - Validasi jika body pesan kosong mengembalikan 422 JSON.
     */
    public function test_ajax_chat_kirim_pesan_validasi_gagal(): void
    {
        $reporter = User::factory()->create();
        $participant = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $reporter->id,
        ]);

        $conversation = Conversation::create([
            'laporan_id'     => $laporan->id,
            'reporter_id'    => $reporter->id,
            'participant_id' => $participant->id,
        ]);

        $response = $this->actingAs($participant)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ])
            ->postJson(route('messages.store', $conversation), [
                'body' => '',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['body']);
    }

    /**
     * Chat Asynchronous - Pengguna bukan peserta ditolak dengan 403 Forbidden.
     */
    public function test_ajax_chat_bukan_peserta_ditolak(): void
    {
        $reporter = User::factory()->create();
        $participant = User::factory()->create();
        $stranger = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $reporter->id,
        ]);

        $conversation = Conversation::create([
            'laporan_id'     => $laporan->id,
            'reporter_id'    => $reporter->id,
            'participant_id' => $participant->id,
        ]);

        $response = $this->actingAs($stranger)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ])
            ->postJson(route('messages.store', $conversation), [
                'body' => 'Pesan dari penyusup',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Fitur 3: Chat Polling - Mengambil hanya pesan baru setelah ID tertentu.
     */
    public function test_ajax_chat_polling_pesan_baru(): void
    {
        $reporter = User::factory()->create();
        $participant = User::factory()->create();

        $laporan = Laporan::factory()->proses()->create([
            'user_id' => $reporter->id,
        ]);

        $conversation = Conversation::create([
            'laporan_id'     => $laporan->id,
            'reporter_id'    => $reporter->id,
            'participant_id' => $participant->id,
        ]);

        $msg1 = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $reporter->id,
            'body'            => 'Pesan pertama',
        ]);

        $msg2 = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $reporter->id,
            'body'            => 'Pesan kedua baru saja tiba',
        ]);

        // Polling dengan ?after={msg1->id} atau ?after_id={msg1->id}
        $response = $this->actingAs($participant)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ])
            ->get(route('messages.index', [
                'conversation' => $conversation->id,
                'after_id'     => $msg1->id,
            ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'success',
            'messages',
        ]);

        $messages = $response->json('messages');
        $this->assertCount(1, $messages);
        $this->assertEquals($msg2->id, $messages[0]['id']);
        $this->assertEquals('Pesan kedua baru saja tiba', $messages[0]['body']);
        $this->assertFalse($messages[0]['mine']); // dikirim oleh lawan bicara

        // Pesan masuk otomatis ditandai sebagai terbaca
        $this->assertNotNull($msg2->fresh()->read_at);
    }
}
