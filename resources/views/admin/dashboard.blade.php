<x-app-layout>
    @push('styles')
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
        .animate-tab-content { animation: fadeIn 0.2s ease-out; }
    </style>
    @endpush

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="adminDashboard()">

        {{-- ===================== TOAST NOTIFICATION ===================== --}}
        @if(session('success'))
            <div id="toast-success"
                 class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-slate-900 text-white text-xs font-semibold rounded-2xl shadow-2xl"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div id="toast-error"
                 class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-rose-600 text-white text-xs font-semibold rounded-2xl shadow-2xl"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4">
                <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ===================== BREADCRUMB & HEADER ===================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                    <a href="{{ route('reports.index') }}" class="hover:text-slate-600 transition-colors">TemuUNRI</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-indigo-600 font-medium">Panel Admin</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard Pengelolaan Admin</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Pantau dan verifikasi laporan barang hilang &amp; klaim kepemilikan di lingkungan kampus UNRI.
                </p>
            </div>
            {{-- Quick Actions --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.export.csv') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-xs transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Export Data</span>
                </a>
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 border border-indigo-200/80 text-indigo-700 hover:bg-indigo-100 text-xs font-semibold rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Refresh Panel</span>
                </a>
            </div>
        </div>

        {{-- ===================== USER ADMIN CARD BANNER ===================== --}}
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
            <!-- Decorative Background SVG Blobs -->
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-40 -bottom-20 w-48 h-48 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600/30 border-2 border-indigo-400/30 text-indigo-200 flex items-center justify-center text-2xl font-bold backdrop-blur-sm shadow-inner shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="text-xl font-bold tracking-tight">Halo, {{ Auth::user()->name }}!</h2>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Mode Utama Active
                            </span>
                        </div>
                        <p class="text-xs text-slate-300 mt-1 flex items-center gap-4 flex-wrap">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ Auth::user()->email }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Bergabung {{ Auth::user()->created_at->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Hak Akses: Super Administrator
                            </span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <a href="{{ route('profile.edit') }}"
                       class="w-full md:w-auto px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/15 text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-2 backdrop-blur-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profil Admin
                    </a>
                </div>
            </div>
        </div>

        {{-- ===================== STATS / METRICS CARDS ===================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            <!-- Card 1: Total -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Laporan</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1" id="stat-total">{{ $jumlahTotal }}</h3>
                    </div>
                    <div class="p-2.5 bg-slate-100 text-slate-700 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-[11px] text-emerald-600 font-medium">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Semua laporan tercatat
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-slate-800"></div>
            </div>

            <!-- Card 2: Menunggu -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider">Menunggu</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1" id="stat-pending">{{ $jumlahPending }}</h3>
                    </div>
                    <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-[11px] text-amber-600 font-medium">
                    <span>Butuh verifikasi admin</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500"></div>
            </div>

            <!-- Card 3: Diproses -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider">Diproses</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1" id="stat-process">{{ $jumlahProses }}</h3>
                    </div>
                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-[11px] text-blue-600 font-medium">
                    <span>Proses klaim berlangsung</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-500"></div>
            </div>

            <!-- Card 4: Selesai -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Selesai</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1" id="stat-success">{{ $jumlahSelesai }}</h3>
                    </div>
                    <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-[11px] text-emerald-600 font-medium">
                    <span>Berhasil dikembalikan</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500"></div>
            </div>

            <!-- Card 5: Ditolak -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-rose-500 uppercase tracking-wider">Ditolak</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1" id="stat-rejected">{{ $jumlahDitolak }}</h3>
                    </div>
                    <div class="p-2.5 bg-rose-50 text-rose-600 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-[11px] text-rose-500 font-medium">
                    <span>Laporan tidak valid</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-rose-500"></div>
            </div>

        </div>

        {{-- ===================== DATA TABLES & ADMIN PANEL SECTIONS ===================== --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">

            <!-- Tab Navigation & Filters Bar -->
            <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">

                <!-- Main Admin Tabs -->
                <div class="flex items-center gap-2 overflow-x-auto custom-scrollbar pb-2 md:pb-0">
                    <button @click="switchTab('laporan')"
                            :class="activeTab === 'laporan' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'"
                            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-all whitespace-nowrap inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Kelola Semua Laporan</span>
                    </button>
                    <button @click="switchTab('klaim')"
                            :class="activeTab === 'klaim' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'"
                            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-all whitespace-nowrap inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <span>Verifikasi Klaim Masuk</span>
                        @if($jumlahKlaimPending > 0)
                            <span class="bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ml-0.5">
                                {{ $jumlahKlaimPending }}
                            </span>
                        @endif
                    </button>
                    <button @click="switchTab('users')"
                            :class="activeTab === 'users' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'"
                            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-all whitespace-nowrap inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Pengguna &amp; Log Aktivitas</span>
                    </button>
                </div>

                <!-- Search & Filters (Tab Laporan) -->
                <form method="GET" action="{{ route('admin.dashboard') }}" x-show="activeTab === 'laporan'"
                      class="flex items-center gap-2 w-full md:w-auto">
                    <input type="hidden" name="tab" value="laporan">
                    <div class="relative flex-1 md:w-64">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $searchLaporan }}"
                               placeholder="Cari nama barang, pelapor..."
                               class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    <select name="status_filter" onchange="this.form.submit()"
                            class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="ALL" {{ ($filterStatus ?? 'ALL') === 'ALL' ? 'selected' : '' }}>Semua Status</option>
                        <option value="MENUNGGU" {{ ($filterStatus ?? '') === 'MENUNGGU' ? 'selected' : '' }}>Menunggu</option>
                        <option value="DIPROSES" {{ ($filterStatus ?? '') === 'DIPROSES' ? 'selected' : '' }}>Diproses</option>
                        <option value="SELESAI"  {{ ($filterStatus ?? '') === 'SELESAI'  ? 'selected' : '' }}>Selesai</option>
                        <option value="DITOLAK"  {{ ($filterStatus ?? '') === 'DITOLAK'  ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </form>

            </div>

            <!-- TAB 1: MANAJEMEN LAPORAN -->
            <div x-show="activeTab === 'laporan'" class="animate-tab-content">
                @if($semuaLaporan->count() === 0)
                    <div class="p-16 text-center text-slate-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-medium">Tidak ada laporan yang ditemukan.</p>
                        <p class="text-xs mt-1">Coba sesuaikan filter atau kata kunci pencarian.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm text-slate-600">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] tracking-wider font-semibold border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3.5">Detail Barang</th>
                                    <th class="px-6 py-3.5">Tipe Laporan</th>
                                    <th class="px-6 py-3.5">Pelapor</th>
                                    <th class="px-6 py-3.5">Lokasi &amp; Tanggal</th>
                                    <th class="px-6 py-3.5">Status</th>
                                    <th class="px-6 py-3.5 text-right">Aksi Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium" id="laporanTableBody">
                                @foreach($semuaLaporan as $laporan)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        {{-- Detail Barang --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if($laporan->foto)
                                                    <img src="{{ asset('storage/' . $laporan->foto) }}"
                                                         alt="Foto"
                                                         class="w-10 h-10 object-cover rounded-xl border border-slate-200 shrink-0">
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-semibold shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-bold text-slate-900 max-w-[200px] line-clamp-2 leading-tight">{{ $laporan->judul }}</div>
                                                    <div class="text-[11px] text-slate-400 mt-0.5">ID: #REP-{{ $laporan->created_at->format('Y') }}-{{ str_pad($laporan->id, 3, '0', STR_PAD_LEFT) }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Tipe Laporan --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($laporan->tipe === 'hilang')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Barang Hilang
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Barang Temuan
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Pelapor --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-slate-900 font-semibold">{{ $laporan->user->name ?? 'Anonim' }}</div>
                                            <div class="text-[11px] text-slate-400">{{ $laporan->user->email ?? '-' }}</div>
                                        </td>

                                        {{-- Lokasi & Tanggal --}}
                                        <td class="px-6 py-4">
                                            <div class="text-slate-700 max-w-[160px] line-clamp-1">{{ $laporan->lokasi }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">
                                                {{ \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d M Y') }}
                                            </div>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusConfig = match($laporan->status) {
                                                    'pending' => ['label' => 'Menunggu Review', 'dot' => 'bg-amber-500', 'cls' => 'bg-amber-50 text-amber-700 border-amber-200/60'],
                                                    'proses'  => ['label' => 'Diproses Klaim',  'dot' => 'bg-blue-500',  'cls' => 'bg-blue-50 text-blue-700 border-blue-200/60'],
                                                    'selesai' => ['label' => 'Selesai',          'dot' => 'bg-emerald-500','cls' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60'],
                                                    'Ditolak' => ['label' => 'Ditolak',           'dot' => 'bg-rose-500',  'cls' => 'bg-rose-50 text-rose-700 border-rose-200/60'],
                                                    default   => ['label' => $laporan->status,    'dot' => 'bg-slate-500', 'cls' => 'bg-slate-50 text-slate-700 border-slate-200/60'],
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusConfig['cls'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                                {{ $statusConfig['label'] }}
                                            </span>
                                        </td>

                                        {{-- Aksi Admin --}}
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1.5">
                                                @if($laporan->status === 'pending')
                                                    {{-- Tombol Setujui --}}
                                                    <form action="{{ route('admin.laporan.setujui', $laporan->id) }}" method="POST" class="inline"
                                                          onsubmit="return confirm('Setujui laporan &quot;{{ addslashes($laporan->judul) }}&quot;?')">
                                                        @csrf
                                                        <button type="submit" title="Setujui Laporan"
                                                                class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    {{-- Tombol Tolak --}}
                                                    <form action="{{ route('admin.laporan.tolak', $laporan->id) }}" method="POST" class="inline"
                                                          onsubmit="return confirm('Tolak laporan &quot;{{ addslashes($laporan->judul) }}&quot;?')">
                                                        @csrf
                                                        <button type="submit" title="Tolak Laporan"
                                                                class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Tombol Modal Detail Cepat --}}
                                                <button type="button"
                                                        @click="openModal('{{ addslashes($laporan->judul) }}', '{{ addslashes($laporan->user->name ?? 'Anonim') }}', '{{ addslashes($laporan->lokasi) }}', '{{ $laporan->tipe === 'hilang' ? 'Barang Hilang' : 'Barang Temuan' }}', '{{ $statusConfig['label'] }}', '{{ route('admin.laporan.show', $laporan->id) }}')"
                                                        title="Detail Cepat"
                                                        class="p-1.5 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($semuaLaporan->hasPages())
                        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $semuaLaporan->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- TAB 2: VERIFIKASI KLAIM -->
            <div x-show="activeTab === 'klaim'" class="animate-tab-content p-6">
                @if($klaimMasuk->count() === 0)
                    <div class="py-16 text-center text-slate-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="font-medium">Tidak ada klaim yang menunggu verifikasi saat ini.</p>
                        <p class="text-xs mt-1">Semua klaim barang telah diproses.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($klaimMasuk as $klaim)
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-amber-600 bg-amber-100/70 px-2.5 py-1 rounded-lg">KLAIM PENDING</span>
                                    <span class="text-xs text-slate-400">Diajukan {{ $klaim->created_at->diffForHumans() }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base">Klaim: {{ $klaim->laporan->judul ?? '-' }}</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Penemu: {{ $klaim->laporan->user->name ?? 'Anonim' }} | Ditemukan di {{ $klaim->laporan->lokasi ?? '-' }}
                                    </p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-200 text-xs text-slate-600 space-y-1">
                                    <p><span class="font-semibold text-slate-900">Pengaju Klaim:</span> {{ $klaim->user->name ?? 'Anonim' }} ({{ $klaim->user->email ?? '-' }})</p>
                                    <p><span class="font-semibold text-slate-900">Bukti Kepemilikan:</span> {{ $klaim->description }}</p>
                                </div>
                                <div class="flex gap-2">
                                    {{-- Setujui Klaim --}}
                                    <form action="{{ route('admin.klaim.setujui', $klaim->id) }}" method="POST" class="flex-1"
                                          onsubmit="return confirm('Setujui klaim kepemilikan ini? Laporan penemuan akan otomatis ditandai Selesai.')">
                                        @csrf
                                        <button type="submit"
                                                class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition-colors flex items-center justify-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Setujui Klaim
                                        </button>
                                    </form>

                                    {{-- Tolak Klaim --}}
                                    <form action="{{ route('admin.klaim.tolak', $klaim->id) }}" method="POST" class="flex-1"
                                          onsubmit="return confirm('Tolak klaim kepemilikan ini?')">
                                        @csrf
                                        <button type="submit"
                                                class="w-full py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-semibold transition-colors flex items-center justify-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($klaimMasuk->hasPages())
                        <div class="mt-6">{{ $klaimMasuk->links() }}</div>
                    @endif
                @endif
            </div>

            <!-- TAB 3: PENGGUNA & LOG -->
            <div x-show="activeTab === 'users'" class="animate-tab-content p-6 space-y-6">

                <!-- Daftar Pengguna -->
                <div>
                    <h4 class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Daftar Pengguna Terdaftar</span>
                        <span class="ml-auto text-xs font-normal text-slate-400">Total: {{ $semuaPengguna->total() }}</span>
                    </h4>
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-xs text-slate-600">
                            <thead class="bg-slate-50 border-b border-slate-200 text-[11px] text-slate-500 uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="px-4 py-3 text-left">Pengguna</th>
                                    <th class="px-4 py-3 text-left">Role</th>
                                    <th class="px-4 py-3 text-left">Jumlah Laporan</th>
                                    <th class="px-4 py-3 text-left">Bergabung</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($semuaPengguna as $usr)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full {{ $usr->isAdmin() ? 'bg-rose-100 text-rose-700' : 'bg-indigo-100 text-indigo-700' }} font-bold text-xs flex items-center justify-center shrink-0">
                                                    {{ strtoupper(substr($usr->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-slate-800">{{ $usr->name }}</div>
                                                    <div class="text-[11px] text-slate-400">{{ $usr->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($usr->isAdmin())
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Pengguna
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-slate-800">
                                            {{ $usr->laporans_count }} laporan
                                        </td>
                                        <td class="px-4 py-3 text-slate-500">
                                            {{ $usr->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($semuaPengguna->hasPages())
                        <div class="mt-4">{{ $semuaPengguna->links() }}</div>
                    @endif
                </div>

                <!-- Aktivitas Terakhir Sistem -->
                <div class="space-y-4">
                    <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Aktivitas Terakhir Sistem</span>
                    </h4>
                    <div class="space-y-3">
                        @forelse($aktivitasTerbaru as $akt)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl text-xs border border-slate-200/60">
                                <div class="flex items-center gap-3">
                                    @php
                                        $aktIcon = match($akt->status) {
                                            'pending' => ['bg' => 'bg-amber-100 text-amber-700', 'svg' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'proses'  => ['bg' => 'bg-blue-100 text-blue-700',   'svg' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'selesai' => ['bg' => 'bg-emerald-100 text-emerald-700', 'svg' => 'M5 13l4 4L19 7'],
                                            'Ditolak' => ['bg' => 'bg-rose-100 text-rose-700',  'svg' => 'M6 18L18 6M6 6l12 12'],
                                            default   => ['bg' => 'bg-slate-100 text-slate-700', 'svg' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                        };
                                    @endphp
                                    <div class="w-8 h-8 rounded-full {{ $aktIcon['bg'] }} flex items-center justify-center font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $aktIcon['svg'] }}"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $akt->user->name ?? 'Anonim' }} memperbarui &quot;{{ Str::limit($akt->judul, 35) }}&quot;
                                        </p>
                                        <p class="text-slate-400 text-[11px] mt-0.5">
                                            {{ $akt->updated_at->format('d M Y - H:i') }} WIB &bull; Status: {{ ucfirst($akt->status) }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.laporan.show', $akt->id) }}"
                                   class="text-indigo-600 hover:text-indigo-800 font-medium shrink-0 ml-3">
                                    Lihat &rarr;
                                </a>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-6">Belum ada aktivitas laporan.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

        {{-- ===================== DETAIL MODAL ===================== --}}
        <div id="detailModal"
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 z-50"
             style="display: none;">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 relative space-y-5"
                 @click.away="closeModal()">
                <button @click="closeModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg line-clamp-1" x-text="modalData.title"></h3>
                        <p class="text-xs text-slate-400">Detail Verifikasi Laporan Admin</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Pelapor / Pemilik:</span>
                        <span class="font-semibold text-slate-800" x-text="modalData.user"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Lokasi Kejadian:</span>
                        <span class="font-semibold text-slate-800" x-text="modalData.loc"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Tipe Laporan:</span>
                        <span class="font-semibold text-slate-800" x-text="modalData.type"></span>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Status Saat Ini:</span>
                        <span class="font-semibold text-indigo-600" x-text="modalData.status"></span>
                    </div>
                </div>

                <div class="pt-2 flex gap-3">
                    <a :href="modalData.url" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition-colors text-center inline-flex items-center justify-center gap-1.5">
                        <span>Halaman Detail Lengkap</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    <button @click="closeModal()" class="py-2.5 px-5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function adminDashboard() {
            return {
                activeTab: new URLSearchParams(window.location.search).get('tab') || 'laporan',
                modalOpen: false,
                modalData: {
                    title: '',
                    user: '',
                    loc: '',
                    type: '',
                    status: '',
                    url: '#'
                },
                switchTab(tab) {
                    this.activeTab = tab;
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url.toString());
                },
                openModal(title, user, loc, type, status, url) {
                    this.modalData = { title, user, loc, type, status, url };
                    this.modalOpen = true;
                },
                closeModal() {
                    this.modalOpen = false;
                }
            };
        }
    </script>
    @endpush

</x-app-layout>
