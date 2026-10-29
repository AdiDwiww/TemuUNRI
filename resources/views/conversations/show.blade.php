<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Chat dengan {{ $lawan->name ?? 'Pengguna dihapus' }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Laporan:
                    @if($conversation->laporan)
                        <a href="{{ route('reports.show', $conversation->laporan) }}" class="text-indigo-600 hover:underline">
                            {{ $conversation->laporan->judul }}
                        </a>
                    @else
                        <span class="italic">Laporan dihapus</span>
                    @endif
                </p>
            </div>
            <a href="{{ route('conversations.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Semua pesan</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden flex flex-col">

                {{-- Daftar pesan --}}
                <div id="chat-box" class="h-[28rem] overflow-y-auto p-4 space-y-3 bg-gray-50">
                    @forelse($messages as $message)
                        @php $mine = $message->sender_id === $user->id; @endphp
                        <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                            <div class="max-w-[75%] rounded-2xl px-4 py-2 text-sm {{ $mine ? 'bg-indigo-600 text-white rounded-br-sm' : 'bg-white border text-gray-800 rounded-bl-sm' }}">
                                <p class="whitespace-pre-line break-words">{{ $message->body }}</p>
                                <p class="text-[11px] mt-1 {{ $mine ? 'text-indigo-200' : 'text-gray-400' }}">{{ $message->created_at->format('d M, H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <p id="chat-empty" class="text-center text-sm text-gray-400 py-10">
                            Belum ada pesan. Mulai percakapan dan cocokkan ciri khusus barang.
                        </p>
                    @endforelse
                </div>

                {{-- Form kirim pesan (tetap jalan tanpa JavaScript) --}}
                <form id="chat-form" method="POST" action="{{ route('messages.store', $conversation) }}" class="border-t p-3 flex gap-2 items-end">
                    @csrf
                    <textarea id="chat-input" name="body" rows="1" maxlength="2000" required
                              placeholder="Tulis pesan... (Enter kirim, Shift+Enter baris baru)"
                              class="flex-1 resize-none rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('body') }}</textarea>
                    <button type="submit" id="chat-send"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-md text-sm font-semibold transition">
                        Kirim
                    </button>
                </form>
                @error('body')
                    <p class="px-4 pb-3 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p id="chat-error" class="hidden px-4 pb-3 text-sm text-red-600"></p>
            </div>

            <p class="text-xs text-gray-400 mt-3">
                Percakapan ini hanya dapat dibaca oleh Anda dan {{ $lawan->name ?? 'lawan bicara' }}.
                Jangan serahkan barang sebelum ciri khusus dan kepemilikan terkonfirmasi.
            </p>
        </div>
    </div>

    <script>
        (function () {
            const box      = document.getElementById('chat-box');
            const form     = document.getElementById('chat-form');
            const input    = document.getElementById('chat-input');
            const sendBtn  = document.getElementById('chat-send');
            const errorEl  = document.getElementById('chat-error');
            const pollUrl  = @json(route('messages.index', $conversation));
            const csrf     = document.querySelector('meta[name="csrf-token"]').content;

            const seen = new Set();
            let lastId = 0;
            box.querySelectorAll('[data-message-id]').forEach(el => {
                const id = parseInt(el.dataset.messageId, 10);
                seen.add(id);
                lastId = Math.max(lastId, id);
            });

            const scrollBottom = () => { box.scrollTop = box.scrollHeight; };
            scrollBottom();

            // Bangun bubble lewat DOM + textContent (aman dari XSS).
            function addMessage(m) {
                if (seen.has(m.id)) return;
                seen.add(m.id);
                lastId = Math.max(lastId, m.id);

                const empty = document.getElementById('chat-empty');
                if (empty) empty.remove();

                const row = document.createElement('div');
                row.className = 'flex ' + (m.mine ? 'justify-end' : 'justify-start');
                row.dataset.messageId = m.id;

                const bubble = document.createElement('div');
                bubble.className = 'max-w-[75%] rounded-2xl px-4 py-2 text-sm ' + (m.mine
                    ? 'bg-indigo-600 text-white rounded-br-sm'
                    : 'bg-white border text-gray-800 rounded-bl-sm');

                const body = document.createElement('p');
                body.className = 'whitespace-pre-line break-words';
                body.textContent = m.body;

                const time = document.createElement('p');
                time.className = 'text-[11px] mt-1 ' + (m.mine ? 'text-indigo-200' : 'text-gray-400');
                time.textContent = m.time;

                bubble.append(body, time);
                row.appendChild(bubble);
                box.appendChild(row);
            }

            // Polling tiap 3 detik.
            async function poll() {
                if (document.hidden) return;
                try {
                    const res = await fetch(pollUrl + '?after=' + lastId, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    const nearBottom = box.scrollHeight - box.scrollTop - box.clientHeight < 80;
                    data.messages.forEach(addMessage);
                    if (data.messages.length && nearBottom) scrollBottom();
                } catch (e) { /* abaikan, coba lagi di putaran berikutnya */ }
            }
            setInterval(poll, 3000);

            // Kirim via fetch (tanpa reload halaman).
            async function send() {
                const text = input.value.trim();
                if (!text) return;

                errorEl.classList.add('hidden');
                sendBtn.disabled = true;

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ body: text }),
                    });

                    if (res.status === 422) {
                        const err = await res.json();
                        throw new Error(err.errors?.body?.[0] || 'Pesan tidak valid.');
                    }
                    if (!res.ok) throw new Error('Gagal mengirim pesan. Coba lagi.');

                    const data = await res.json();
                    addMessage(data.message);
                    input.value = '';
                    scrollBottom();
                } catch (e) {
                    errorEl.textContent = e.message;
                    errorEl.classList.remove('hidden');
                } finally {
                    sendBtn.disabled = false;
                    input.focus();
                }
            }

            form.addEventListener('submit', function (e) { e.preventDefault(); send(); });
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
            });
        })();
    </script>
</x-app-layout>
