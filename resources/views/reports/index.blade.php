<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center space-x-2 text-xs text-slate-500 mb-1">
                <span>TemuUNRI</span>
                <span>/</span>
                <span class="text-navy-800 font-medium">Beranda Laporan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Daftar Barang Hilang & Ditemukan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Lingkungan Civitas Akademika Universitas Riau
            </p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

            <!-- Flash Notification -->
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

            <!-- 1. Ikhtisar Laporan Terbaru (Barang Hilang, Penemuan, Selesai) -->
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Ikhtisar Laporan Terbaru</h2>
                        <p class="text-xs text-slate-500">Pembaruan terkini dari masing-masing kategori laporan dan riwayat selesai</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Kolom 1: Barang Hilang Terbaru -->
                    <div class="bg-white rounded-xl border border-slate-200/85 p-5 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                            <div class="flex items-center space-x-2">
                                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </span>
                                <h3 class="font-bold text-sm text-slate-900">Barang Hilang Terbaru</h3>
                            </div>
                            <span class="text-[11px] font-semibold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100">
                                {{ $barangHilang->count() }} Laporan
                            </span>
                        </div>

                        <ul class="space-y-2.5 flex-1">
                            @forelse($barangHilang as $item)
                                <li class="p-2.5 rounded-lg hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                                    <a href="{{ route('reports.show', $item->id) }}" class="block group">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-xs font-semibold text-slate-800 group-hover:text-navy-800 transition-colors line-clamp-1">
                                                {{ $item->judul }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                                            <span class="truncate max-w-[140px] flex items-center">
                                                <svg class="w-3 h-3 me-1 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                {{ $item->lokasi }}
                                            </span>
                                            <span class="text-slate-400 shrink-0">
                                                {{ date('d M Y', strtotime($item->tanggal_kejadian)) }}
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="py-6 text-center text-xs text-slate-400 italic">
                                    Belum ada laporan barang hilang aktif.
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Kolom 2: Barang Penemuan Terbaru -->
                    <div class="bg-white rounded-xl border border-slate-200/85 p-5 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                            <div class="flex items-center space-x-2">
                                <span class="w-7 h-7 rounded-lg bg-navy-50 text-navy-800 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <h3 class="font-bold text-sm text-slate-900">Barang Penemuan Terbaru</h3>
                            </div>
                            <span class="text-[11px] font-semibold text-navy-800 bg-navy-50 px-2 py-0.5 rounded-full border border-navy-100">
                                {{ $barangPenemuan->count() }} Laporan
                            </span>
                        </div>

                        <ul class="space-y-2.5 flex-1">
                            @forelse($barangPenemuan as $item)
                                <li class="p-2.5 rounded-lg hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                                    <a href="{{ route('reports.show', $item->id) }}" class="block group">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-xs font-semibold text-slate-800 group-hover:text-navy-800 transition-colors line-clamp-1">
                                                {{ $item->judul }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                                            <span class="truncate max-w-[140px] flex items-center">
                                                <svg class="w-3 h-3 me-1 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                {{ $item->lokasi }}
                                            </span>
                                            <span class="text-slate-400 shrink-0">
                                                {{ date('d M Y', strtotime($item->tanggal_kejadian)) }}
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="py-6 text-center text-xs text-slate-400 italic">
                                    Belum ada laporan penemuan barang aktif.
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Kolom 3: Riwayat Laporan Selesai -->
                    <div class="bg-white rounded-xl border border-slate-200/85 p-5 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                            <div class="flex items-center space-x-2">
                                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <h3 class="font-bold text-sm text-slate-900">Riwayat Laporan Selesai</h3>
                            </div>
                            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                Selesai
                            </span>
                        </div>

                        <ul class="space-y-2.5 flex-1">
                            @forelse($laporanSelesai as $item)
                                <li class="p-2.5 rounded-lg hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                                    <a href="{{ route('reports.show', $item->id) }}" class="block group">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-xs font-semibold text-slate-800 group-hover:text-navy-800 transition-colors line-clamp-1">
                                                {{ $item->judul }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                                            <span class="inline-flex items-center text-emerald-700 font-semibold text-[10px]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1"></span>
                                                Terklaim
                                            </span>
                                            <span class="text-slate-400 shrink-0">
                                                {{ date('d M Y', strtotime($item->tanggal_kejadian)) }}
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="py-6 text-center text-xs text-slate-400 italic">
                                    Belum ada riwayat laporan selesai.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 2. Pencarian dan Filter Laporan -->
            <section id="daftar-laporan" class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Pencarian & Filter Laporan</h2>
                        <p class="text-xs text-slate-500">Saring barang berdasarkan kata kunci, jenis laporan, atau kategori</p>
                    </div>

                    @if(request('search') || request('tipe') || request('kategori'))
                        <div class="flex items-center space-x-2">
                            <span class="text-xs text-slate-500">Filter Aktif:</span>
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                Hapus Filter
                                <svg class="w-3.5 h-3.5 ms-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-end">
                    <!-- Kata Kunci (Judul / Lokasi) -->
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label for="search" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Kata Kunci (Nama Barang / Lokasi)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Misal: Dompet, Kunci motor, FMIPA..."
                                class="w-full ps-9 pe-3 py-2 text-xs sm:text-sm bg-slate-50/50 border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:bg-white focus:border-navy-700 focus:ring-1 focus:ring-navy-700 shadow-2xs transition-colors"
                            >
                        </div>
                    </div>

                    <!-- Jenis Laporan -->
                    <div class="lg:col-span-3">
                        <label for="tipe" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Jenis Laporan
                        </label>
                        <div class="relative">
                            <select
                                id="tipe"
                                name="tipe"
                                class="w-full py-2 ps-3 pe-8 text-xs sm:text-sm bg-slate-50/50 border border-slate-300 rounded-lg text-slate-800 focus:bg-white focus:border-navy-700 focus:ring-1 focus:ring-navy-700 shadow-2xs transition-colors"
                            >
                                <option value="">Semua Jenis Laporan</option>
                                <option value="hilang" {{ request('tipe') == 'hilang' ? 'selected' : '' }}>Barang Hilang</option>
                                <option value="penemuan" {{ request('tipe') == 'penemuan' ? 'selected' : '' }}>Barang Ditemukan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kategori Barang -->
                    <div class="lg:col-span-3">
                        <label for="kategori" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Kategori Barang
                        </label>
                        <input
                            type="text"
                            id="kategori"
                            name="kategori"
                            value="{{ request('kategori') }}"
                            placeholder="Misal: Elektronik, Dompet, Tas..."
                            class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50/50 border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:bg-white focus:border-navy-700 focus:ring-1 focus:ring-navy-700 shadow-2xs transition-colors"
                        >
                    </div>

                    <!-- Tombol Cari & Reset -->
                    <div class="sm:col-span-2 lg:col-span-2 flex space-x-2">
                        <button
                            type="submit"
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg text-white bg-navy-800 hover:bg-navy-900 shadow-xs transition-colors"
                        >
                            <svg class="w-3.5 h-3.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>

                        <a
                            href="{{ route('reports.index') }}"
                            class="inline-flex items-center justify-center px-3 py-2 text-xs sm:text-sm font-medium rounded-lg text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors"
                            title="Reset Pencarian"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </section>

            <!-- 3. Daftar Kartu Barang Hilang dan Ditemukan -->
            <section class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Daftar Laporan Aktif</h2>
                        <p class="text-xs text-slate-500">
                            Menampilkan laporan yang telah disetujui admin dan sedang dalam proses
                        </p>
                    </div>

                    <div class="text-xs text-slate-500">
                        Total: <span class="font-bold text-slate-800">{{ $laporans->total() }}</span> laporan
                    </div>
                </div>

                @if($laporans->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                        @foreach($laporans as $laporan)
                            <article class="bg-white rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                                <!-- Foto Barang / Fallback -->
                                <div class="relative w-full aspect-16/10 bg-slate-100 overflow-hidden border-b border-slate-100">
                                    @if($laporan->foto)
                                        <img
                                            src="{{ asset('storage/' . $laporan->foto) }}"
                                            alt="{{ $laporan->judul }}"
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
                                                <a
                                                    href="{{ route('reports.edit', $laporan->id) }}"
                                                    class="p-2 rounded-lg text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors"
                                                    title="Edit Laporan"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

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
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-6 pt-4 border-t border-slate-200/80">
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
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                                <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Bersihkan Filter
                            </a>
                            <a href="{{ route('reports.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-white bg-navy-800 hover:bg-navy-900 shadow-xs transition-colors">
                                <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Buat Laporan Baru
                            </a>
                        </div>
                    </div>
                @endif
            </section>

        </div>
    </div>
</x-app-layout>