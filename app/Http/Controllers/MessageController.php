<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Polling: ambil pesan baru (id > ?after) dalam bentuk JSON.
     */
    public function index(Request $request, Conversation $conversation)
    {
        $user = auth()->user();

        abort_unless($conversation->hasParticipant($user), 403, 'Anda bukan peserta percakapan ini.');

        $after = (int) $request->query('after', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $after)
            ->oldest('id')
            ->get();

        // Pesan masuk dari lawan bicara otomatis dianggap terbaca.
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => $messages->map(fn (Message $m) => $this->format($m, $user->id))->values(),
        ]);
    }

    /**
     * Kirim pesan teks.
     */
    public function store(Request $request, Conversation $conversation)
    {
        $user = auth()->user();

        abort_unless($conversation->hasParticipant($user), 403, 'Anda bukan peserta percakapan ini.');

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body'      => trim($validated['body']),
        ]);

        // Naikkan percakapan ke atas inbox.
        $conversation->touch();

        if ($request->expectsJson()) {
            return response()->json(['message' => $this->format($message, $user->id)], 201);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    private function format(Message $m, int $currentUserId): array
    {
        return [
            'id'   => $m->id,
            'body' => $m->body,
            'mine' => (int) $m->sender_id === $currentUserId,
            'time' => $m->created_at->format('d M, H:i'),
        ];
    }
}
