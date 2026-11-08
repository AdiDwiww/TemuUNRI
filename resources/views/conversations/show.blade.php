<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <style>
            .chat-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
            .chat-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
            .chat-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
            .chat-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
            .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        </style>
    @endpush

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full flex-grow">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pesan</h1>
                <p class="text-sm text-slate-500">Kelola percakapan penemuan &amp; klaim barang di Universitas Riau.</p>
            </div>

            <div class="flex items-center space-x-2 bg-white px-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-600 self-start">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Layanan Pesan Aktif</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden min-h-[620px] flex flex-col md:flex-row">
            <aside class="w-full md:w-80 lg:w-96 border-b md:border-b-0 md:border-r border-slate-200 flex flex-col bg-slate-50/50">
                <div class="p-4 bg-white border-b border-slate-100">
                    <form method="GET" action="{{ route('conversations.index') }}" class="space-y-3">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau barang..." class="w-full bg-slate-100 focus:bg-white text-xs pl-9 pr-4 py-2.5 rounded-lg border border-transparent focus:border-slate-300 focus:outline-none transition">
                        </div>

                        <div class="flex space-x-1 bg-slate-100 p-1 rounded-lg text-xs font-medium text-slate-600">
                            <a href="{{ route('conversations.index') }}" class="flex-1 py-1.5 px-2 rounded-md {{ request('tab', 'all') === 'all' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'hover:text-slate-900 text-center' }} text-center">Semua</a>
                            <a href="{{ route('conversations.index', ['tab' => 'unread']) }}" class="flex-1 py-1.5 px-2 rounded-md {{ request('tab') === 'unread' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'hover:text-slate-900 text-center' }} text-center">Belum Dibaca</a>
                            <a href="{{ route('conversations.index', ['tab' => 'claim']) }}" class="flex-1 py-1.5 px-2 rounded-md {{ request('tab') === 'claim' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'hover:text-slate-900 text-center' }} text-center">Klaim</a>
                        </div>
                    </form>
                </div>

                <div class="chat-scroll divide-y divide-slate-100 overflow-y-auto flex-1 max-h-[520px]">
                    @forelse($allConversations as $item)
                        @php
                            $itemLawan = $item->otherUser($user);
                            $itemTerakhir = $item->latestMessage;
                            $itemUnread = (int) ($item->unread_count ?? 0) > 0;
                            $itemNama = $itemLawan?->name ?? 'Pengguna dihapus';
                            $itemJudul = $item->laporan?->judul ?? 'Laporan dihapus';
                        @endphp

                        <a href="{{ route('conversations.show', $item) }}" class="block p-4 hover:bg-slate-100/70 transition {{ $item->id === $conversation->id ? 'bg-blue-50/60 border-l-4 border-indigo-600' : '' }}">
                            <div class="flex items-start justify-between mb-1">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="relative">
                                        <div class="w-10 h-10 rounded-full {{ $itemLawan && $itemLawan->isAdmin() ? 'bg-rose-600' : 'bg-indigo-600' }} text-white font-bold flex items-center justify-center text-sm shadow-xs">
                                            {{ strtoupper(substr($itemNama, 0, 1)) }}
                                        </div>
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="font-bold text-slate-900 text-sm truncate">{{ $itemNama }}</h4>
                                            @if($itemUnread)
                                                <span class="bg-indigo-600 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">{{ $item->unread_count }} baru</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] font-medium text-slate-500 mt-0.5 line-clamp-1">
                                            <i class="fa-solid {{ $item->laporan?->tipe === 'penemuan' ? 'fa-id-card' : 'fa-tag' }} text-[9px] mr-1 {{ $item->laporan?->tipe === 'penemuan' ? 'text-emerald-500' : 'text-indigo-500' }}"></i>
                                            {{ $item->laporan?->tipe === 'penemuan' ? 'Klaim' : 'Laporan' }}: {{ $itemJudul }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">
                                    {{ $itemTerakhir?->created_at ? $itemTerakhir->created_at->diffForHumans() : $item->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-700 font-medium line-clamp-1 pl-13 {{ $itemUnread ? 'text-slate-800' : 'text-slate-500' }}">
                                @if($itemTerakhir)
                                    @if($itemTerakhir->sender_id === $user->id)
                                        <span class="text-slate-400">Anda: </span>
                                    @endif
                                    {{ \Illuminate\Support\Str::limit($itemTerakhir->body, 90) }}
                                @else
                                    <span class="italic text-slate-400">Belum ada pesan</span>
                                @endif
                            </p>
                        </a>
                    @empty
                        <div class="p-8 text-center text-sm text-slate-500">
                            <p class="font-medium text-slate-700 mb-2">Belum ada percakapan.</p>
                            <p>Buka detail laporan lalu pilih <span class="font-semibold">Chat Pelapor</span> untuk memulai.</p>
                        </div>
                    @endforelse
                </div>
            </aside>

            <section class="flex-1 flex flex-col bg-white">
                <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-sm">
                            {{ strtoupper(substr(($lawan?->name ?? 'Pengguna'), 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h3 class="font-bold text-slate-900 text-base">{{ $lawan?->name ?? 'Pengguna dihapus' }}</h3>
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-semibold px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                <span>Terkait: <strong>{{ $conversation->laporan?->judul ?? 'Laporan dihapus' }}</strong></span>
                                <span>•</span>
                                <span class="text-indigo-600 font-medium">ID Ref: #LPR-{{ str_pad($conversation->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <a href="{{ route('reports.show', $conversation->laporan) }}" class="px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-circle-info text-slate-500"></i>
                            <span>Detail Barang</span>
                        </a>
                        <button type="button" class="px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>Selesaikan Chat</span>
                        </button>
                    </div>
                </div>

                <div class="bg-amber-50 border-b border-amber-100 px-4 py-2 text-xs text-amber-800 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-shield-halved text-amber-600"></i>
                        <span>Pastikan Anda memverifikasi identitas pemilik sebelum menyerahkan barang di Kampus UNRI.</span>
                    </div>
                </div>

                <div id="chatMessages" class="chat-scroll flex-1 p-4 md:p-6 overflow-y-auto space-y-4 bg-slate-50/30 max-h-[420px]">
                    @if($messages->isEmpty())
                        <div id="chat-empty" class="flex h-full items-center justify-center text-center text-sm text-slate-400 py-10">
                            Belum ada pesan. Mulai percakapan dan cocokkan ciri khusus barang.
                        </div>
                    @else
                        @foreach($messages as $message)
                            @php $mine = (int) $message->sender_id === (int) $user->id; @endphp
                            <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                                <div class="max-w-[75%] {{ $mine ? 'text-right' : 'text-left' }}">
                                    <div class="{{ $mine ? 'bg-[#1e293b] text-white rounded-2xl rounded-tr-none' : 'bg-white border border-slate-200 rounded-2xl rounded-tl-none' }} p-3.5 shadow-xs text-xs space-y-1">
                                        @if(!$mine)
                                            <p class="font-semibold text-indigo-600 text-[11px] mb-1">{{ $lawan?->name ?? 'Pengguna' }}</p>
                                        @endif
                                        <p class="whitespace-pre-line break-words">{{ $message->body }}</p>
                                    </div>
                                    <div class="{{ $mine ? 'flex items-center justify-end space-x-1' : 'flex items-center justify-start space-x-1' }} text-[10px] text-slate-400 mt-1">
                                        <span>{{ $message->created_at->format('h:i A') }}</span>
                                        @if($mine)
                                            <i class="fa-solid fa-check{{ $message->read_at ? '-double text-blue-500' : ' text-slate-400' }}"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="p-3 md:p-4 border-t border-slate-200 bg-white">
                    <form id="chatForm" method="POST" action="{{ route('messages.store', $conversation) }}" class="flex items-center space-x-2">
                        @csrf
                        <button type="button" class="p-2.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition" title="Lampirkan Gambar/Bukti">
                            <i class="fa-solid fa-paperclip text-sm"></i>
                        </button>
                        <button type="button" class="p-2.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition" title="Pilih Emoji">
                            <i class="fa-regular fa-face-smile text-sm"></i>
                        </button>

                        <textarea id="chatInput" name="body" rows="1" maxlength="2000" required placeholder="Ketik pesan balasan untuk {{ $lawan?->name ?? 'pengguna' }}..." class="flex-1 resize-none bg-slate-100 focus:bg-white text-xs md:text-sm px-4 py-2.5 rounded-lg border border-transparent focus:border-slate-300 focus:outline-none transition"></textarea>

                        <button type="submit" id="chatSend" class="bg-[#1e293b] hover:bg-slate-800 text-white px-4 py-2.5 rounded-lg text-xs font-semibold shadow-xs transition flex items-center space-x-1.5">
                            <span>Kirim</span>
                            <i class="fa-solid fa-paper-plane text-[10px]"></i>
                        </button>
                    </form>
                    @error('body')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p id="chatError" class="hidden mt-2 text-sm text-red-600"></p>
                </div>
            </section>
        </div>
    </main>

    <script>
        (function () {
            const box = document.getElementById('chatMessages');
            const form = document.getElementById('chatForm');
            const input = document.getElementById('chatInput');
            const sendBtn = document.getElementById('chatSend');
            const errorEl = document.getElementById('chatError');
            const pollUrl = @json(route('messages.index', $conversation));
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            const seen = new Set();
            let lastId = 0;
            box.querySelectorAll('[data-message-id]').forEach((el) => {
                const id = Number(el.dataset.messageId);
                seen.add(id);
                lastId = Math.max(lastId, id);
            });

            const scrollBottom = () => {
                box.scrollTop = box.scrollHeight;
            };
            scrollBottom();

            function addMessage(message) {
                if (seen.has(message.id)) {
                    return;
                }

                seen.add(message.id);
                lastId = Math.max(lastId, message.id);

                const empty = document.getElementById('chat-empty');
                if (empty) {
                    empty.remove();
                }

                const row = document.createElement('div');
                row.className = 'flex ' + (message.mine ? 'justify-end' : 'justify-start');
                row.dataset.messageId = message.id;

                const wrap = document.createElement('div');
                wrap.className = 'max-w-[75%] ' + (message.mine ? 'text-right' : 'text-left');

                const bubble = document.createElement('div');
                bubble.className = (message.mine ? 'bg-[#1e293b] text-white rounded-2xl rounded-tr-none' : 'bg-white border border-slate-200 rounded-2xl rounded-tl-none') + ' p-3.5 shadow-xs text-xs space-y-1';

                if (!message.mine) {
                    const from = document.createElement('p');
                    from.className = 'font-semibold text-indigo-600 text-[11px] mb-1';
                    from.textContent = '{{ $lawan?->name ?? 'Pengguna' }}';
                    bubble.appendChild(from);
                }

                const body = document.createElement('p');
                body.className = 'whitespace-pre-line break-words';
                body.textContent = message.body;
                bubble.appendChild(body);

                const meta = document.createElement('div');
                meta.className = (message.mine ? 'flex items-center justify-end space-x-1' : 'flex items-center justify-start space-x-1') + ' text-[10px] text-slate-400 mt-1';
                const time = document.createElement('span');
                time.textContent = message.time;
                meta.appendChild(time);

                if (message.mine) {
                    const icon = document.createElement('i');
                    icon.className = 'fa-solid fa-check text-slate-400';
                    meta.appendChild(icon);
                }

                wrap.appendChild(bubble);
                wrap.appendChild(meta);
                row.appendChild(wrap);
                box.appendChild(row);
            }

            async function poll() {
                if (document.hidden) {
                    return;
                }

                try {
                    const response = await fetch(pollUrl + '?after=' + lastId, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                    });

                    if (!response.ok) {
                        return;
                    }

                    const data = await response.json();
                    const nearBottom = box.scrollHeight - box.scrollTop - box.clientHeight < 80;
                    data.messages.forEach(addMessage);
                    if (data.messages.length && nearBottom) {
                        scrollBottom();
                    }
                } catch (error) {
                    // silent retry, no UI interruption
                }
            }

            async function sendMessage() {
                const text = input.value.trim();
                if (!text) {
                    return;
                }

                errorEl.classList.add('hidden');
                sendBtn.disabled = true;

                try {
                    const response = await fetch(form.action, {
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

                    if (response.status === 422) {
                        const payload = await response.json();
                        throw new Error(payload.errors?.body?.[0] || 'Pesan tidak valid.');
                    }

                    if (!response.ok) {
                        throw new Error('Gagal mengirim pesan. Coba lagi.');
                    }

                    const data = await response.json();
                    addMessage(data.message);
                    input.value = '';
                    scrollBottom();
                } catch (error) {
                    errorEl.textContent = error.message;
                    errorEl.classList.remove('hidden');
                } finally {
                    sendBtn.disabled = false;
                    input.focus();
                }
            }

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                sendMessage();
            });

            input.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();
                    sendMessage();
                }
            });

            setInterval(poll, 3000);
        })();
    </script>
</x-app-layout>
