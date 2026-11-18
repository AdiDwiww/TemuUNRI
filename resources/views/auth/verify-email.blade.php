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
            <h2 class="text-xl font-bold text-slate-900 mb-2">Verifikasi Email Anda</h2>
            <div class="mb-4 text-xs sm:text-sm text-slate-500 leading-relaxed">
                {{ __('Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-semibold text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 p-3 rounded-xl">
                    {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda daftarkan.') }}
                </div>
            @endif

            <div class="mt-6 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="py-2.5 px-4 bg-navy-800 hover:bg-navy-900 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors">
                        {{ __('Kirim Ulang Email Verifikasi') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-navy-800 underline transition-colors">
                        {{ __('Keluar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
