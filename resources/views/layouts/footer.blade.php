<footer class="bg-white border-t border-slate-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            <!-- Brand Info -->
            <div class="space-y-3">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-navy-800 flex items-center justify-center text-white font-bold shadow-xs">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold text-navy-800 text-base tracking-tight">TemuUNRI</span>
                        <span class="block text-[11px] font-medium text-slate-400 uppercase tracking-wider">Universitas Riau</span>
                    </div>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                    Platform pelaporan dan penemuan barang hilang di lingkungan kampus Universitas Riau. Menghubungkan penemu dan pemilik barang secara aman dan transparan.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="space-y-3 md:ps-8">
                <h4 class="text-xs font-semibold text-slate-900 uppercase tracking-wider">Akses Cepat</h4>
                <ul class="space-y-2 text-xs text-slate-600">
                    <li>
                        <a href="{{ route('reports.index') }}" class="hover:text-navy-800 transition-colors inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300 me-2"></span>
                            Beranda Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.create') }}" class="hover:text-navy-800 transition-colors inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300 me-2"></span>
                            Buat Laporan Baru
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('claims.index') }}" class="hover:text-navy-800 transition-colors inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300 me-2"></span>
                            Klaim Saya
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('conversations.index') }}" class="hover:text-navy-800 transition-colors inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300 me-2"></span>
                            Pesan
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Lokasi & Kampus -->
            <div class="space-y-3">
                <h4 class="text-xs font-semibold text-slate-900 uppercase tracking-wider">Lokasi Kampus</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Universitas Riau<br>
                    Kampus Bina Widya Km. 12.5, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau 28293
                </p>
                <div class="pt-1">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-medium bg-navy-50 text-navy-800 border border-navy-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5 animate-pulse"></span>
                        Civitas Akademika UNRI
                    </span>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400 gap-2">
            <p>&copy; {{ date('Y') }} TemuUNRI — Universitas Riau.</p>
            <p class="text-slate-400 text-[11px]">Sistem Informasi Kampus Bebas Kehilangan</p>
        </div>
    </div>
</footer>
