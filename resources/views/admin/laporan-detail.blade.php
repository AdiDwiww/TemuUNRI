<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Laporan — Tinjauan Admin
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-800 underline">
                ← Kembali ke Panel Admin
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Status Banner --}}
            @if($laporan->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 px-5 py-4 rounded-xl flex items-center space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium">Laporan ini menunggu persetujuan admin. Tinjau deskripsi dan foto sebelum mengambil keputusan.</p>
                </div>
            @elseif($laporan->status === 'proses')
                <div class="bg-blue-50 border border-blue-300 text-blue-800 px-5 py-4 rounded-xl flex items-center space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium">Laporan ini sudah disetujui dan tampil di beranda publik.</p>
                </div>
            @elseif($laporan->status === 'Ditolak')
                <div class="bg-red-50 border border-red-300 text-red-800 px-5 py-4 rounded-xl flex items-center space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium">Laporan ini telah ditolak dan tidak tampil di beranda publik.</p>
                </div>
            @elseif($laporan->status === 'selesai')
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-4 rounded-xl flex items-center space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="text-sm font-medium">Laporan ini sudah selesai dan masuk riwayat laporan selesai.</p>
                </div>
            @endif

            {{-- Detail Laporan --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 lg:p-8 space-y-6">

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-4 gap-4">
                    <div>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $laporan->tipe == 'hilang' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }} uppercase">
                            Barang {{ $laporan->tipe }}
                        </span>
                        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $laporan->judul }}</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Diposting oleh <span class="font-medium text-gray-700">{{ $laporan->user->name ?? 'Anonim' }}</span>
                            ({{ $laporan->user->email ?? '-' }})
                            pada {{ $laporan->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div>
                        @php
                            $statusColor = match($laporan->status) {
                                'pending'  => 'bg-yellow-100 text-yellow-800',
                                'proses'   => 'bg-blue-100 text-blue-800',
                                'selesai'  => 'bg-emerald-100 text-emerald-800',
                                'Ditolak'  => 'bg-red-100 text-red-800',
                                default    => 'bg-gray-100 text-gray-800',
                            };
                            $statusLabel = match($laporan->status) {
                                'pending'  => 'Menunggu',
                                'proses'   => 'Aktif',
                                'selesai'  => 'Selesai',
                                'Ditolak'  => 'Ditolak',
                                default    => $laporan->status,
                            };
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }} uppercase">
                            Status: {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                {{-- Foto --}}
                @if($laporan->foto)
                    <div class="flex justify-center bg-gray-50 p-4 rounded-xl border">
                        <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Barang" class="max-h-80 w-auto rounded-lg shadow-sm object-contain">
                    </div>
                @else
                    <div class="flex justify-center bg-gray-50 p-8 rounded-xl border text-gray-400 text-sm">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Tidak ada foto yang diunggah
                        </div>
                    </div>
                @endif

                {{-- Info Detail --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl text-sm">
                    <div>
                        <p class="text-gray-500 font-medium">Kategori</p>
                        <p class="text-gray-900 font-semibold">{{ $laporan->kategori }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Lokasi Kejadian</p>
                        <p class="text-gray-900 font-semibold">{{ $laporan->lokasi }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Tanggal Kejadian</p>
                        <p class="text-gray-900 font-semibold">{{ $laporan->tanggal_kejadian }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Terakhir Diperbarui</p>
                        <p class="text-gray-900 font-semibold">{{ $laporan->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Deskripsi Lengkap</h3>
                    <div class="bg-gray-50 p-4 rounded-xl text-gray-800 text-sm whitespace-pre-line leading-relaxed border">
                        {{ $laporan->deskripsi }}
                    </div>
                </div>

                {{-- Tombol Tindakan Admin --}}
                <div class="pt-6 border-t">
                    @if($laporan->status === 'pending')
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <form action="{{ route('admin.laporan.setujui', $laporan->id) }}" method="POST"
                                  onsubmit="return confirm('Setujui laporan ini? Laporan akan tampil di beranda publik.')">
                                @csrf
                                <button type="submit"
                                        class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg text-sm font-semibold shadow transition flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Setujui Laporan</span>
                                </button>
                            </form>

                            <form action="{{ route('admin.laporan.tolak', $laporan->id) }}" method="POST"
                                  onsubmit="return confirm('Tolak laporan ini? Laporan tidak akan tampil di beranda publik.')">
                                @csrf
                                <button type="submit"
                                        class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg text-sm font-semibold shadow transition flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Tolak Laporan</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <p class="text-center text-sm text-gray-500 italic">
                            Laporan ini sudah diproses (status: <span class="font-semibold">{{ $laporan->status }}</span>). Tidak ada tindakan lebih lanjut.
                        </p>
                    @endif

                    <div class="text-center mt-4">
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-sm text-indigo-600 hover:text-indigo-800 underline">
                            ← Kembali ke Panel Admin
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
