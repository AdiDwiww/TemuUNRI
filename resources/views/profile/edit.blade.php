<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">
                    Pengaturan Profil
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Kelola informasi data diri, foto profil, dan pengaturan keamanan akun TemuUNRI Anda.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-navy-800 transition-colors shadow-xs">
                    <svg class="w-4 h-4 me-1.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F7F9FC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Kartu Ringkasan Pengguna (Profile Hero) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 lg:p-8">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    {{-- Avatar Besar --}}
                    <div class="relative shrink-0 flex items-center justify-center" style="width: 100px; height: 100px; min-width: 100px; min-height: 100px; max-width: 100px; max-height: 100px;">
                        <img id="header-avatar-preview"
                             src="{{ $user->foto_profil ? Storage::url($user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1e3a5f&color=fff&size=128' }}"
                             alt="{{ $user->name }}"
                             style="width: 100px; height: 100px; min-width: 100px; min-height: 100px; max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 9999px;"
                             class="w-24 h-24 rounded-full object-cover ring-4 ring-slate-100 shadow-sm" />
                        <span class="absolute bottom-1 right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white" title="Akun Aktif"></span>
                    </div>

                    {{-- Informasi Singkat --}}
                    <div class="flex-1 text-center md:text-left space-y-3">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <h1 class="text-xl md:text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
                                <p class="text-xs text-slate-500 mt-0.5">NIM: <span class="font-semibold text-slate-800">{{ $user->nim ?? '-' }}</span></p>
                            </div>
                            <div class="flex flex-wrap justify-center md:justify-end gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-navy-50 text-navy-800 border border-navy-100">
                                    <svg class="w-3.5 h-3.5 me-1 text-navy-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $user->isAdmin() ? 'Administrator' : 'Mahasiswa UNRI' }}
                                </span>
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <svg class="w-3.5 h-3.5 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Email Terverifikasi
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Metadata Cards --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                            <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 text-left">
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Fakultas</p>
                                <p class="text-xs font-semibold text-slate-800 mt-0.5 truncate">{{ $user->fakultas ?? 'Belum diisi' }}</p>
                            </div>
                            <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 text-left">
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Program Studi</p>
                                <p class="text-xs font-semibold text-slate-800 mt-0.5 truncate">{{ $user->program_studi ?? 'Belum diisi' }}</p>
                            </div>
                            <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 text-left">
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Nomor Kontak (HP)</p>
                                <p class="text-xs font-semibold text-slate-800 mt-0.5 truncate">{{ $user->no_hp ?? 'Belum diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid Konten Utama: 2 Kolom di Layar Besar --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri: Form Informasi Profil & Upload Foto (Col-span 2) --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-navy-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Informasi Profil
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">Perbarui nama, kontak, fakultas, program studi, dan foto profil akun Anda.</p>
                            </div>

                            @if (session('status') === 'profile-updated')
                                <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Tersimpan
                                </span>
                            @endif
                            @if (session('status') === 'foto-deleted')
                                <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    Foto Dihapus
                                </span>
                            @endif
                        </div>

                        {{-- Bagian Upload & Preview Foto Profil --}}
                        <div class="mb-8 p-4 rounded-xl bg-slate-50/70 border border-slate-200/60">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Foto Profil</label>
                            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                                {{-- Preview Foto --}}
                                <div class="relative shrink-0 flex items-center justify-center" style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; max-width: 80px; max-height: 80px;">
                                    <img id="foto-preview"
                                         src="{{ $user->foto_profil ? Storage::url($user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1e3a5f&color=fff&size=96' }}"
                                         alt="Preview Foto Profil"
                                         style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 9999px;"
                                         class="w-20 h-20 rounded-full object-cover ring-2 ring-white shadow-sm border border-slate-200" />
                                </div>

                                {{-- Aksi Foto & Panduan --}}
                                <div class="flex-1 space-y-2 text-center sm:text-left">
                                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                                        <label for="foto_profil_input"
                                               class="inline-flex items-center px-3.5 py-2 text-xs font-semibold rounded-lg cursor-pointer bg-navy-800 text-white hover:bg-navy-900 transition-colors shadow-xs">
                                            <svg class="w-3.5 h-3.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Ganti Foto
                                        </label>

                                        @if ($user->foto_profil)
                                            <button type="button"
                                                    onclick="if(confirm('Hapus foto profil dan gunakan avatar default?')) document.getElementById('form-hapus-foto').submit();"
                                                    class="inline-flex items-center px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors shadow-xs">
                                                <svg class="w-3.5 h-3.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus Foto
                                            </button>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-500">Mendukung format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 2 MB.</p>
                                    <x-input-error class="mt-1" :messages="$errors->get('foto_profil')" />
                                </div>
                            </div>
                        </div>

                        {{-- Form Hidden untuk Hapus Foto --}}
                        @if ($user->foto_profil)
                            <form method="POST" action="{{ route('profile.foto.destroy') }}" id="form-hapus-foto" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif

                        {{-- Form Utama Update Profil --}}
                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            @method('patch')

                            {{-- File input tersembunyi yang di-trigger tombol Ganti Foto --}}
                            <input type="file" id="foto_profil_input" name="foto_profil"
                                   accept="image/jpeg,image/png,image/webp" class="hidden" />

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Nama Lengkap --}}
                                <div>
                                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                    <x-text-input id="name" name="name" type="text" class="block w-full"
                                                  :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                    <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
                                </div>

                                {{-- NIM --}}
                                <div>
                                    <label for="nim" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">NIM (Nomor Induk Mahasiswa)</label>
                                    <x-text-input id="nim" name="nim" type="text" class="block w-full"
                                                  :value="old('nim', $user->nim)" required
                                                  placeholder="Contoh: 2410123456" />
                                    <x-input-error class="mt-1.5" :messages="$errors->get('nim')" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                                    <x-text-input id="email" name="email" type="email" class="block w-full"
                                                  :value="old('email', $user->email)" required autocomplete="username" />
                                    <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div class="mt-2 text-xs text-amber-700 bg-amber-50 p-2.5 rounded-lg border border-amber-200">
                                            Email belum diverifikasi.
                                            <button form="send-verification" class="underline font-semibold hover:text-amber-900">
                                                Kirim ulang verifikasi.
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                {{-- Nomor HP --}}
                                <div>
                                    <label for="no_hp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor HP (Opsional)</label>
                                    <x-text-input id="no_hp" name="no_hp" type="tel" class="block w-full"
                                                  :value="old('no_hp', $user->no_hp)" autocomplete="tel"
                                                  placeholder="Contoh: 08123456789" />
                                    <x-input-error class="mt-1.5" :messages="$errors->get('no_hp')" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Fakultas --}}
                                <div>
                                    <label for="fakultas" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Fakultas</label>
                                    <x-text-input id="fakultas" name="fakultas" type="text" class="block w-full"
                                                  :value="old('fakultas', $user->fakultas)" required
                                                  placeholder="Contoh: Fakultas Teknik" />
                                    <x-input-error class="mt-1.5" :messages="$errors->get('fakultas')" />
                                </div>

                                {{-- Program Studi --}}
                                <div>
                                    <label for="program_studi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Program Studi</label>
                                    <x-text-input id="program_studi" name="program_studi" type="text" class="block w-full"
                                                  :value="old('program_studi', $user->program_studi)" required
                                                  placeholder="Contoh: Teknik Informatika" />
                                    <x-input-error class="mt-1.5" :messages="$errors->get('program_studi')" />
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a href="{{ route('profile.edit') }}"
                                   class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-xs">
                                    Batal
                                </a>

                                <button type="submit"
                                        class="inline-flex items-center px-5 py-2.5 bg-navy-800 hover:bg-navy-900 text-white rounded-lg text-xs font-semibold transition-colors shadow-xs">
                                    <svg class="w-4 h-4 me-1.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>

                        <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>

                {{-- Kolom Kanan: Pengaturan Password & Hapus Akun (Col-span 1) --}}
                <div class="space-y-6">

                    {{-- Card Ubah Kata Sandi --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="border-b border-slate-100 pb-3 mb-5">
                            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Ubah Kata Sandi
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pastikan akun menggunakan kata sandi yang kuat dan aman.</p>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')

                            {{-- Password Saat Ini --}}
                            <div>
                                <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Saat Ini</label>
                                <x-text-input id="update_password_current_password" name="current_password"
                                              type="password" class="block w-full" autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
                            </div>

                            {{-- Password Baru --}}
                            <div>
                                <label for="update_password_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Baru</label>
                                <x-text-input id="update_password_password" name="password"
                                              type="password" class="block w-full" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
                            </div>

                            {{-- Konfirmasi Password Baru --}}
                            <div>
                                <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru</label>
                                <x-text-input id="update_password_password_confirmation" name="password_confirmation"
                                              type="password" class="block w-full" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition-colors shadow-xs">
                                    <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Simpan Kata Sandi
                                </button>
                            </div>

                            @if (session('status') === 'password-updated')
                                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                                     class="p-2.5 text-xs text-center font-medium text-emerald-700 bg-emerald-50 rounded-lg border border-emerald-200">
                                    Kata sandi berhasil diperbarui.
                                </div>
                            @endif
                        </form>
                    </div>

                    {{-- Card Hapus Akun (Zona Bahaya) --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-base font-bold text-rose-700 flex items-center gap-2">
                                <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Akun
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Setelah akun dihapus, seluruh data dan laporan Anda akan dihapus secara permanen.</p>
                        </div>

                        <x-danger-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                            class="w-full justify-center text-xs py-2.5"
                        >
                            Hapus Akun Saya
                        </x-danger-button>

                        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-bold text-slate-900">
                                    Apakah Anda yakin ingin menghapus akun?
                                </h2>

                                <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                                    Tindakan ini tidak dapat dibatalkan. Semua laporan, klaim, dan data akun TemuUNRI Anda akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk konfirmasi.
                                </p>

                                <div class="mt-5">
                                    <x-input-label for="password" value="Kata Sandi Konfirmasi" class="sr-only" />

                                    <x-text-input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="block w-full"
                                        placeholder="Masukkan kata sandi akun Anda"
                                    />

                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        Batal
                                    </x-secondary-button>

                                    <x-danger-button>
                                        Ya, Hapus Akun Saya
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script Live Preview Foto --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fotoInput = document.getElementById('foto_profil_input');
            const fotoPreview = document.getElementById('foto-preview');
            const headerAvatarPreview = document.getElementById('header-avatar-preview');

            if (fotoInput) {
                fotoInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            if (fotoPreview) fotoPreview.src = e.target.result;
                            if (headerAvatarPreview) headerAvatarPreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</x-app-layout>
