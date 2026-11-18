<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-[#F4F7FB]">
        <!-- Container Utama Split-Screen Persegi Panjang Membulat -->
        <div class="w-full max-w-5xl lg:max-w-6xl bg-white rounded-3xl shadow-xl sm:shadow-2xl overflow-hidden border border-slate-200/80 grid grid-cols-1 lg:grid-cols-12 min-h-[680px] transition-all">

            <!-- Kolom Kiri: Form Registrasi (Background Putih) -->
            <div class="lg:col-span-7 flex flex-col justify-between p-6 sm:p-9 lg:p-11 bg-white">
                <!-- Bagian Atas: Identitas TemuUNRI -->
                <div class="flex items-center mb-4 sm:mb-6">
                    <a href="{{ route('reports.index') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-navy-800 flex items-center justify-center text-white shadow-xs group-hover:bg-navy-900 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-navy-800 text-lg leading-tight tracking-tight group-hover:text-navy-900 transition-colors">
                                TemuUNRI
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">
                                Lost &amp; Found
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Bagian Tengah: Form Registrasi Terstruktur -->
                <div class="w-full max-w-xl mx-auto my-auto py-2 sm:py-4">
                    <!-- Heading & Deskripsi -->
                    <div class="mb-5 sm:mb-6">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Buat Akun TemuUNRI
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
                            Lengkapi data diri Anda sebagai mahasiswa atau civitas akademika Universitas Riau untuk mulai menggunakan layanan Lost &amp; Found.
                        </p>
                    </div>

                    <!-- Alert Error Validasi Global Jika Ada -->
                    @if ($errors->any())
                        <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 p-3.5 rounded-xl text-rose-800 text-xs">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-rose-500 me-2 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <span class="font-bold">Mohon periksa kembali input formulir Anda:</span>
                                    <ul class="list-disc ml-4 mt-1 space-y-0.5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Baris 1: Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                       placeholder="Nama lengkap sesuai KTM"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <!-- Baris 2: NIM & Nomor HP (Grid 2 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <!-- Input NIM -->
                            <div>
                                <label for="nim" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    NIM (Nomor Induk) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                    </div>
                                    <input id="nim" type="text" name="nim" value="{{ old('nim') }}" required autocomplete="off"
                                           pattern="[a-zA-Z0-9]+" title="NIM hanya boleh berisi angka atau huruf"
                                           placeholder="Contoh: 2401123456"
                                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                                </div>
                                <x-input-error :messages="$errors->get('nim')" class="mt-1" />
                            </div>

                            <!-- Input Nomor HP (Opsional) -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label for="no_hp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Nomor HP
                                    </label>
                                    <span class="text-[10px] text-slate-400">Opsional</span>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" autocomplete="tel"
                                           placeholder="Contoh: 08123456789"
                                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                                </div>
                                <x-input-error :messages="$errors->get('no_hp')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Baris 3: Alamat Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                       placeholder="nama@student.unri.ac.id"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <!-- Baris 4: Fakultas & Program Studi (Grid 2 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <!-- Input Fakultas -->
                            <div>
                                <label for="fakultas" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Fakultas <span class="text-rose-500">*</span>
                                </label>
                                <input id="fakultas" type="text" name="fakultas" value="{{ old('fakultas') }}" required
                                       placeholder="Contoh: Fakultas Teknik"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                                <x-input-error :messages="$errors->get('fakultas')" class="mt-1" />
                            </div>

                            <!-- Input Program Studi -->
                            <div>
                                <label for="program_studi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Program Studi <span class="text-rose-500">*</span>
                                </label>
                                <input id="program_studi" type="text" name="program_studi" value="{{ old('program_studi') }}" required
                                       placeholder="Contoh: Teknik Informatika"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                                <x-input-error :messages="$errors->get('program_studi')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Baris 5: Kata Sandi & Konfirmasi Kata Sandi (Grid 2 Kolom dengan Toggle Show/Hide) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <!-- Kata Sandi -->
                            <div x-data="{ show: false }">
                                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Kata Sandi <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                                           placeholder="Minimal 8 karakter"
                                           class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                                    <button type="button" @click="show = !show"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-navy-800 transition-colors focus:outline-none"
                                            aria-label="Tampilkan atau sembunyikan kata sandi">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>

                            <!-- Konfirmasi Kata Sandi -->
                            <div x-data="{ show: false }">
                                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Konfirmasi Sandi <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                                           placeholder="Ulangi kata sandi"
                                           class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                                    <button type="button" @click="show = !show"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-navy-800 transition-colors focus:outline-none"
                                            aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Tombol Submit Daftar -->
                        <div class="pt-3">
                            <button type="submit"
                                    class="w-full py-3 px-4 bg-navy-800 hover:bg-navy-900 text-white font-semibold text-sm rounded-xl shadow-xs hover:shadow-md transition-all duration-200 flex items-center justify-center space-x-2 focus:outline-none focus:ring-2 focus:ring-navy-800 focus:ring-offset-2">
                                <span>Daftar Akun Sekarang</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Bagian Bawah: Link Menuju Login -->
                <div class="pt-4 border-t border-slate-100 text-center">
                    <p class="text-xs sm:text-sm text-slate-600">
                        Sudah memiliki akun terdaftar?
                        <a href="{{ route('login') }}" class="font-bold text-navy-800 hover:text-navy-900 hover:underline transition-colors">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>

            <!-- Kolom Kanan: Area Visual Abstrak (Tema Navy/Slate TemuUNRI) -->
            @include('auth.partials.auth-visual', [
                'quote' => 'Satu Tempat untuk Barang Hilang dan Ditemukan.',
                'description' => 'Bergabunglah dengan ribuan mahasiswa dan staf Universitas Riau dalam mewujudkan ekosistem kampus yang peduli, jujur, dan amanah.',
                'highlight1' => 'Pelaporan Cepat & Upload Foto Rasio 4:5',
                'highlight2' => 'Sistem Verifikasi Klaim Transparan',
                'highlight3' => 'Pemberitahuan Status Laporan Terkini',
            ])

        </div>
    </div>
</x-guest-layout>
