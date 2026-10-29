<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center space-x-2 text-xs text-slate-500 mb-1">
                <span>TemuUNRI</span>
                <span>/</span>
                <span class="text-navy-800 font-medium">Dashboard</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Dashboard Saya
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola laporan barang hilang, penemuan, dan aktivitas klaim Anda
            </p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="flex items-start p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-2xs" role="alert">
                    <div class="shrink-0 text-emerald-600 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ms-3 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if(session('error'))
                <div class="flex items-start p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-2xs" role="alert">
                    <div class="shrink-0 text-rose-600 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ms-3 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            {{-- Sapaan dan Info Akun --}}
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-navy-100 text-navy-800 font-bold text-lg sm:text-xl flex items-center justify-center uppercase shrink-0 shadow-2xs select-none">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Halo, {{ $user->name }}!</h2>
                            <p class="text-xs sm:text-sm text-slate-500">{{ $user->email }}</p>
                            <p class="text-xs text-slate-400 mt-0.5 flex items-center">
                                <svg class="w-3.5 h-3.5 me-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Bergabung sejak {{ $user->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center justify-center px-4 py-2 border border-slate-200 text-xs sm:text-sm font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 hover:text-navy-800 hover:border-slate-300 transition-colors shadow-2xs">
                            <svg class="w-4 h-4 me-1.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Statistik Laporan --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
                {{-- Total --}}
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 border-t-4 border-t-navy-800 col-span-2 md:col-span-1">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Laporan</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-navy-800 mt-1">{{ $jumlahTotal }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">Semua laporan</p>
                </div>

                {{-- Menunggu --}}
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 border-t-4 border-t-amber-400">
                    <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide">Menunggu</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-amber-600 mt-1">{{ $jumlahPending }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">Pending review</p>
                </div>

                {{-- Diproses --}}
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 border-t-4 border-t-blue-500">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Diproses</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-blue-600 mt-1">{{ $jumlahProses }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">Disetujui di beranda</p>
                </div>

                {{-- Selesai --}}
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 border-t-4 border-t-emerald-500">
                    <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Selesai</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-1">{{ $jumlahSelesai }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">Telah dikembalikan</p>
                </div>

                {{-- Ditolak --}}
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-4 sm:p-5 border-t-4 border-t-rose-500">
                    <p class="text-xs font-semibold text-rose-700 uppercase tracking-wide">Ditolak</p>
                    <p class="text-2xl sm:text-3xl font-extrabold text-rose-600 mt-1">{{ $jumlahDitolak }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">Ditolak admin</p>
                </div>
            </div>

            {{-- Aktivitas Klaim: Persiapan UI Klaim Masuk & Klaim Saya --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Card 1: Klaim Masuk (Persiapan UI untuk Pelapor Barang Temuan) --}}
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 flex flex-col justify-between">
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2.5">
                                <span class="w-8 h-8 rounded-lg bg-navy-50 text-navy-800 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900">Klaim Masuk</h3>
                                    <p class="text-[11px] text-slate-500">Klaim dari pengguna lain atas barang temuan Anda</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-navy-50 text-navy-800 border border-navy-100 uppercase tracking-wider">
                                Siap Dikembangkan
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Sebagai penemu barang, Anda yang akan memverifikasi dan memutuskan penerimaan atau penolakan klaim kepemilikan. Modul persetujuan klaim masuk sedang disiapkan untuk tahap implementasi backend berikutnya.
                        </p>
                    </div>
                    <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                        <span class="flex items-center text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 me-1.5 animate-pulse"></span>
                            Menunggu integrasi alur persetujuan
                        </span>
                        <span class="text-[11px] font-medium text-slate-400 italic">Segera Hadir</span>
                    </div>
                </div>

                {{-- Card 2: Klaim Saya --}}
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 flex flex-col justify-between">
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2.5">
                                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900">Klaim Saya</h3>
                                    <p class="text-[11px] text-slate-500">Klaim yang Anda ajukan untuk barang hilang</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">
                                Aktif
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Pantau riwayat dan status verifikasi klaim kepemilikan yang telah Anda ajukan pada barang temuan milik pengguna lain di lingkungan kampus.
                        </p>
                    </div>
                    <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Kelola riwayat pengajuan klaim Anda</span>
                        <a href="{{ route('claims.index') }}"
                           class="inline-flex items-center text-xs font-semibold text-navy-800 hover:text-navy-900 hover:underline">
                            Buka Klaim Saya
                            <svg class="w-3.5 h-3.5 ms-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Daftar Laporan Saya --}}
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-base sm:text-lg text-slate-900">Laporan Saya</h2>
                        <p class="text-xs text-slate-500">Daftar laporan barang hilang dan penemuan yang Anda buat</p>
                    </div>
                    <a href="{{ route('reports.index') }}" class="text-xs sm:text-sm text-navy-800 hover:text-navy-900 font-semibold hover:underline inline-flex items-center">
                        Lihat Semua Laporan
                        <svg class="w-4 h-4 ms-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                @if($laporans->isEmpty())
                    {{-- Empty State --}}
                    <div class="p-8 sm:p-12 flex flex-col items-center justify-center text-center">
                        <div class="w-14 h-14 rounded-2xl bg-navy-50 text-navy-800 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">Belum ada laporan</h3>
                        <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-5">
                            Anda belum pernah membuat laporan barang hilang atau penemuan di lingkungan Universitas Riau.
                        </p>
                        <a href="{{ route('reports.create') }}"
                           class="inline-flex items-center justify-center bg-navy-800 hover:bg-navy-900 text-white text-xs sm:text-sm font-semibold py-2.5 px-5 rounded-lg shadow-xs transition-colors">
                            <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Laporan Pertama
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs sm:text-sm">
                            <thead class="bg-slate-50/75">
                                <tr>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Laporan</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Tipe & Kategori</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Tanggal Kejadian</th>
                                    <th class="px-5 py-3.5 text-left font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Status</th>
                                    <th class="px-5 py-3.5 text-center font-semibold text-slate-500 uppercase tracking-wider text-[11px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($laporans as $laporan)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        {{-- Judul & Foto --}}
                                        <td class="px-5 py-4">
                                            <div class="flex items-center space-x-3">
                                                @if($laporan->foto)
                                                    <img src="{{ asset('storage/' . $laporan->foto) }}"
                                                         alt="Foto {{ $laporan->judul }}"
                                                         class="w-11 h-11 object-cover rounded-lg border border-slate-200 shrink-0">
                                                @else
                                                    <div class="w-11 h-11 bg-slate-100 rounded-lg flex flex-col items-center justify-center text-[10px] text-slate-400 border border-slate-200 shrink-0">
                                                        <svg class="w-4 h-4 text-slate-400 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('reports.show', $laporan->id) }}"
                                                       class="text-xs sm:text-sm font-semibold text-slate-900 hover:text-navy-800 hover:underline line-clamp-1 transition-colors">
                                                        {{ $laporan->judul }}
                                                    </a>
                                                    <div class="text-[11px] text-slate-400 flex items-center mt-0.5">
                                                        <svg class="w-3 h-3 me-1 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        </svg>
                                                        <span class="truncate">{{ $laporan->lokasi }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Tipe & Kategori --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @if($laporan->tipe === 'hilang')
                                                <span class="px-2.5 py-0.5 inline-flex text-[11px] font-semibold rounded-md bg-rose-50 text-rose-700 border border-rose-100">
                                                    Barang Hilang
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 inline-flex text-[11px] font-semibold rounded-md bg-navy-50 text-navy-800 border border-navy-100">
                                                    Barang Ditemukan
                                                </span>
                                            @endif
                                            <div class="text-xs text-slate-500 mt-1 font-medium">{{ $laporan->kategori }}</div>
                                        </td>

                                        {{-- Tanggal Kejadian --}}
                                        <td class="px-5 py-4 whitespace-nowrap text-xs text-slate-600">
                                            {{ \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d M Y') }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @php
                                                $statusLabel = match($laporan->status) {
                                                    'pending'  => 'Menunggu',
                                                    'proses'   => 'Diproses',
                                                    'selesai'  => 'Selesai',
                                                    'Ditolak'  => 'Ditolak',
                                                    default    => ucfirst($laporan->status),
                                                };
                                                $statusClass = match($laporan->status) {
                                                    'pending'  => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                    'proses'   => 'bg-blue-50 text-navy-800 border border-blue-200',
                                                    'selesai'  => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                    'Ditolak'  => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                    default    => 'bg-slate-50 text-slate-700 border border-slate-200',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-0.5 inline-flex text-[11px] font-semibold rounded-md {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-medium space-x-1">
                                            <a href="{{ route('reports.show', $laporan->id) }}"
                                               class="text-navy-800 hover:text-navy-900 bg-navy-50 hover:bg-navy-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors inline-flex items-center">
                                                Detail
                                            </a>

                                            {{-- Tombol Edit & Hapus hanya untuk laporan berstatus 'pending' --}}
                                            @if($laporan->status === 'pending')
                                                <a href="{{ route('reports.edit', $laporan->id) }}"
                                                   class="text-amber-700 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors inline-flex items-center">
                                                    Edit
                                                </a>
                                                <form action="{{ route('reports.destroy', $laporan->id) }}"
                                                      method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-rose-700 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors inline-flex items-center"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($laporans->hasPages())
                        <div class="p-4 bg-slate-50/60 border-t border-slate-100">
                            {{ $laporans->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
