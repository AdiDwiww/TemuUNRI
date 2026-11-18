<x-app-layout>
    @push('styles')
<<<<<<< Updated upstream
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
                            <input type="text" name="search" value="{{ old('search', $search ?? '') }}" placeholder="Cari nama atau barang..." class="w-full bg-slate-100 focus:bg-white text-xs pl-9 pr-4 py-2.5 rounded-lg border border-transparent focus:border-slate-300 focus:outline-none transition">
                        </div>

                        <div class="flex space-x-1 bg-slate-100 p-1 rounded-lg text-xs font-medium text-slate-600">
                            <a href="{{ route('conversations.index') }}" class="flex-1 py-1.5 px-2 rounded-md {{ $tab === 'all' || empty($tab) ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'hover:text-slate-900 text-center' }} text-center">Semua</a>
                            <a href="{{ route('conversations.index', ['tab' => 'unread']) }}" class="flex-1 py-1.5 px-2 rounded-md {{ $tab === 'unread' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'hover:text-slate-900 text-center' }} text-center">Belum Dibaca</a>
                            <a href="{{ route('conversations.index', ['tab' => 'claim']) }}" class="flex-1 py-1.5 px-2 rounded-md {{ $tab === 'claim' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'hover:text-slate-900 text-center' }} text-center">Klaim</a>
                        </div>
                    </form>
                </div>

                <div class="chat-scroll divide-y divide-slate-100 overflow-y-auto flex-1 max-h-[520px]">
                    @if($conversations->isEmpty())
                        <div class="p-8 text-center text-sm text-slate-500">
                            <p class="font-medium text-slate-700 mb-2">Belum ada percakapan.</p>
                            <p>Buka detail laporan lalu pilih <span class="font-semibold">Chat Pelapor</span> untuk memulai.</p>
                            <a href="{{ route('reports.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">Lihat laporan</a>
                        </div>
                    @else
                        @foreach($conversations as $conversation)
                            @php
                                $lawan = $conversation->otherUser($user);
                                $terakhir = $conversation->latestMessage;
                                $isUnread = (int) ($conversation->unread_count ?? 0) > 0;
                                $namaLawan = $lawan?->name ?? 'Pengguna dihapus';
                                $judulLaporan = $conversation->laporan?->judul ?? 'Laporan dihapus';
                            @endphp

                            <a href="{{ route('conversations.show', $conversation) }}"
                               class="block p-4 hover:bg-slate-100/70 transition {{ request()->route('conversation') && request()->route('conversation')->id === $conversation->id ? 'bg-blue-50/60 border-l-4 border-indigo-600' : '' }}">
                                <div class="flex items-start justify-between mb-1">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <div class="relative">
                                            <div class="w-10 h-10 rounded-full {{ $lawan && $lawan->isAdmin() ? 'bg-rose-600' : 'bg-indigo-600' }} text-white font-bold flex items-center justify-center text-sm shadow-xs">
                                                {{ strtoupper(substr($namaLawan, 0, 1)) }}
                                            </div>
                                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <h4 class="font-bold text-slate-900 text-sm truncate">{{ $namaLawan }}</h4>
                                                @if($isUnread)
                                                    <span class="bg-indigo-600 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">{{ $conversation->unread_count }} baru</span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] font-medium text-slate-500 mt-0.5 line-clamp-1">
                                                <i class="fa-solid {{ $conversation->laporan?->tipe === 'penemuan' ? 'fa-id-card' : 'fa-tag' }} text-[9px] mr-1 {{ $conversation->laporan?->tipe === 'penemuan' ? 'text-emerald-500' : 'text-indigo-500' }}"></i>
                                                {{ $conversation->laporan?->tipe === 'penemuan' ? 'Klaim' : 'Laporan' }}: {{ $judulLaporan }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">
                                        {{ $terakhir?->created_at ? $terakhir->created_at->diffForHumans() : $conversation->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-700 font-medium line-clamp-1 pl-13 {{ $isUnread ? 'text-slate-800' : 'text-slate-500' }}">
                                    @if($terakhir)
                                        @if($terakhir->sender_id === $user->id)
                                            <span class="text-slate-400">Anda: </span>
                                        @endif
                                        {{ \Illuminate\Support\Str::limit($terakhir->body, 90) }}
                                    @else
                                        <span class="italic text-slate-400">Belum ada pesan</span>
                                    @endif
                                </p>
                            </a>
                        @endforeach
                    @endif
                </div>
            </aside>

            <section class="flex-1 flex flex-col bg-white">
                @if(! $selectedConversation)
                    <div class="flex-1 flex items-center justify-center text-center p-10 text-slate-500">
                        <div>
                            <p class="font-semibold text-slate-700 mb-2">Belum ada percakapan</p>
                            <p class="text-sm">Buka detail laporan lalu mulai chat untuk melihat pesan di sini.</p>
                        </div>
                    </div>
                @else
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
                                    <span>Terkait: <strong>{{ $selectedConversation->laporan?->judul ?? 'Laporan dihapus' }}</strong></span>
                                    <span>•</span>
                                    <span class="text-indigo-600 font-medium">ID Ref: #LPR-{{ str_pad($selectedConversation->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('reports.show', $selectedConversation->laporan) }}" class="px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition flex items-center space-x-1.5">
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
                        @if($selectedMessages->isEmpty())
                            <div id="chat-empty" class="flex h-full items-center justify-center text-center text-sm text-slate-400 py-10">
                                Belum ada pesan. Mulai percakapan dan cocokkan ciri khusus barang.
                            </div>
                        @else
                            @foreach($selectedMessages as $message)
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
                        <form id="chatForm" method="POST" action="{{ route('messages.store', $selectedConversation) }}" class="flex items-center space-x-2">
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
                @endif
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

            if (!box || !form || !input || !sendBtn || !errorEl) {
                return;
            }

            const pollUrl = @json(route('messages.index', $selectedConversation ?? 0));
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
                    input.style.height = 'auto';
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
=======
    <style>
        /* Custom scrollbar for conversation list and chat */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 9999px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #818cf8; }
        /* Full-height layout below the navbar */
        .chat-layout { height: calc(100vh - 64px); }
        /* Hide footer on chat pages */
        body > div > footer { display: none !important; }
    </style>
    @endpush

    <div class="chat-layout flex overflow-hidden bg-[#f1f5f9]">

        {{-- ════════════════════════════════════
             LEFT PANEL — Daftar Percakapan
        ════════════════════════════════════ --}}
        <aside class="w-80 xl:w-96 flex-shrink-0 bg-white border-r border-gray-200 flex flex-col">

            {{-- Header Panel --}}
            <div class="px-5 pt-5 pb-4 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-lg font-bold text-gray-900">Pesan</h1>
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold">
                        {{ $conversations->total() }}
                    </span>
                </div>

                {{-- Search box (client-side) --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                    </div>
                    <input id="search-conv" type="text" placeholder="Cari percakapan..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                </div>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mx-4 mt-3 px-3 py-2 bg-green-50 border border-green-200 text-green-700 text-xs rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-4 mt-3 px-3 py-2 bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Conversation list --}}
            <ul id="conv-list" class="flex-1 overflow-y-auto custom-scroll divide-y divide-gray-50">
                @forelse($conversations as $conv)
                    @php
                        $lawan   = $conv->otherUser($user);
                        $last    = $conv->latestMessage;
                        $unread  = $conv->unread_count;
                        $initials = strtoupper(mb_substr($lawan->name ?? '?', 0, 2));
                        $colors  = ['bg-indigo-500','bg-purple-500','bg-pink-500','bg-teal-500','bg-orange-500','bg-cyan-500'];
                        $color   = $colors[$conv->id % count($colors)];
                    @endphp
                    <li class="conv-item" data-name="{{ strtolower($lawan->name ?? '') }}" data-judul="{{ strtolower($conv->laporan->judul ?? '') }}">
                        <a href="{{ route('conversations.show', $conv) }}"
                           class="flex items-center gap-3 px-4 py-3.5 hover:bg-blue-50/60 transition group">

                            {{-- Avatar --}}
                            <div class="relative flex-shrink-0">
                                <div class="w-11 h-11 rounded-full {{ $color }} flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                    {{ $initials }}
                                </div>
                                @if($unread > 0)
                                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full bg-indigo-600 border-2 border-white"></span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1 mb-0.5">
                                    <span class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $lawan->name ?? 'Pengguna dihapus' }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 flex-shrink-0">
                                        {{ ($last?->created_at ?? $conv->created_at)->diffForHumans(null, true) }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-indigo-500 truncate font-medium mb-0.5">
                                    📌 {{ \Illuminate\Support\Str::limit($conv->laporan->judul ?? 'Laporan dihapus', 35) }}
                                </p>
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-xs truncate {{ $unread > 0 ? 'text-gray-900 font-medium' : 'text-gray-500' }}">
                                        @if($last)
                                            @if($last->sender_id === $user->id)
                                                <span class="text-gray-400">Anda: </span>
                                            @endif
                                            {{ \Illuminate\Support\Str::limit($last->body, 50) }}
                                        @else
                                            <span class="italic text-gray-400">Belum ada pesan</span>
                                        @endif
                                    </p>
                                    @if($unread > 0)
                                        <span class="flex-shrink-0 min-w-[20px] h-5 px-1.5 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center">
                                            {{ $unread > 9 ? '9+' : $unread }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                @empty
                    <li id="conv-empty" class="p-8 text-center text-sm text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.97-4.03 9-9 9a9.08 9.08 0 01-4.49-1.19L3 21l1.19-4.51A9 9 0 1121 12z"/>
                        </svg>
                        <p class="font-medium text-gray-700 mb-1">Belum ada percakapan</p>
                        <p class="text-xs text-gray-400 mb-3">Buka detail laporan lalu tekan <strong>Chat Pelapor</strong> untuk memulai.</p>
                        <a href="{{ route('reports.index') }}"
                           class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                            Lihat Laporan →
                        </a>
                    </li>
                @endforelse
                {{-- "not found" placeholder for search --}}
                <li id="no-result" class="hidden p-8 text-center text-sm text-gray-400">
                    Tidak ada percakapan yang cocok.
                </li>
            </ul>

            {{-- Pagination --}}
            @if($conversations->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 text-xs">
                    {{ $conversations->links() }}
                </div>
            @endif
        </aside>

        {{-- ════════════════════════════════════
             RIGHT PANEL — Welcome / Pilih chat
        ════════════════════════════════════ --}}
        <main class="flex-1 flex flex-col items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-purple-50">
            <div class="text-center max-w-sm px-6">
                <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-indigo-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.97-4.03 9-9 9a9.08 9.08 0 01-4.49-1.19L3 21l1.19-4.51A9 9 0 1121 12z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Pesan Anda</h2>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Pilih percakapan di sebelah kiri untuk membuka chat, atau mulai dari detail laporan.
                </p>
                @if($conversations->isEmpty())
                    <a href="{{ route('reports.index') }}"
                       class="mt-5 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Mulai dari Laporan
                    </a>
                @endif
            </div>
        </main>

    </div>

    @push('scripts')
    <script>
        // Client-side search filter
        const searchInput = document.getElementById('search-conv');
        const convItems   = document.querySelectorAll('.conv-item');
        const noResult    = document.getElementById('no-result');

        searchInput?.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            let visible = 0;
            convItems.forEach(function (li) {
                const name  = li.dataset.name  || '';
                const judul = li.dataset.judul || '';
                const match = !q || name.includes(q) || judul.includes(q);
                li.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (noResult) {
                noResult.classList.toggle('hidden', visible > 0 || q === '');
            }
        });
    </script>
    @endpush
>>>>>>> Stashed changes
</x-app-layout>
