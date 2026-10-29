<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <nav class="flex items-center text-xs text-slate-400 mb-1 space-x-1.5" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:text-navy-800 transition-colors">Dashboard</a>
                    <span>/</span>
                    @if(auth()->id() === $claim->laporan->user_id)
                        <a href="{{ route('claims.incoming') }}" class="hover:text-navy-800 transition-colors">Klaim Masuk</a>
                    @else
                        <a href="{{ route('claims.index') }}" class="hover:text-navy-800 transition-colors">Klaim Saya</a>
                    @endif
                    <span>/</span>
                    <span class="text-slate-600 font-medium">Detail Klaim</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Detail Pengajuan Klaim</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Informasi lengkap bukti kepemilikan dan status klaim</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F7F9FC] min-h-[calc(100vh-140px)]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs sm:text-sm flex items-center justify-between shadow-2xs" role="alert">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-xs sm:text-sm flex items-center justify-between shadow-2xs" role="alert">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 sm:p-8 space-y-6">
                {{-- Header Detail --}}
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Klaim untuk laporan barang</p>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 mt-0.5">
                            <a href="{{ route('reports.show', $claim->laporan) }}" class="hover:text-navy-800 hover:underline">
                                {{ $claim->laporan->judul }}
                            </a>
                        </h2>
                        <div class="flex items-center space-x-2 mt-2 text-xs text-slate-500">
                            <span>Pengaju: <strong class="text-slate-800 font-semibold">{{ $claim->user->name }}</strong></span>
                            <span>&bull;</span>
                            <span>Diajukan pada: {{ $claim->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    <div>
                        @if($claim->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-400 me-1.5 animate-pulse"></span>
                                Menunggu Keputusan
                            </span>
                        @elseif($claim->status === 'accepted')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 me-1.5"></span>
                                Klaim Diterima
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                <span class="w-2 h-2 rounded-full bg-rose-500 me-1.5"></span>
                                Klaim Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Bukti / Keterangan Kepemilikan --}}
                <div class="space-y-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Keterangan / Bukti Kepemilikan</h3>
                    <div class="bg-slate-50 p-4 sm:p-5 rounded-xl text-slate-800 text-sm whitespace-pre-line leading-relaxed border border-slate-200/80">
                        {{ $claim->description }}
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-navy-50/60 border border-navy-100 text-xs text-slate-600 flex items-start space-x-2.5">
                    <svg class="w-4 h-4 text-navy-800 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="leading-relaxed">
                        Penerimaan atau penolakan klaim dilakukan oleh pelapor penemuan barang. Jika klaim diterima, laporan penemuan otomatis dinyatakan selesai dan klaim lain akan otomatis ditolak.
                    </p>
                </div>

                {{-- Action Panel untuk Pelapor Barang Temuan --}}
                @if($claim->status === 'pending' && auth()->id() === $claim->laporan->user_id && $claim->laporan->status === 'proses')
                    <div class="p-5 rounded-xl border border-amber-200 bg-amber-50/40 space-y-3">
                        <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wide">Tindakan Keputusan Pelapor</h4>
                        <p class="text-xs text-slate-600">
                            Sebagai penemu barang, tentukan apakah keterangan kepemilikan di atas sudah sesuai dengan barang yang Anda temukan.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-2.5 pt-1">
                            {{-- Terima --}}
                            <form method="POST" action="{{ route('claims.decide', $claim) }}" onsubmit="return confirm('Apakah Anda yakin ingin MENERIMA klaim kepemilikan ini? Laporan barang akan ditandai selesai.');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="decision" value="accepted">
                                <button type="submit"
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-2xs">
                                    <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Terima Klaim Ini
                                </button>
                            </form>

                            {{-- Tolak --}}
                            <form method="POST" action="{{ route('claims.decide', $claim) }}" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK klaim kepemilikan ini?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="decision" value="rejected">
                                <button type="submit"
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition shadow-2xs">
                                    <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Tolak Klaim Ini
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Tombol Navigasi Bawah --}}
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3">
                    @if(auth()->id() === $claim->laporan->user_id)
                        <a href="{{ route('claims.incoming') }}"
                           class="w-full sm:w-auto text-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg text-xs font-semibold transition">
                            &larr; Kembali ke Klaim Masuk
                        </a>
                    @else
                        <a href="{{ route('claims.index') }}"
                           class="w-full sm:w-auto text-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg text-xs font-semibold transition">
                            &larr; Kembali ke Klaim Saya
                        </a>
                    @endif

                    <a href="{{ route('reports.show', $claim->laporan) }}"
                       class="w-full sm:w-auto text-center bg-navy-800 hover:bg-navy-900 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow-xs transition">
                        Lihat Laporan Barang
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
