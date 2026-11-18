@props([
    'badge' => null,
    'quote' => 'Temukan Kembali, Temukan Bersama.',
    'description' => 'Satu wadah terintegrasi untuk melaporkan barang hilang, mengumumkan temuan, dan memastikan setiap barang kembali ke pemilik sahnya di Universitas Riau.',
    'highlight1' => 'Pencarian Cepat & Filter Kategori Real-Time',
    'highlight2' => 'Mediasi Klaim Akuntabel & Bukti Spesifik',
    'highlight3' => 'Komunikasi Privat Terproteksi (In-App Chat)',
])

<!-- Kolom Kanan: Area Visual Abstrak (Tema Navy / Slate Lembut Sesuai Identitas TemuUNRI) -->
<div class="lg:col-span-5 hidden lg:flex flex-col justify-between p-8 sm:p-10 lg:p-12 relative overflow-hidden bg-gradient-to-br from-[#EAF2FB] via-[#F1F5F9] to-[#E2EBF6] border-l border-slate-200/70 select-none">
    <!-- Visual Shapes Abstrak dengan Efek Blur Lembut (Tanpa Warna Ungu) -->
    <div class="absolute -top-16 -right-16 w-80 h-80 rounded-full bg-navy-200/40 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-16 w-72 h-72 rounded-full bg-blue-200/35 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 -right-12 w-60 h-60 rounded-full bg-navy-400/20 blur-2xl pointer-events-none"></div>

    <!-- Elemen Geometris Lingkaran Konsentris Halus di Latar Belakang -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-35">
        <div class="w-96 h-96 rounded-full border border-navy-800/10 flex items-center justify-center">
            <div class="w-72 h-72 rounded-full border border-navy-800/10 flex items-center justify-center">
                <div class="w-48 h-48 rounded-full border border-navy-800/10 flex items-center justify-center">
                    <div class="w-24 h-24 rounded-full border border-navy-800/10"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Atas Visual -->
    <div class="relative z-10 flex items-center {{ !empty($badge) ? 'justify-between' : 'justify-end' }}">
        @if (!empty($badge))
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 backdrop-blur-md border border-white/90 text-navy-800 text-[11px] font-bold shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ $badge }}
            </div>
        @endif
        <span class="text-[11px] font-semibold text-slate-500">Lost &amp; Found</span>
    </div>

    <!-- Bagian Tengah Visual: Glass Card Elegan & Minimalis -->
    <div class="relative z-10 my-auto py-6">
        <div class="p-6 sm:p-7 rounded-2xl bg-white/80 backdrop-blur-md border border-white/90 shadow-lg space-y-4">
            <!-- Ikon Badge TemuUNRI -->
            <div class="w-11 h-11 rounded-xl bg-navy-800 text-white flex items-center justify-center shadow-xs">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                </svg>
            </div>

            <!-- Slogan & Teks Pengantar -->
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-navy-950 leading-snug tracking-tight">
                    "{{ $quote }}"
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                    {{ $description }}
                </p>
            </div>

            <!-- 3 Poin Keunggulan Utama TemuUNRI -->
            <div class="pt-3 border-t border-slate-200/60 space-y-2.5">
                <div class="flex items-center space-x-2.5 text-xs font-semibold text-slate-700">
                    <div class="w-6 h-6 rounded-lg bg-navy-100 text-navy-800 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <span>{{ $highlight1 }}</span>
                </div>
                <div class="flex items-center space-x-2.5 text-xs font-semibold text-slate-700">
                    <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span>{{ $highlight2 }}</span>
                </div>
                <div class="flex items-center space-x-2.5 text-xs font-semibold text-slate-700">
                    <div class="w-6 h-6 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <span>{{ $highlight3 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Bawah Visual: Identitas Civitas Akademika -->
    <div class="relative z-10 pt-4 border-t border-slate-200/50 flex items-center justify-between text-[11px] text-slate-500 font-medium">
        <span>Universitas Riau</span>
        <span>Satu Tempat untuk Barang Kampus</span>
    </div>
</div>
