<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">
                    Edit Laporan: {{ Str::limit($report->judul, 40) }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Perbarui rincian informasi, lokasi, tanggal, atau foto bukti barang Anda.
                </p>
            </div>
            <div>
                <a href="{{ route('reports.show', $report->id) }}"
                   class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-navy-800 transition-colors shadow-xs">
                    <svg class="w-4 h-4 me-1.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Lihat Detail Laporan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F7F9FC]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi Status Menunggu --}}
            <div class="mb-6 bg-blue-50 border border-blue-200/80 rounded-2xl p-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-blue-900">Status Laporan: Menunggu Persetujuan (Pending)</div>
                        <p class="text-[11px] text-blue-700">Laporan dapat diperbarui selama belum diproses oleh tim admin TemuUNRI.</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 uppercase shrink-0">
                    {{ $report->status }}
                </span>
            </div>

            {{-- Alert Error Validasi --}}
            @if ($errors->any())
                <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-xs text-rose-800">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-rose-500 me-2.5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h4 class="font-bold text-sm">Terdapat kesalahan pada input:</h4>
                            <ul class="list-disc ml-5 mt-1 text-xs space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Kartu Utama Form Edit Laporan --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                <form action="{{ route('reports.update', $report->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Bagian 1: Jenis Laporan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jenis Laporan <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-xs text-slate-500 mb-3">Tentukan apakah laporan ini merupakan barang hilang atau penemuan barang.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="tipe-group">
                            {{-- Opsi Barang Hilang --}}
                            <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ old('tipe', $report->tipe) === 'hilang' ? 'border-rose-500 bg-rose-50/40 text-rose-950' : 'border-slate-200 bg-white hover:border-slate-300 text-slate-700' }} tipe-card">
                                <input type="radio" name="tipe" value="hilang" class="sr-only" {{ old('tipe', $report->tipe) === 'hilang' ? 'checked' : '' }} required>
                                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 me-3">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-sm">Barang Hilang</div>
                                    <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Saya mencari barang milik saya yang hilang</p>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-rose-500 flex items-center justify-center radio-check {{ old('tipe', $report->tipe) === 'hilang' ? '' : 'hidden' }}">
                                    <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                                </div>
                            </label>

                            {{-- Opsi Penemuan Barang --}}
                            <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ old('tipe', $report->tipe) === 'penemuan' ? 'border-emerald-500 bg-emerald-50/40 text-emerald-950' : 'border-slate-200 bg-white hover:border-slate-300 text-slate-700' }} tipe-card">
                                <input type="radio" name="tipe" value="penemuan" class="sr-only" {{ old('tipe', $report->tipe) === 'penemuan' ? 'checked' : '' }} required>
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 me-3">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-sm">Penemuan Barang</div>
                                    <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Saya menemukan barang tercecer / tertinggal</p>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-emerald-500 flex items-center justify-center radio-check {{ old('tipe', $report->tipe) === 'penemuan' ? '' : 'hidden' }}">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                </div>
                            </label>
                        </div>
                        @error('tipe')
                            <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bagian 2: Judul Laporan --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Judul Laporan <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">Jelas dan ringkas</span>
                        </div>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $report->judul) }}"
                               placeholder="Contoh: Dompet Hitam Eiger Berisi KTM dan KTP"
                               class="w-full rounded-xl border-slate-300 text-slate-800 text-sm focus:border-navy-800 focus:ring-navy-800 shadow-xs"
                               required maxlength="255">
                        <p class="text-[11px] text-slate-500 mt-1">Sertakan nama barang dan ciri yang paling mudah dikenali.</p>
                        @error('judul')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bagian 3: Kategori Barang & Tanggal Kejadian --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="kategori" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Kategori Barang <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="kategori" name="kategori" value="{{ old('kategori', $report->kategori) }}"
                                   placeholder="Contoh: Dompet, Kunci, HP, Tas"
                                   class="w-full rounded-xl border-slate-300 text-slate-800 text-sm focus:border-navy-800 focus:ring-navy-800 shadow-xs"
                                   required>
                            {{-- Chip Bantuan Cepat --}}
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @foreach(['Dompet', 'Kunci', 'Handphone', 'KTM/Dokumen', 'Tas', 'Laptop', 'Lainnya'] as $kat)
                                    <button type="button"
                                            onclick="document.getElementById('kategori').value='{{ $kat }}'"
                                            class="text-[10px] font-medium px-2 py-0.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                                        + {{ $kat }}
                                    </button>
                                @endforeach
                            </div>
                            @error('kategori')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_kejadian" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tanggal Kejadian <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="tanggal_kejadian" name="tanggal_kejadian"
                                   value="{{ old('tanggal_kejadian', $report->tanggal_kejadian) }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full rounded-xl border-slate-300 text-slate-800 text-sm focus:border-navy-800 focus:ring-navy-800 shadow-xs"
                                   required>
                            <p class="text-[11px] text-slate-500 mt-1">Perkiraan waktu barang hilang atau ditemukan.</p>
                            @error('tanggal_kejadian')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Bagian 4: Lokasi Kejadian --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="lokasi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Lokasi Kejadian / Penemuan <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">Area kampus UNRI</span>
                        </div>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $report->lokasi) }}"
                               placeholder="Contoh: Kantin Fakultas Teknik / Halte Bus UNRI Dekat Rektorat"
                               class="w-full rounded-xl border-slate-300 text-slate-800 text-sm focus:border-navy-800 focus:ring-navy-800 shadow-xs"
                               required>
                        <p class="text-[11px] text-slate-500 mt-1">Tuliskan nama gedung, lantai, ruangan, atau patokan tempat yang spesifik.</p>
                        @error('lokasi')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bagian 5: Deskripsi Lengkap & Ciri-ciri --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Deskripsi Lengkap & Ciri-Ciri <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">Detail & spesifik</span>
                        </div>
                        <textarea id="deskripsi" name="deskripsi" rows="5"
                                  placeholder="Jelaskan ciri-ciri fisik barang secara rinci seperti warna, merek, gantungan kunci, stiker, isi di dalamnya, atau kronologi kejadian..."
                                  class="w-full rounded-xl border-slate-300 text-slate-800 text-sm focus:border-navy-800 focus:ring-navy-800 shadow-xs"
                                  required>{{ old('deskripsi', $report->deskripsi) }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Informasi yang detail mempermudah verifikasi kepemilikan barang.</p>
                        @error('deskripsi')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bagian 6: Ganti Foto Barang --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Foto Bukti Barang
                        </label>
                        <p class="text-xs text-slate-500 mb-3">Foto lama tetap digunakan jika Anda tidak mengunggah foto baru.</p>

                        {{-- Tampilan Foto Saat Ini (Jika ada) --}}
                        @if($report->foto)
                            <div class="mb-4 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $report->foto) }}" alt="Foto Saat Ini"
                                     class="w-20 h-20 object-cover rounded-lg border border-slate-200 shadow-xs shrink-0">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                            Foto Tersimpan
                                        </span>
                                    </div>
                                    <p class="text-xs font-medium text-slate-700 mt-1">Foto ini sedang aktif pada postingan Anda.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Unggah foto baru di bawah ini hanya jika ingin menggantinya.</p>
                                </div>
                            </div>
                        @endif

                        {{-- Dropzone Upload Foto Baru --}}
                        <div class="border-2 border-dashed border-slate-300 hover:border-navy-800 rounded-2xl p-6 text-center transition-colors bg-slate-50/60" id="dropzone">
                            {{-- Area Preview Ketika Foto Terpilih --}}
                            <div id="preview-container" class="hidden mb-4">
                                <img id="foto-preview-img" src="#" alt="Preview Foto Baru" class="max-h-56 mx-auto rounded-xl object-contain shadow-sm border border-slate-200">
                                <button type="button" id="remove-preview-btn" class="mt-2.5 inline-flex items-center text-xs font-semibold text-rose-600 hover:text-rose-700">
                                    <svg class="w-3.5 h-3.5 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Batalkan Foto Baru
                                </button>
                            </div>

                            {{-- Area Placeholder Upload --}}
                            <div id="upload-placeholder" class="space-y-2">
                                <div class="w-12 h-12 mx-auto rounded-full bg-navy-50 text-navy-800 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="text-xs text-slate-700">
                                    <label for="foto" class="font-bold text-navy-800 hover:underline cursor-pointer">
                                        Klik untuk memilih foto baru
                                    </label>
                                    <span class="text-slate-500">atau seret file ke sini</span>
                                </div>
                                <p class="text-[11px] text-slate-400">Mendukung format JPG, JPEG, PNG, WEBP (Maksimal 2 MB). Kosongkan jika tidak mengganti foto.</p>
                            </div>

                            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                        </div>
                        @error('foto')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('reports.show', $report->id) }}"
                           class="inline-flex items-center px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-xs">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-navy-800 hover:bg-navy-900 text-white rounded-xl text-xs font-semibold transition-colors shadow-xs">
                            <svg class="w-4 h-4 me-1.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script Interaksi UI: Tipe Selector & Preview Foto --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tipe Selector Card Interactive Highlight
            const tipeCards = document.querySelectorAll('.tipe-card');
            tipeCards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                card.addEventListener('click', function () {
                    tipeCards.forEach(c => {
                        const r = c.querySelector('input[type="radio"]');
                        const check = c.querySelector('.radio-check');
                        if (r.value === 'hilang') {
                            c.classList.remove('border-rose-500', 'bg-rose-50/40', 'text-rose-950');
                            c.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
                        } else {
                            c.classList.remove('border-emerald-500', 'bg-emerald-50/40', 'text-emerald-950');
                            c.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
                        }
                        if (check) check.classList.add('hidden');
                    });

                    radio.checked = true;
                    const activeCheck = card.querySelector('.radio-check');
                    if (activeCheck) activeCheck.classList.remove('hidden');

                    if (radio.value === 'hilang') {
                        card.classList.add('border-rose-500', 'bg-rose-50/40', 'text-rose-950');
                        card.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
                    } else {
                        card.classList.add('border-emerald-500', 'bg-emerald-50/40', 'text-emerald-950');
                        card.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
                    }
                });
            });

            // Live Image Preview & Drag and Drop
            const fotoInput = document.getElementById('foto');
            const previewContainer = document.getElementById('preview-container');
            const previewImg = document.getElementById('foto-preview-img');
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const removePreviewBtn = document.getElementById('remove-preview-btn');
            const dropzone = document.getElementById('dropzone');

            function handleFile(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        uploadPlaceholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }

            if (fotoInput) {
                fotoInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        handleFile(this.files[0]);
                    }
                });
            }

            if (removePreviewBtn) {
                removePreviewBtn.addEventListener('click', function () {
                    fotoInput.value = '';
                    previewImg.src = '#';
                    previewContainer.classList.add('hidden');
                    uploadPlaceholder.classList.remove('hidden');
                });
            }

            if (dropzone) {
                dropzone.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    dropzone.classList.add('border-navy-800', 'bg-navy-50/30');
                });
                dropzone.addEventListener('dragleave', function (e) {
                    e.preventDefault();
                    dropzone.classList.remove('border-navy-800', 'bg-navy-50/30');
                });
                dropzone.addEventListener('drop', function (e) {
                    e.preventDefault();
                    dropzone.classList.remove('border-navy-800', 'bg-navy-50/30');
                    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                        fotoInput.files = e.dataTransfer.files;
                        handleFile(e.dataTransfer.files[0]);
                    }
                });
            }
        });
    </script>
</x-app-layout>
