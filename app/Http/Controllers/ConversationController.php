<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    /**
     * Inbox: semua percakapan milik user (sebagai pelapor atau penanya).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = trim((string) $request->query('search', ''));
        $tab = (string) $request->query('tab', 'all');

        $query = Conversation::query()
            ->where(function ($q) use ($user) {
                $q->where('reporter_id', $user->id)
                  ->orWhere('participant_id', $user->id);
            })
            ->with(['laporan', 'reporter', 'participant', 'latestMessage'])
            ->withCount([
                'messages as unread_count' => function ($q) use ($user) {
                    $q->whereNull('read_at')->where('sender_id', '!=', $user->id);
                },
            ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('laporan', function ($laporanQuery) use ($search) {
                        $laporanQuery->where('judul', 'like', "%{$search}%");
                    })
                    ->orWhereHas('reporter', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('participant', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($tab === 'unread') {
            $query->whereHas('messages', function ($messageQuery) use ($user) {
                $messageQuery->whereNull('read_at')->where('sender_id', '!=', $user->id);
            });
        }

        if ($tab === 'claim') {
            $query->whereHas('laporan', function ($laporanQuery) {
                $laporanQuery->where('tipe', 'penemuan');
            });
        }

        $conversations = $query->latest('updated_at')->paginate(15);
        $selectedConversation = $conversations->first();
        $selectedMessages = collect();
        $lawan = null;

        if ($selectedConversation) {
            $selectedConversation->load(['laporan', 'reporter', 'participant']);
            $selectedMessages = $selectedConversation->messages()->oldest('id')->get();
            $lawan = $selectedConversation->otherUser($user);
        }

        return view('conversations.index', compact('conversations', 'user', 'search', 'tab', 'selectedConversation', 'selectedMessages', 'lawan'));
    }

    /**
     * Tombol "Chat Pelapor": buka percakapan yang sudah ada, atau buat baru.
     */
    public function store(Laporan $report)
    {
        $user = Auth::user();

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
        $user = Auth::user();

        abort_unless($conversation->hasParticipant($user), 403, 'Anda bukan peserta percakapan ini.');

        $conversation->load(['laporan', 'reporter', 'participant']);

        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()->oldest('id')->get();
        $lawan = $conversation->otherUser($user);

        $allConversations = Conversation::query()
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
            ->get();

        return view('conversations.show', compact('conversation', 'messages', 'lawan', 'user', 'allConversations'));
    }
}
