<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel Admin — TemuUNRI
            </h2>
            <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                Admin
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Info Admin --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center text-red-700 font-bold text-xl uppercase select-none">
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Halo, {{ Auth::user()->name }}!</h1>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-red-600 font-semibold mt-0.5 uppercase tracking-wide">Administrator</p>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Statistik Laporan --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                {{-- Total --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-t-4 border-gray-400 col-span-2 md:col-span-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Laporan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $jumlahTotal }}</p>
                </div>

                {{-- Pending --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-t-4 border-yellow-400">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Menunggu</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $jumlahPending }}</p>
                    <p class="text-xs text-gray-400 mt-1">Perlu persetujuan</p>
                </div>

                {{-- Proses --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-t-4 border-blue-400">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Aktif</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $jumlahProses }}</p>
                    <p class="text-xs text-gray-400 mt-1">Di beranda publik</p>
                </div>

                {{-- Selesai --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-t-4 border-emerald-400">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Selesai</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $jumlahSelesai }}</p>
                    <p class="text-xs text-gray-400 mt-1">Riwayat selesai</p>
                </div>

                {{-- Ditolak --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-t-4 border-red-400">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ditolak</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $jumlahDitolak }}</p>
                    <p class="text-xs text-gray-400 mt-1">Tidak tampil publik</p>
                </div>
            </div>

            {{-- Daftar Laporan Menunggu Persetujuan --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Laporan Menunggu Persetujuan</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Periksa setiap laporan sebelum menyetujui atau menolak.
                            @if($jumlahPending > 0)
                                <span class="font-semibold text-yellow-600">{{ $jumlahPending }} laporan menunggu.</span>
                            @else
                                <span class="text-emerald-600 font-semibold">Semua laporan sudah diproses ✓</span>
                            @endif
                        </p>
                    </div>
                    @if($jumlahPending > 0)
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-bold px-3 py-1 rounded-full">
                            {{ $jumlahPending }} Menunggu
                        </span>
                    @endif
                </div>

                @if($laporanPending->count() === 0)
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-emerald-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500 font-medium">Tidak ada laporan yang menunggu persetujuan.</p>
                        <p class="text-sm text-gray-400 mt-1">Semua laporan sudah diproses.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laporan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi & Tanggal</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($laporanPending as $laporan)
                                    <tr class="hover:bg-yellow-50 transition">
                                        {{-- Kolom Judul & Foto --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                @if($laporan->foto)
                                                    <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto" class="w-12 h-12 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-400 border flex-shrink-0">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-900 max-w-xs line-clamp-2">{{ $laporan->judul }}</div>
                                                    <div class="text-xs text-gray-400 mt-0.5">Dibuat {{ $laporan->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Tipe --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $laporan->tipe == 'hilang' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                {{ ucfirst($laporan->tipe) }}
                                            </span>
                                        </td>

                                        {{-- Kategori --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $laporan->kategori }}</td>

                                        {{-- Pelapor --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $laporan->user->name ?? 'Anonim' }}</div>
                                            <div class="text-xs text-gray-500">{{ $laporan->user->email ?? '-' }}</div>
                                        </td>

                                        {{-- Lokasi & Tanggal --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ $laporan->lokasi }}</div>
                                            <div class="text-xs text-gray-400">{{ $laporan->tanggal_kejadian }}</div>
                                        </td>

                                        {{-- Tindakan --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                {{-- Tombol Detail --}}
                                                <a href="{{ route('admin.laporan.show', $laporan->id) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-xs font-medium transition"
                                                   title="Lihat detail laporan">
                                                    Detail
                                                </a>

                                                {{-- Tombol Setujui --}}
                                                <form action="{{ route('admin.laporan.setujui', $laporan->id) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Setujui laporan &quot;{{ addslashes($laporan->judul) }}&quot;? Laporan akan tampil di beranda publik.')">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-md text-xs font-medium transition"
                                                            title="Setujui laporan ini">
                                                        ✓ Setujui
                                                    </button>
                                                </form>

                                                {{-- Tombol Tolak --}}
                                                <form action="{{ route('admin.laporan.tolak', $laporan->id) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Tolak laporan &quot;{{ addslashes($laporan->judul) }}&quot;? Laporan tidak akan tampil di beranda publik.')">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-red-700 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md text-xs font-medium transition"
                                                            title="Tolak laporan ini">
                                                        ✗ Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($laporanPending->hasPages())
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                            {{ $laporanPending->links() }}
                        </div>
                    @endif
                @endif
            </div>

            {{-- Link kembali ke beranda laporan --}}
            <div class="text-center">
                <a href="{{ route('reports.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 underline">
                    ← Lihat Beranda Laporan
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
