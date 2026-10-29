<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <nav class="flex items-center text-xs text-slate-400 mb-1 space-x-1.5" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:text-navy-800 transition-colors">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('reports.show', $report) }}" class="hover:text-navy-800 transition-colors">Laporan</a>
                    <span>/</span>
                    <span class="text-slate-600 font-medium">Ajukan Klaim</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Ajukan Klaim Kepemilikan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Sampaikan ciri khusus barang untuk diverifikasi oleh pelapor barang temuan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F7F9FC] min-h-[calc(100vh-140px)]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 sm:p-8 space-y-6">
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 text-xs sm:text-sm rounded-xl">
                        <p class="font-bold">Terjadi kesalahan input:</p>
                        <ul class="list-disc ml-5 mt-1 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 text-xs sm:text-sm space-y-1">
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Laporan Penemuan Barang</p>
                    <p class="font-bold text-slate-900 text-base">{{ $report->judul }}</p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-600 pt-1">
                        <span>Lokasi: <strong class="text-slate-800 font-medium">{{ $report->lokasi }}</strong></span>
                        <span>&bull;</span>
                        <span>Pelapor: <strong class="text-slate-800 font-medium">{{ $report->user->name ?? 'Anonim' }}</strong></span>
                    </div>
                </div>

                <form action="{{ route('claims.store', $report) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="description" class="block font-semibold text-xs sm:text-sm text-slate-800 mb-1.5">
                            Keterangan / Ciri Khusus Kepemilikan <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="5" required minlength="10"
                            placeholder="Jelaskan ciri khusus barang yang hanya diketahui pemilik sah, misalnya isi dalam tas, tanda pengenal, nomor seri, goresan tertentu, atau kelengkapan dokumen pendukung."
                            class="w-full border-slate-300 focus:border-navy-700 focus:ring-navy-700 rounded-lg text-xs sm:text-sm text-slate-800 shadow-2xs placeholder-slate-400">{{ old('description') }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1.5">Minimal 10 karakter. Pelapor barang temuan akan menggunakan keterangan ini untuk memverifikasi klaim kepemilikan Anda.</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('reports.show', $report) }}"
                           class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg text-xs font-semibold transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="bg-navy-800 hover:bg-navy-900 text-white px-5 py-2 rounded-lg text-xs font-semibold shadow-xs transition">
                            Kirim Pengajuan Klaim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
