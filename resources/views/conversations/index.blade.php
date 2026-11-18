<x-app-layout>
    @push('styles')
    <style>
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 9999px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #818cf8; }
        .chat-layout { height: calc(100vh - 64px); }
        body > div > footer { display: none !important; }
    </style>
    @endpush

    <div class="chat-layout flex overflow-hidden bg-[#f1f5f9]">

        {{-- ══════════════════════════════
             LEFT PANEL — Daftar Percakapan
        ══════════════════════════════ --}}
        <aside class="w-80 xl:w-96 flex-shrink-0 bg-white border-r border-gray-200 flex flex-col">

            {{-- Header --}}
            <div class="px-5 pt-5 pb-4 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-lg font-bold text-gray-900">Pesan</h1>
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold">
                        {{ $conversations->total() }}
                    </span>
                </div>
                {{-- Search --}}
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

            {{-- Flash --}}
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

            {{-- List --}}
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
                    <li class="conv-item"
                        data-name="{{ strtolower($lawan->name ?? '') }}"
                        data-judul="{{ strtolower($conv->laporan->judul ?? '') }}">
                        <a href="{{ route('conversations.show', $conv) }}"
                           class="flex items-center gap-3 px-4 py-3.5 hover:bg-blue-50/60 transition border-l-4 border-transparent hover:border-indigo-300">

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
                                            @if($last->sender_id === $user->id)<span class="text-gray-400">Anda: </span>@endif
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

        {{-- ══════════════════════════════
             RIGHT PANEL — Welcome Screen
        ══════════════════════════════ --}}
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
        const searchInput = document.getElementById('search-conv');
        const convItems   = document.querySelectorAll('.conv-item');
        const noResult    = document.getElementById('no-result');

        searchInput?.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            let visible = 0;
            convItems.forEach(function (li) {
                const match = !q || li.dataset.name.includes(q) || li.dataset.judul.includes(q);
                li.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (noResult) noResult.classList.toggle('hidden', visible > 0 || q === '');
        });
    </script>
    @endpush
</x-app-layout>
