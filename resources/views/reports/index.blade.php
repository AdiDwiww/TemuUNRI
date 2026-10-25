<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Beranda & Daftar Laporan TemuUNRI') }}
            </h2>
            <a href="{{ route('reports.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out">
                + Buat Laporan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Notifikasi Sukses -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- 3 Bagian Utama Sesuai Ketentuan (Barang Hilang, Penemuan, Selesai) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Bagian 1: Laporan Barang Hilang -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-t-4 border-red-500">
                    <h3 class="font-bold text-lg text-red-600 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Barang Hilang Terbaru
                    </h3>
                    <ul class="space-y-3">
                        @forelse($barangHilang as $item)
                            <li class="border-b pb-2 last:border-none">
                                <a href="{{ route('reports.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm line-clamp-1">{{ $item->judul }}</a>
                                <div class="flex justify-between items-center text-xs text-gray-500 mt-1">
                                    <span>{{ $item->lokasi }}</span>
                                    <span>{{ $item->tanggal_kejadian }}</span>
                                </div>
                            </li>
                        @empty
                            <p class="text-sm text-gray-400 italic">Belum ada laporan barang hilang.</p>
                        @endforelse
                    </ul>
                </div>

                <!-- Bagian 2: Laporan Penemuan Barang -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-t-4 border-emerald-500">
                    <h3 class="font-bold text-lg text-emerald-600 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Barang Penemuan Terbaru
                    </h3>
                    <ul class="space-y-3">
                        @forelse($barangPenemuan as $item)
                            <li class="border-b pb-2 last:border-none">
                                <a href="{{ route('reports.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm line-clamp-1">{{ $item->judul }}</a>
                                <div class="flex justify-between items-center text-xs text-gray-500 mt-1">
                                    <span>{{ $item->lokasi }}</span>
                                    <span>{{ $item->tanggal_kejadian }}</span>
                                </div>
                            </li>
                        @empty
                            <p class="text-sm text-gray-400 italic">Belum ada laporan penemuan.</p>
                        @endforelse
                    </ul>
                </div>

                <!-- Bagian 3: Riwayat Laporan Selesai -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-t-4 border-gray-500">
                    <h3 class="font-bold text-lg text-gray-700 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Riwayat Laporan Selesai
                    </h3>
                    <ul class="space-y-3">
                        @forelse($laporanSelesai as $item)
                            <li class="border-b pb-2 last:border-none">
                                <a href="{{ route('reports.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm line-clamp-1">{{ $item->judul }}</a>
                                <div class="flex justify-between items-center text-xs text-gray-500 mt-1">
                                    <span class="uppercase font-semibold text-emerald-600">Selesai</span>
                                    <span>{{ $item->tanggal_kejadian }}</span>
                                </div>
                            </li>
                        @empty
                            <p class="text-sm text-gray-400 italic">Belum ada riwayat selesai.</p>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Fitur Pencarian dan Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">
                <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pencarian Kata Kunci</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau lokasi..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Laporan</label>
                        <select name="tipe" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">Semua Tipe</option>
                            <option value="hilang" {{ request('tipe') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            <option value="penemuan" {{ request('tipe') == 'penemuan' ? 'selected' : '' }}>Penemuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status Laporan</label>
                        <select name="status" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-700 text-white text-sm font-semibold py-2 px-4 rounded-md shadow transition">Filter</button>
                        <a href="{{ route('reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold py-2 px-3 rounded-md transition text-center flex items-center justify-center">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Laporan Lengkap dengan Pagination -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="font-bold text-lg">Semua Daftar Laporan</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul / Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi & Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($laporans as $laporan)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            @if($laporan->foto)
                                                <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto" class="w-10 h-10 object-cover rounded-md border">
                                            @else
                                                <div class="w-10 h-10 bg-gray-100 rounded-md flex items-center justify-center text-xs text-gray-400 border">No Pic</div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $laporan->judul }}</div>
                                                <div class="text-xs text-gray-500">Oleh: {{ $laporan->user->name ?? 'Anonim' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $laporan->tipe == 'hilang' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ ucfirst($laporan->tipe) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $laporan->kategori }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $laporan->lokasi }}</div>
                                        <div class="text-xs text-gray-400">{{ $laporan->tanggal_kejadian }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                                            {{ $laporan->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                        <a href="{{ route('reports.show', $laporan->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md text-xs">Detail</a>
                                        
                                        @if($laporan->user_id == auth()->id())
                                            <a href="{{ route('reports.edit', $laporan->id) }}" class="text-amber-600 hover:text-amber-900 bg-amber-50 px-3 py-1 rounded-md text-xs">Edit</a>
                                            <form action="{{ route('reports.destroy', $laporan->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md text-xs" onclick="return confirm('Yakin ingin menghapus laporan ini?')">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Tidak ada data laporan ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="p-4 bg-gray-50 border-t border-gray-200">
                    {{ $laporans->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>