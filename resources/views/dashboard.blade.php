<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Saya
            </h2>
            <a href="{{ route('reports.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out">
                + Buat Laporan Baru
            </a>
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

            {{-- Sapaan dan Info Akun --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xl uppercase select-none">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Halo, {{ $user->name }}!</h1>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Bergabung sejak {{ $user->created_at->format('d M Y') }}
                        </p>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('profile.edit') }}"
                           class="text-sm text-indigo-600 hover:text-indigo-800 underline">
                            Edit Profil
                        </a>
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

                {{-- Menunggu --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-t-4 border-yellow-400">
                    <p class="text-xs font-medium text-yellow-700 uppercase tracking-wide">Menunggu</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $jumlahPending }}</p>
                    <p class="text-xs text-gray-400 mt-1">pending</p>
                </div>

                {{-- Diproses --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-t-4 border-blue-400">
                    <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Diproses</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $jumlahProses }}</p>
                    <p class="text-xs text-gray-400 mt-1">proses</p>
                </div>

                {{-- Selesai --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-t-4 border-emerald-400">
                    <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Selesai</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $jumlahSelesai }}</p>
                    <p class="text-xs text-gray-400 mt-1">selesai</p>
                </div>

                {{-- Ditolak --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-t-4 border-red-400">
                    <p class="text-xs font-medium text-red-700 uppercase tracking-wide">Ditolak</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $jumlahDitolak }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ditolak</p>
                </div>
            </div>

            {{-- Daftar Laporan Saya --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="font-bold text-lg text-gray-800">Laporan Saya</h2>
                    <a href="{{ route('reports.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 underline">
                        Lihat Semua Laporan
                    </a>
                </div>

                @if($laporans->isEmpty())
                    {{-- Empty State --}}
                    <div class="p-12 flex flex-col items-center justify-center text-center">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-gray-500 font-medium text-lg">Belum ada laporan</p>
                        <p class="text-gray-400 text-sm mt-1">
                            Anda belum pernah membuat laporan barang hilang atau penemuan.
                        </p>
                        <a href="{{ route('reports.create') }}"
                           class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-5 rounded-lg shadow transition">
                            Buat Laporan Pertama
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laporan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe & Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kejadian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($laporans as $laporan)
                                    <tr class="hover:bg-gray-50 transition">
                                        {{-- Judul & Foto --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                @if($laporan->foto)
                                                    <img src="{{ asset('storage/' . $laporan->foto) }}"
                                                         alt="Foto"
                                                         class="w-10 h-10 object-cover rounded-md border shrink-0">
                                                @else
                                                    <div class="w-10 h-10 bg-gray-100 rounded-md flex items-center justify-center text-xs text-gray-400 border shrink-0">
                                                        No Pic
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('reports.show', $laporan->id) }}"
                                                       class="text-sm font-semibold text-indigo-700 hover:text-indigo-900 hover:underline line-clamp-1">
                                                        {{ $laporan->judul }}
                                                    </a>
                                                    <div class="text-xs text-gray-400">{{ $laporan->lokasi }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Tipe & Kategori --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $laporan->tipe == 'hilang' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                {{ ucfirst($laporan->tipe) }}
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">{{ $laporan->kategori }}</div>
                                        </td>

                                        {{-- Tanggal Kejadian --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d M Y') }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusLabel = match($laporan->status) {
                                                    'pending'  => 'Menunggu',
                                                    'proses'   => 'Diproses',
                                                    'selesai'  => 'Selesai',
                                                    'Ditolak'  => 'Ditolak',
                                                    default    => ucfirst($laporan->status),
                                                };
                                                $statusClass = match($laporan->status) {
                                                    'pending'  => 'bg-yellow-100 text-yellow-800',
                                                    'proses'   => 'bg-blue-100 text-blue-800',
                                                    'selesai'  => 'bg-emerald-100 text-emerald-800',
                                                    'Ditolak'  => 'bg-red-100 text-red-800',
                                                    default    => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-1">
                                            <a href="{{ route('reports.show', $laporan->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md text-xs">
                                                Detail
                                            </a>

                                            {{-- Tombol Edit & Hapus hanya untuk laporan berstatus 'pending' --}}
                                            @if($laporan->status === 'pending')
                                                <a href="{{ route('reports.edit', $laporan->id) }}"
                                                   class="text-amber-600 hover:text-amber-900 bg-amber-50 px-3 py-1 rounded-md text-xs">
                                                    Edit
                                                </a>
                                                <form action="{{ route('reports.destroy', $laporan->id) }}"
                                                      method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md text-xs"
                                                            onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($laporans->hasPages())
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                            {{ $laporans->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
