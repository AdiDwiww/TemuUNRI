<nav x-data="{ open: false }" class="bg-white border-b border-slate-200/80 sticky top-0 z-40 shadow-xs">
    <!-- Menu Navigasi Utama -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo / Identitas TemuUNRI -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('reports.index') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-navy-800 flex items-center justify-center text-white shadow-xs group-hover:bg-navy-900 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-navy-800 text-lg leading-tight tracking-tight group-hover:text-navy-900 transition-colors">
                                TemuUNRI
                            </span>
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest leading-none">
                                Lost & Found
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Tautan Navigasi Desktop -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index') || request()->is('/')">
                        Beranda Laporan
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('claims.index')" :active="request()->routeIs('claims.*')">
                        Klaim Saya
                    </x-nav-link>
                    <x-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')">
                        Pesan
                    </x-nav-link>
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                            <span class="inline-flex items-center text-rose-600 font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 me-1.5"></span>
                                Panel Admin
                            </span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Bagian Kanan Desktop: Buat Laporan & Akun -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-3">
                <a href="{{ route('reports.create') }}" class="inline-flex items-center px-3.5 py-1.5 border border-transparent text-xs font-semibold rounded-lg text-white bg-navy-800 hover:bg-navy-900 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-navy-700 shadow-xs transition-colors">
                    <svg class="w-3.5 h-3.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Lapor Barang
                </a>

                <!-- Dropdown Pengaturan Akun -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-2.5 py-1.5 border border-slate-200 text-xs font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 hover:text-navy-800 focus:outline-hidden transition ease-in-out duration-150">
                            <div class="w-6 h-6 rounded-full bg-navy-100 text-navy-800 font-bold flex items-center justify-center me-2 text-[11px]">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="max-w-[120px] truncate">{{ Auth::user()->name }}</div>
                            <div class="ms-1.5 text-slate-400">
                                <svg class="fill-current h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="text-xs">
                            Profil Saya
                        </x-dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-xs text-rose-600 hover:text-rose-700 hover:bg-rose-50">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Tombol Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-hidden transition duration-150 ease-in-out" aria-label="Menu Utama">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Navigasi Mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-slate-200">
        <div class="px-4 pt-3 pb-2 border-b border-slate-100">
            <a href="{{ route('reports.create') }}" class="w-full flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg text-white bg-navy-800 hover:bg-navy-900 shadow-xs transition-colors">
                <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                + Buat Laporan Barang
            </a>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index') || request()->is('/')">
                Beranda Laporan
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('claims.index')" :active="request()->routeIs('claims.*')">
                Klaim Saya
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')">
                Pesan
            </x-responsive-nav-link>
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                    <span class="text-rose-600 font-semibold">Panel Admin</span>
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Pengaturan Akun Mobile -->
        <div class="pt-4 pb-3 border-t border-slate-200 bg-slate-50/50">
            <div class="px-4 flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-navy-100 text-navy-800 font-bold flex items-center justify-center text-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="font-medium text-sm text-slate-800 truncate">{{ Auth::user()->name }}</div>
                    <div class="font-normal text-xs text-slate-500 truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profil Saya
                </x-responsive-nav-link>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-rose-600">
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
