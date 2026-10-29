<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Laporan;

class ConversationController extends Controller
{
    /**
     * Inbox: semua percakapan milik user (sebagai pelapor atau penanya).
     */
    public function index()
    {
        $user = auth()->user();

        $conversations = Conversation::query()
            ->where(function ($q) use ($user) {
                $q->where('reporter_id', $user->id)
                  ->orWhere('participant_id', $user->id);
            })
            ->with(['laporan', 'reporter', 'participant', 'latestMessage'])
            ->withCount([
                'messages as unread_count' => function ($q) use ($user) {
                    $q->whereNull('read_at')->where('sender_id', '!=', $user->id);
                },
            ])
            ->latest('updated_at')
            ->paginate(15);

        return view('conversations.index', compact('conversations', 'user'));
    }

    /**
     * Tombol "Chat Pelapor": buka percakapan yang sudah ada, atau buat baru.
     */
    public function store(Laporan $report)
    {
        $user = auth()->user();

        if ((int) $report->user_id === (int) $user->id) {
            return redirect()
                ->route('reports.show', $report)
                ->with('error', 'Anda tidak dapat memulai chat pada laporan milik sendiri.');
        }

        if ($report->status !== 'proses') {
            return redirect()
                ->route('reports.show', $report)
                ->with('error', 'Chat hanya tersedia untuk laporan yang sudah disetujui admin.');
        }

        // Cegah duplikat: pasangan (laporan + pemilik laporan + penanya) hanya satu.
        $conversation = Conversation::firstOrCreate([
            'laporan_id'     => $report->id,
            'reporter_id'    => $report->user_id,
            'participant_id' => $user->id,
        ]);

        return redirect()->route('conversations.show', $conversation);
    }

    /**
     * Halaman chat. Hanya reporter & participant.
     */
    public function show(Conversation $conversation)
    {
        $user = auth()->user();

        abort_unless($conversation->hasParticipant($user), 403, 'Anda bukan peserta percakapan ini.');

        $conversation->load(['laporan', 'reporter', 'participant']);

        // Tandai pesan dari lawan bicara sebagai sudah dibaca.
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()->oldest('id')->get();
        $lawan = $conversation->otherUser($user);

        return view('conversations.show', compact('conversation', 'messages', 'lawan', 'user'));
    }
}
