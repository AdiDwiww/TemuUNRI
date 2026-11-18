<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-[#F4F7FB]">
        <!-- Container Utama Split-Screen Persegi Panjang Membulat -->
        <div class="w-full max-w-4xl lg:max-w-5xl bg-white rounded-3xl shadow-xl sm:shadow-2xl overflow-hidden border border-slate-200/80 grid grid-cols-1 lg:grid-cols-12 min-h-[600px] transition-all">

            <!-- Kolom Kiri: Form Autentikasi (Background Putih) -->
            <div class="lg:col-span-7 flex flex-col justify-between p-6 sm:p-10 lg:p-12 bg-white">
                <!-- Bagian Atas: Identitas TemuUNRI -->
                <div class="flex items-center">
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

                <!-- Bagian Tengah: Form Login Vertikal Centered -->
                <div class="w-full max-w-sm sm:max-w-md mx-auto my-auto py-8">
                    <!-- Heading & Deskripsi -->
                    <div class="mb-6">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Selamat Datang Kembali
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
                            Silakan masukkan akun Anda untuk mengakses pelaporan barang dan layanan TemuUNRI.
                        </p>
                    </div>

                    <!-- Status Sesi (Flash Message) -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Input Alamat Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                       placeholder="nama@student.unri.ac.id"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </div>

                        <!-- Input Kata Sandi dengan Tombol Tampil/Sembunyikan -->
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                       placeholder="••••••••"
                                       class="w-full pl-10 pr-11 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs transition-colors">
                                <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-navy-800 transition-colors focus:outline-none"
                                        aria-label="Tampilkan atau sembunyikan kata sandi">
                                    <!-- Eye Icon (Saat Kata Sandi Tersembunyi) -->
                                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Eye-Slash Icon (Saat Kata Sandi Ditampilkan) -->
                                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                        </div>

                        <!-- Ingat Saya & Lupa Kata Sandi -->
                        <div class="flex items-center justify-between pt-1">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                                <input id="remember_me" type="checkbox" name="remember"
                                       class="rounded-md border-slate-300 text-navy-800 shadow-xs focus:ring-navy-800 focus:ring-offset-0">
                                <span class="ms-2 text-xs sm:text-sm text-slate-600">Ingat Saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-xs sm:text-sm font-semibold text-navy-800 hover:text-navy-900 hover:underline transition-colors">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>

                        <!-- Tombol Masuk Utama -->
                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full py-3 px-4 bg-navy-800 hover:bg-navy-900 text-white font-semibold text-sm rounded-xl shadow-xs hover:shadow-md transition-all duration-200 flex items-center justify-center space-x-2 focus:outline-none focus:ring-2 focus:ring-navy-800 focus:ring-offset-2">
                                <span>Masuk ke Akun</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Bagian Bawah: Link Daftar Akun -->
                <div class="pt-4 border-t border-slate-100 text-center">
                    <p class="text-xs sm:text-sm text-slate-600">
                        Belum memiliki akun?
                        <a href="{{ route('register') }}" class="font-bold text-navy-800 hover:text-navy-900 hover:underline transition-colors">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>

            <!-- Kolom Kanan: Area Visual Abstrak (Tema Navy/Slate TemuUNRI) -->
            @include('auth.partials.auth-visual', [
                'quote' => 'Temukan Kembali, Temukan Bersama.',
                'description' => 'Satu wadah terpadu untuk saling membantu mengembalikan barang hilang dan tercecer di lingkungan Universitas Riau.',
                'highlight1' => 'Pencarian Cepat & Filter Kategori Real-Time',
                'highlight2' => 'Mediasi Klaim Akuntabel & Bukti Spesifik',
                'highlight3' => 'Komunikasi Privat Terproteksi (In-App Chat)',
            ])

        </div>
    </div>
</x-guest-layout>
