<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <nav class="flex items-center text-xs text-slate-400 mb-1 space-x-1.5" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:text-navy-800 transition-colors">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-600 font-medium">Klaim Masuk</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Klaim Masuk</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Daftar pengajuan klaim kepemilikan dari pengguna lain untuk barang temuan Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F7F9FC] min-h-[calc(100vh-140px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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

            {{-- Navigasi Tab Klaim --}}
            <div class="flex items-center space-x-2 border-b border-slate-200/90 pb-2">
                <a href="{{ route('claims.incoming') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg text-xs sm:text-sm font-bold bg-navy-800 text-white shadow-xs">
                    <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    Klaim Masuk
                    @if(isset($jumlahKlaimMasukPending) && $jumlahKlaimMasukPending > 0)
                        <span class="ms-2 px-1.5 py-0.5 rounded-full text-[10px] bg-amber-400 text-slate-900 font-bold">
                            {{ $jumlahKlaimMasukPending }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('claims.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-600 hover:text-navy-800 hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Klaim Saya
                </a>
            </div>

            {{-- Tabel / Daftar Klaim Masuk --}}
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="font-bold text-base sm:text-lg text-slate-900">Daftar Pengajuan Klaim Masuk</h2>
                        <p class="text-xs text-slate-500">Periksa bukti kepemilikan dan putuskan apakah klaim diterima atau ditolak</p>
                    </div>
                    @if(isset($jumlahKlaimMasukPending) && $jumlahKlaimMasukPending > 0)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 self-start sm:self-auto">
                            <span class="w-2 h-2 rounded-full bg-amber-400 me-1.5 animate-pulse"></span>
                            {{ $jumlahKlaimMasukPending }} Klaim Menunggu Keputusan
                        </span>
                    @endif
                </div>

                @if($claims->isEmpty())
                    {{-- Empty State --}}
                    <div class="p-8 sm:p-12 flex flex-col items-center justify-center text-center">
                        <div class="w-14 h-14 rounded-2xl bg-navy-50 text-navy-800 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">Belum ada klaim masuk</h3>
                        <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-5 leading-relaxed">
                            Saat pengguna lain mengajukan bukti kepemilikan atas laporan barang temuan Anda, daftarnya akan muncul di sini untuk Anda verifikasi.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-2.5">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center justify-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold py-2 px-4 rounded-lg transition-colors">
                                Kembali ke Dashboard
                            </a>
                            <a href="{{ route('reports.create') }}"
                               class="inline-flex items-center justify-center bg-navy-800 hover:bg-navy-900 text-white text-xs sm:text-sm font-semibold py-2 px-4 rounded-lg shadow-xs transition-colors">
                                <svg class="w-3.5 h-3.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Lapor Barang Temuan
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs sm:text-sm">
                            <thead class="bg-slate-50/75">
                                <tr>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Barang Temuan</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Pengaju Klaim</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Ciri Kepemilikan</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Tanggal Pengajuan</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Status</th>
                                    <th class="px-5 py-3.5 text-center font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Keputusan & Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($claims as $claim)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        {{-- Barang Temuan --}}
                                        <td class="px-5 py-4">
                                            @if($claim->laporan)
                                                <a href="{{ route('reports.show', $claim->laporan) }}" class="font-bold text-slate-900 hover:text-navy-800 hover:underline">
                                                    {{ $claim->laporan->judul }}
                                                </a>
                                                <p class="text-[11px] text-slate-400 mt-0.5">
                                                    Status laporan:
                                                    <span class="font-semibold text-slate-600">{{ ucfirst($claim->laporan->status) }}</span>
                                                </p>
                                            @else
                                                <span class="text-slate-400 italic">Laporan telah dihapus</span>
                                            @endif
                                        </td>

                                        {{-- Pengaju Klaim --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-7 h-7 rounded-full bg-navy-100 text-navy-800 font-bold flex items-center justify-center text-[10px] shrink-0">
                                                    {{ strtoupper(substr($claim->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div class="truncate max-w-[140px]">
                                                    <p class="font-semibold text-slate-900 text-xs truncate">{{ $claim->user->name ?? 'Anonim' }}</p>
                                                    <p class="text-[10px] text-slate-400 truncate">{{ $claim->user->email ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Ciri Kepemilikan --}}
                                        <td class="px-5 py-4 max-w-xs">
                                            <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed">
                                                {{ $claim->description }}
                                            </p>
                                        </td>

                                        {{-- Tanggal Pengajuan --}}
                                        <td class="px-5 py-4 whitespace-nowrap text-xs text-slate-500">
                                            {{ $claim->created_at->format('d M Y, H:i') }}
                                        </td>

                                        {{-- Status Klaim --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @if($claim->status === 'pending')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 me-1"></span>
                                                    Menunggu
                                                </span>
                                            @elseif($claim->status === 'accepted')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1"></span>
                                                    Diterima
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 me-1"></span>
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Keputusan & Aksi --}}
                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                @if($claim->status === 'pending' && $claim->laporan && $claim->laporan->status === 'proses')
                                                    {{-- Tombol Terima --}}
                                                    <form method="POST" action="{{ route('claims.decide', $claim) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin MENERIMA klaim ini? Laporan penemuan akan selesai dan klaim pending lainnya otomatis ditolak.');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="decision" value="accepted">
                                                        <button type="submit"
                                                                class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-2xs">
                                                            <svg class="w-3.5 h-3.5 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Terima
                                                        </button>
                                                    </form>

                                                    {{-- Tombol Tolak --}}
                                                    <form method="POST" action="{{ route('claims.decide', $claim) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK klaim ini?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="decision" value="rejected">
                                                        <button type="submit"
                                                                class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-md text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition shadow-2xs">
                                                            <svg class="w-3.5 h-3.5 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Tolak
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Tautan Detail --}}
                                                <a href="{{ route('claims.show', $claim) }}"
                                                   class="inline-flex items-center px-2 py-1 text-xs font-semibold text-slate-700 hover:text-navy-800 hover:bg-slate-100 rounded-md transition">
                                                    Detail
                                                    <svg class="w-3 h-3 ms-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($claims->hasPages())
                        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $claims->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
