@if($laporans->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
        @foreach($laporans as $laporan)
            <article class="bg-white rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                <!-- Foto Barang / Fallback (Rasio 4:5 Portrait) -->
                <div class="relative w-full aspect-[4/5] bg-slate-100 overflow-hidden border-b border-slate-100" style="aspect-ratio: 4 / 5;">
                    @if($laporan->foto)
                        <img
                            src="{{ asset('storage/' . $laporan->foto) }}"
                            alt="{{ $laporan->judul }}"
                            style="object-fit: cover;"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        >
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                            <div class="w-10 h-10 rounded-full bg-slate-200/70 flex items-center justify-center text-slate-400 mb-1.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-[11px] font-medium text-slate-400">Tidak ada foto</span>
                        </div>
                    @endif

                    <!-- Badges di Sudut Atas Foto -->
                    <div class="absolute top-2.5 start-2.5 flex flex-wrap gap-1.5">
                        @if($laporan->tipe === 'hilang')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-600/90 text-white shadow-xs backdrop-blur-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-white me-1.5"></span>
                                Barang Hilang
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold bg-navy-800/90 text-white shadow-xs backdrop-blur-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-white me-1.5"></span>
                                Barang Ditemukan
                            </span>
                        @endif
                    </div>

                    <!-- Status Badge di Sudut Kanan Atas -->
                    <div class="absolute top-2.5 end-2.5">
                        @if($laporan->status === 'proses')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/90 text-white shadow-2xs backdrop-blur-xs uppercase tracking-wider">
                                Diproses
                            </span>
                        @elseif($laporan->status === 'selesai')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-600/90 text-white shadow-2xs backdrop-blur-xs uppercase tracking-wider">
                                Selesai
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-700/80 text-white shadow-2xs backdrop-blur-xs uppercase tracking-wider">
                                {{ $laporan->status }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Konten Kartu -->
                <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                    <div class="space-y-2.5">
                        <!-- Kategori Tag -->
                        @if($laporan->kategori)
                            <div class="inline-flex items-center text-[11px] font-medium text-navy-800 bg-navy-50 border border-navy-100 px-2 py-0.5 rounded-md">
                                {{ $laporan->kategori }}
                            </div>
                        @endif

                        <!-- Judul Barang -->
                        <h3 class="font-bold text-sm sm:text-base text-slate-900 group-hover:text-navy-800 transition-colors line-clamp-1">
                            {{ $laporan->judul }}
                        </h3>

                        <!-- Meta Lokasi & Tanggal -->
                        <div class="space-y-1.5 text-xs text-slate-500 pt-1">
                            <div class="flex items-center">
                                <svg class="w-3.5 h-3.5 me-1.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $laporan->lokasi }}</span>
                            </div>

                            <div class="flex items-center">
                                <svg class="w-3.5 h-3.5 me-1.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ date('d M Y', strtotime($laporan->tanggal_kejadian)) }}</span>
                            </div>

                            <div class="flex items-center text-[11px] text-slate-400 pt-1 border-t border-slate-100">
                                <span class="truncate">Pelapor: <strong class="text-slate-600 font-medium">{{ $laporan->user->name ?? 'Anonim' }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <a
                            href="{{ route('reports.show', $laporan->id) }}"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-semibold text-navy-800 bg-navy-50 hover:bg-navy-800 hover:text-white transition-colors"
                        >
                            Lihat Detail
                            <svg class="w-3.5 h-3.5 ms-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        @if($laporan->user_id == auth()->id())
                            <div class="flex items-center space-x-1 shrink-0">
                                @if($laporan->status === 'pending')
                                    <a
                                        href="{{ route('reports.edit', $laporan->id) }}"
                                        class="p-2 rounded-lg text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors"
                                        title="Edit Laporan"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                @endif

                                <form action="{{ route('reports.destroy', $laporan->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 transition-colors"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')"
                                        title="Hapus Laporan"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @elseif(auth()->check() && auth()->user()->isAdmin())
                            <div class="flex items-center space-x-1 shrink-0">
                                {{-- Tombol Takedown Admin dari Beranda --}}
                                <form action="{{ route('admin.laporan.takedown', $laporan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="p-2 rounded-lg text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors"
                                        onclick="return confirm('Takedown postingan &quot;{{ addslashes($laporan->judul) }}&quot; dari beranda? Postingan tidak akan lagi tampil di publik.')"
                                        title="Takedown Postingan dari Beranda"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- Tombol Hapus Permanen Admin --}}
                                <form action="{{ route('admin.laporan.destroy', $laporan->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 transition-colors"
                                        onclick="return confirm('Hapus postingan &quot;{{ addslashes($laporan->judul) }}&quot; secara permanen?')"
                                        title="Hapus Postingan Permanen"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="mt-6 pt-4 border-t border-slate-200/80 pagination-wrapper">
        {{ $laporans->withQueryString()->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-8 sm:p-12 text-center shadow-xs">
        <div class="w-14 h-14 rounded-2xl bg-navy-50 text-navy-800 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">
            Tidak Ada Laporan yang Sesuai
        </h3>
        <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-6">
            Kami tidak menemukan laporan dengan kriteria pencarian tersebut. Anda dapat mengatur ulang filter atau melaporkan barang baru.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <button type="button" data-action="reset-filter" class="inline-flex items-center px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Bersihkan Filter
            </button>
            <a href="{{ route('reports.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-white bg-navy-800 hover:bg-navy-900 shadow-xs transition-colors">
                <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Laporan Baru
            </a>
        </div>
    </div>
@endif
