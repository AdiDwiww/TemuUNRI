<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 bg-[#F4F7FB]">
        <div class="mb-6">
            <a href="{{ route('reports.index') }}" class="flex items-center space-x-2.5 group">
                <div class="w-10 h-10 rounded-xl bg-navy-800 flex items-center justify-center text-white shadow-xs group-hover:bg-navy-900 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-navy-800 text-lg leading-tight tracking-tight">TemuUNRI</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Lost & Found</span>
                </div>
            </a>
        </div>

        <div class="w-full sm:max-w-md p-6 sm:p-8 bg-white shadow-xl rounded-3xl border border-slate-200/80">
            <h2 class="text-xl font-bold text-slate-900 mb-2">Lupa Kata Sandi</h2>
            <div class="mb-4 text-xs sm:text-sm text-slate-500 leading-relaxed">
                {{ __('Lupa kata sandi Anda? Tidak masalah. Berikan alamat email Anda yang terdaftar dan kami akan mengirimkan tautan reset kata sandi.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input id="email" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 focus:border-navy-800 focus:ring-1 focus:ring-navy-800 shadow-xs"
                           type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="nama@student.unri.ac.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-800 transition-colors">
                        Kembali ke Login
                    </a>
                    <button type="submit" class="py-2.5 px-4 bg-navy-800 hover:bg-navy-900 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors">
                        Kirim Link Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
