<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <nav class="flex items-center text-xs text-slate-400 mb-1 space-x-1.5" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:text-navy-800 transition-colors">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-600 font-medium">Klaim Saya</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Klaim Saya</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Daftar pengajuan klaim kepemilikan yang Anda ajukan untuk barang temuan</p>
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
                   class="inline-flex items-center px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-600 hover:text-navy-800 hover:bg-slate-100 transition-colors">
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
                   class="inline-flex items-center px-4 py-2 rounded-lg text-xs sm:text-sm font-bold bg-navy-800 text-white shadow-xs">
                    <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Klaim Saya
                </a>
            </div>

            {{-- Tabel / Daftar Klaim Saya --}}
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="font-bold text-base sm:text-lg text-slate-900">Riwayat Pengajuan Klaim Saya</h2>
                        <p class="text-xs text-slate-500">Pantau status persetujuan klaim kepemilikan yang Anda ajukan</p>
                    </div>
                </div>

                @if($claims->isEmpty())
                    {{-- Empty State --}}
                    <div class="p-8 sm:p-12 flex flex-col items-center justify-center text-center">
                        <div class="w-14 h-14 rounded-2xl bg-navy-50 text-navy-800 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">Anda belum mengajukan klaim</h3>
                        <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-5 leading-relaxed">
                            Jika Anda merasa kehilangan barang dan melihat barang yang cocok pada daftar penemuan, Anda dapat mengajukan klaim kepemilikan.
                        </p>
                        <a href="{{ route('reports.index') }}"
                           class="inline-flex items-center justify-center bg-navy-800 hover:bg-navy-900 text-white text-xs sm:text-sm font-semibold py-2 px-4 rounded-lg shadow-xs transition-colors">
                            <svg class="w-3.5 h-3.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari Laporan Penemuan
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs sm:text-sm">
                            <thead class="bg-slate-50/75">
                                <tr>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Barang Temuan</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Keterangan Kepemilikan</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Tanggal Pengajuan</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Status</th>
                                    <th class="px-5 py-3.5 text-center font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Aksi</th>
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
                                                    Lokasi: {{ $claim->laporan->lokasi }}
                                                </p>
                                            @else
                                                <span class="text-slate-400 italic">Laporan telah dihapus</span>
                                            @endif
                                        </td>

                                        {{-- Keterangan Kepemilikan --}}
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

                                        {{-- Tautan Detail --}}
                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            <a href="{{ route('claims.show', $claim) }}"
                                               class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-navy-800 hover:text-navy-900 bg-navy-50 hover:bg-navy-100 rounded-md transition">
                                                Lihat Detail
                                                <svg class="w-3 h-3 ms-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
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
