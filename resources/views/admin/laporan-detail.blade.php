<x-app-layout>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ===================== BREADCRUMB ===================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <nav class="flex items-center gap-2 text-xs text-slate-400">
                <a href="{{ route('reports.index') }}" class="hover:text-slate-600 transition-colors">TemuUNRI</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600 transition-colors">Panel Admin</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-indigo-600 font-medium">Detail Laporan — Tinjauan Admin</span>
            </nav>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-50 px-3 py-2 rounded-xl transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Panel Admin
            </a>
        </div>

        {{-- ===================== NOTIFIKASI TOAST ===================== --}}
        @if(session('success'))
            <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-slate-900 text-white text-xs font-semibold rounded-2xl shadow-2xl"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-rose-600 text-white text-xs font-semibold rounded-2xl shadow-2xl"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ===================== STATUS BANNER ===================== --}}
        @php
            $bannerConfig = match($laporan->status) {
                'pending' => ['cls' => 'bg-amber-50 border-amber-300 text-amber-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'msg' => 'Laporan ini menunggu persetujuan admin. Tinjau deskripsi dan foto sebelum mengambil keputusan.'],
                'proses'  => ['cls' => 'bg-blue-50 border-blue-300 text-blue-800',   'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'msg' => 'Laporan ini sudah disetujui dan tampil di beranda publik.'],
                'selesai' => ['cls' => 'bg-emerald-50 border-emerald-300 text-emerald-800', 'icon' => 'M5 13l4 4L19 7', 'msg' => 'Laporan ini sudah selesai dan masuk riwayat laporan selesai.'],
                'Ditolak' => ['cls' => 'bg-rose-50 border-rose-300 text-rose-800',  'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'msg' => 'Laporan ini telah ditolak dan tidak tampil di beranda publik.'],
                default   => ['cls' => 'bg-slate-50 border-slate-300 text-slate-700', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'msg' => 'Status laporan: ' . $laporan->status],
            };
        @endphp
        <div class="flex items-start gap-3 px-5 py-4 rounded-2xl border {{ $bannerConfig['cls'] }}">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $bannerConfig['icon'] }}"/>
            </svg>
            <p class="text-sm font-medium">{{ $bannerConfig['msg'] }}</p>
        </div>

        {{-- ===================== DETAIL CARD ===================== --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">

            {{-- Header Card --}}
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div>
                    @if($laporan->tipe === 'hilang')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100 mb-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Barang Hilang
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 mb-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Barang Temuan
                        </span>
                    @endif
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1 leading-snug">{{ $laporan->judul }}</h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Diposting oleh
                        <span class="font-semibold text-slate-700">{{ $laporan->user->name ?? 'Anonim' }}</span>
                        ({{ $laporan->user->email ?? '-' }})
                        pada {{ $laporan->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                @php
                    $statusConfig = match($laporan->status) {
                        'pending' => ['label' => 'Menunggu', 'dot' => 'bg-amber-500', 'cls' => 'bg-amber-50 text-amber-700 border-amber-200'],
                        'proses'  => ['label' => 'Aktif',    'dot' => 'bg-blue-500',  'cls' => 'bg-blue-50 text-blue-700 border-blue-200'],
                        'selesai' => ['label' => 'Selesai',  'dot' => 'bg-emerald-500','cls' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                        'Ditolak' => ['label' => 'Ditolak',  'dot' => 'bg-rose-500',  'cls' => 'bg-rose-50 text-rose-700 border-rose-200'],
                        default   => ['label' => $laporan->status, 'dot' => 'bg-slate-500', 'cls' => 'bg-slate-50 text-slate-700 border-slate-200'],
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border {{ $statusConfig['cls'] }} shrink-0">
                    <span class="w-2 h-2 rounded-full {{ $statusConfig['dot'] }}"></span>
                    {{ $statusConfig['label'] }}
                </span>
            </div>

            <div class="p-6 space-y-6">

                {{-- Foto Barang --}}
                @if($laporan->foto)
                    <div class="flex justify-center bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <img src="{{ asset('storage/' . $laporan->foto) }}"
                             alt="Foto Barang"
                             class="max-h-80 w-auto rounded-xl shadow-xs object-contain">
                    </div>
                @else
                    <div class="flex justify-center bg-slate-50 py-10 rounded-2xl border border-dashed border-slate-300 text-slate-400 text-sm">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Tidak ada foto yang diunggah
                        </div>
                    </div>
                @endif

                {{-- Info Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/60 space-y-3 text-sm">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Kategori</p>
                            <p class="font-semibold text-slate-800 mt-0.5">{{ $laporan->kategori }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Lokasi Kejadian</p>
                            <p class="font-semibold text-slate-800 mt-0.5">{{ $laporan->lokasi }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/60 space-y-3 text-sm">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Tanggal Kejadian</p>
                            <p class="font-semibold text-slate-800 mt-0.5">
                                {{ \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Terakhir Diperbarui</p>
                            <p class="font-semibold text-slate-800 mt-0.5">{{ $laporan->updated_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Lengkap --}}
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Deskripsi Lengkap</h3>
                    <div class="bg-slate-50 p-4 rounded-2xl text-slate-800 text-sm whitespace-pre-line leading-relaxed border border-slate-200/60">
                        {{ $laporan->deskripsi }}
                    </div>
                </div>

                {{-- Daftar Klaim (jika ada) --}}
                @if($laporan->claims && $laporan->claims->count() > 0)
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Klaim Masuk ({{ $laporan->claims->count() }})
                        </h3>
                        <div class="space-y-3">
                            @foreach($laporan->claims as $klaim)
                                @php
                                    $klaimCls = match($klaim->status) {
                                        'pending'  => 'border-amber-200 bg-amber-50',
                                        'accepted' => 'border-emerald-200 bg-emerald-50',
                                        'rejected' => 'border-rose-200 bg-rose-50',
                                        default    => 'border-slate-200 bg-slate-50',
                                    };
                                    $klaimLabel = match($klaim->status) {
                                        'pending'  => 'Menunggu',
                                        'accepted' => 'Diterima',
                                        'rejected' => 'Ditolak',
                                        default    => $klaim->status,
                                    };
                                @endphp
                                <div class="p-4 rounded-2xl border {{ $klaimCls }} text-sm space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="font-semibold text-slate-800">
                                            {{ $klaim->user->name ?? 'Anonim' }}
                                            <span class="text-xs font-normal text-slate-500 ml-1">({{ $klaim->user->email ?? '-' }})</span>
                                        </div>
                                        <span class="text-[11px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full
                                            {{ $klaim->status === 'pending' ? 'bg-amber-200 text-amber-800' : ($klaim->status === 'accepted' ? 'bg-emerald-200 text-emerald-800' : 'bg-rose-200 text-rose-800') }}">
                                            {{ $klaimLabel }}
                                        </span>
                                    </div>
                                    <p class="text-slate-600 text-xs leading-relaxed">{{ $klaim->description }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $klaim->created_at->format('d M Y, H:i') }} WIB</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tombol Tindakan Admin --}}
                <div class="pt-2 border-t border-slate-100">
                    @if($laporan->status === 'pending')
                        <p class="text-xs text-slate-500 mb-4">Laporan ini menunggu keputusan admin. Pastikan data telah ditinjau sebelum memutuskan.</p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <form action="{{ route('admin.laporan.setujui', $laporan->id) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Setujui laporan &quot;{{ addslashes($laporan->judul) }}&quot;? Laporan akan tampil di beranda publik.')">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-xs transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Setujui Laporan
                                </button>
                            </form>
                            <form action="{{ route('admin.laporan.tolak', $laporan->id) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Tolak laporan &quot;{{ addslashes($laporan->judul) }}&quot;?')">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-rose-600 hover:bg-rose-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-xs transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Tolak Laporan
                                </button>
                            </form>
                        </div>
                    @else
                        <p class="text-center text-sm text-slate-400 italic py-4">
                            Laporan sudah diproses dengan status
                            <span class="font-semibold text-slate-600">{{ $statusConfig['label'] }}</span>.
                            Tidak ada tindakan lebih lanjut.
                        </p>
                    @endif
                </div>

            </div>
        </div>

    </div>

</x-app-layout>
