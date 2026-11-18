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
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf     = csrfMeta ? csrfMeta.content : '';

            const seen = new Set();
            let lastId = 0;
            box.querySelectorAll('[data-message-id]').forEach(el => {
                const id = parseInt(el.dataset.messageId, 10);
                if (!isNaN(id) && id > 0) {
                    seen.add(id);
                    lastId = Math.max(lastId, id);
                }
            });

            const scrollBottom = () => { box.scrollTop = box.scrollHeight; };
            scrollBottom();

            // Bangun bubble pesan masuk / tersimpan lewat DOM (aman dari XSS)
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

                const metaWrapper = document.createElement('div');
                metaWrapper.className = 'flex items-center justify-end space-x-1.5 mt-1 text-[11px] ' + (m.mine ? 'text-indigo-200' : 'text-gray-400');

                const time = document.createElement('span');
                time.textContent = m.time;
                metaWrapper.appendChild(time);

                if (m.mine) {
                    const status = document.createElement('span');
                    status.className = 'text-emerald-300 font-medium';
                    status.textContent = '✓ Terkirim';
                    metaWrapper.appendChild(status);
                }

                bubble.append(body, metaWrapper);
                row.appendChild(bubble);
                box.appendChild(row);
            }

            // Polling pesan baru setiap 3.5 detik (interval 3-5 detik)
            async function poll() {
                if (document.hidden) return;
                try {
                    const res = await fetch(pollUrl + '?after=' + lastId + '&after_id=' + lastId, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                    });
                    if (!res.ok) return;

                    const data = await res.json();
                    const messages = data.messages || [];
                    const nearBottom = box.scrollHeight - box.scrollTop - box.clientHeight < 100;

                    messages.forEach(addMessage);

                    if (messages.length > 0 && nearBottom) {
                        scrollBottom();
                    }
                } catch (e) {
                    // Polling gagal secara silent agar tidak mengganggu interaksi pengguna
                }
            }
            setInterval(poll, 3500);

            // Kirim pesan teks via Fetch API Asynchronous tanpa reload halaman
            async function send() {
                const text = input.value.trim();
                if (!text) return;

                errorEl.classList.add('hidden');
                sendBtn.disabled = true;

                // 1. Buat bubble pengiriman sementara dengan status "Mengirim..."
                const tempId = 'temp-' + Date.now();
                const empty = document.getElementById('chat-empty');
                if (empty) empty.remove();

                const tempRow = document.createElement('div');
                tempRow.className = 'flex justify-end';
                tempRow.id = tempId;

                const tempBubble = document.createElement('div');
                tempBubble.className = 'max-w-[75%] rounded-2xl px-4 py-2 text-sm bg-indigo-600 text-white rounded-br-sm opacity-90 transition-opacity';

                const tempBody = document.createElement('p');
                tempBody.className = 'whitespace-pre-line break-words';
                tempBody.textContent = text;

                const tempMeta = document.createElement('div');
                tempMeta.className = 'flex items-center justify-end space-x-1.5 mt-1 text-[11px] text-indigo-200';

                const statusSpan = document.createElement('span');
                statusSpan.className = 'flex items-center text-indigo-200 status-indicator';
                statusSpan.innerHTML = '<svg class="animate-spin w-3 h-3 me-1 text-indigo-200" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Mengirim...';

                tempMeta.appendChild(statusSpan);
                tempBubble.append(tempBody, tempMeta);
                tempRow.appendChild(tempBubble);
                box.appendChild(tempRow);

                // Dikosongkan segera dan scroll ke bawah
                const originalText = text;
                input.value = '';
                scrollBottom();

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
                        body: JSON.stringify({ body: originalText }),
                    });

                    if (res.status === 422) {
                        const err = await res.json();
                        throw new Error(err.errors?.body?.[0] || 'Pesan tidak valid.');
                    }
                    if (!res.ok) throw new Error('Gagal mengirim pesan (Status ' + res.status + '). Coba lagi.');

                    const data = await res.json();
                    const messageData = data.message || data;

                    // Tandai pesan sudah terkirim pada bubble
                    tempBubble.classList.remove('opacity-90');
                    tempRow.dataset.messageId = messageData.id;
                    tempRow.removeAttribute('id');

                    seen.add(messageData.id);
                    lastId = Math.max(lastId, messageData.id);

                    statusSpan.className = 'text-emerald-300 font-medium';
                    statusSpan.textContent = '✓ Terkirim';

                    const timeSpan = document.createElement('span');
                    timeSpan.className = 'text-indigo-200 me-1.5';
                    timeSpan.textContent = messageData.time;
                    tempMeta.insertBefore(timeSpan, statusSpan);

                    scrollBottom();
                } catch (e) {
                    // Update status bubble menjadi "Gagal"
                    statusSpan.className = 'text-rose-200 font-semibold';
                    statusSpan.textContent = '✕ Gagal terkirim';

                    // Tampilkan notifikasi error di form
                    errorEl.textContent = e.message || 'Gagal mengirim pesan. Silakan coba lagi.';
                    errorEl.classList.remove('hidden');

                    // Kembalikan teks ke input jika belum diisi teks lain
                    if (!input.value) {
                        input.value = originalText;
                    }
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
