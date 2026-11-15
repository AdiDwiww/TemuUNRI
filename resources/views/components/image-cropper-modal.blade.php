{{-- Reusable Image Cropper Modal for TemuUNRI (Cropper.js) --}}
<div id="cropper-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-5 bg-slate-950/80 backdrop-blur-xs transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl flex flex-col overflow-hidden max-h-[94vh] border border-slate-200/90 animate-in fade-in zoom-in-95 duration-150">
        {{-- Header Modal --}}
        <div class="px-5 py-3.5 bg-white border-b border-slate-100 flex items-center justify-between shrink-0">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-navy-50 text-navy-800 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-sm sm:text-base text-slate-900">Potong & Sesuaikan Foto</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-navy-100 text-navy-800">
                            4:5 Portrait
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500">Geser atau perbesar foto agar pas dengan format postingan TemuUNRI.</p>
                </div>
            </div>
            <button type="button" id="cropper-close-btn" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors" title="Tutup">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Area Cropper Canvas --}}
        <div class="relative bg-slate-950 flex items-center justify-center overflow-hidden" style="min-height: 280px; max-height: 52vh;">
            <div class="w-full h-full flex items-center justify-center p-2">
                <img id="cropper-modal-image" src="" alt="Pratinjau Crop" class="max-w-full max-h-[50vh] block mx-auto">
            </div>
        </div>

        {{-- Toolbar Navigasi & Kontrol Crop --}}
        <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-2 text-xs">
            <div class="flex items-center space-x-1 sm:space-x-1.5">
                <button type="button" id="cropper-zoom-in-btn" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-medium shadow-2xs transition-colors" title="Perbesar">
                    <svg class="w-3.5 h-3.5 me-1 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                    </svg>
                    Zoom In
                </button>
                <button type="button" id="cropper-zoom-out-btn" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-medium shadow-2xs transition-colors" title="Perkecil">
                    <svg class="w-3.5 h-3.5 me-1 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                    </svg>
                    Zoom Out
                </button>
                <button type="button" id="cropper-rotate-btn" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-medium shadow-2xs transition-colors" title="Putar 90 Derajat">
                    <svg class="w-3.5 h-3.5 me-1 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Putar
                </button>
            </div>

            <div>
                <button type="button" id="cropper-reset-btn" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-medium shadow-2xs transition-colors" title="Kembalikan posisi awal">
                    <svg class="w-3.5 h-3.5 me-1 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Atur Ulang
                </button>
            </div>
        </div>

        {{-- Footer Modal Aksi --}}
        <div class="px-5 py-3.5 bg-white border-t border-slate-100 flex items-center justify-between gap-3 shrink-0">
            <span class="text-[11px] text-slate-400 hidden sm:inline-block">
                Foto akan otomatis disimpan dalam format 4:5 berkualitas tinggi.
            </span>
            <div class="flex items-center space-x-2 ms-auto">
                <button type="button" id="cropper-cancel-btn" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-2xs">
                    Batal
                </button>
                <button type="button" id="cropper-confirm-btn" class="inline-flex items-center px-5 py-2 rounded-xl text-xs font-semibold text-white bg-navy-800 hover:bg-navy-900 transition-colors shadow-xs">
                    <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Gunakan Foto Ini
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Custom Style Terintegrasi untuk Cropper.js Theme TemuUNRI --}}
<style>
    .cropper-view-box {
        outline: 2px solid #1e3a5f !important;
        outline-color: rgba(30, 58, 95, 0.95) !important;
    }
    .cropper-line {
        background-color: #1e3a5f !important;
    }
    .cropper-point {
        background-color: #1e3a5f !important;
        width: 8px !important;
        height: 8px !important;
        opacity: 0.9 !important;
    }
    .cropper-point.point-se {
        width: 10px !important;
        height: 10px !important;
    }
</style>

