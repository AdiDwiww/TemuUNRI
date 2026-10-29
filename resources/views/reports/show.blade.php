<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan: ') . $report->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Notifikasi Error (misalnya laporan tidak bisa diedit) --}}
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 lg:p-8 space-y-6">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-4 gap-4">
                    <div>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report->tipe == 'hilang' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }} uppercase">
                            Barang {{ $report->tipe }}
                        </span>
                        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $report->judul }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Diposting oleh <span class="font-medium text-gray-700">{{ $report->user->name ?? 'Anonim' }}</span> pada {{ $report->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                            Status: {{ $report->status }}
                        </span>
                    </div>
                </div>

                @if($report->foto)
                    <div class="flex justify-center bg-gray-50 p-4 rounded-xl border">
                        <img src="{{ asset('storage/' . $report->foto) }}" alt="Foto Barang" class="max-h-80 w-auto rounded-lg shadow-sm object-contain">
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl text-sm">
                    <div>
                        <p class="text-gray-500 font-medium">Kategori</p>
                        <p class="text-gray-900 font-semibold">{{ $report->kategori }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Lokasi Kejadian</p>
                        <p class="text-gray-900 font-semibold">{{ $report->lokasi }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Tanggal Kejadian</p>
                        <p class="text-gray-900 font-semibold">{{ $report->tanggal_kejadian }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Terakhir Diperbarui</p>
                        <p class="text-gray-900 font-semibold">{{ $report->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Deskripsi Lengkap</h3>
                    <div class="bg-gray-50 p-4 rounded-xl text-gray-800 text-sm whitespace-pre-line leading-relaxed border">
                        {{ $report->deskripsi }}
                    </div>
                </div>

                <!-- Titik masuk ke fitur klaim dan chat (Orang 3) -->
                @php
                    $klaimSaya = auth()->check()
                        ? $report->claims()->where('user_id', auth()->id())->first()
                        : null;
                    $bisaKlaim = auth()->check()
                        && $report->user_id !== auth()->id()
                        && $report->tipe === 'penemuan'
                        && $report->status === 'proses'
                        && ! $klaimSaya;
                    $bisaChat = auth()->check() && $report->user_id !== auth()->id();
                @endphp

                <div class="pt-6 border-t flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ route('reports.index') }}" class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-md text-sm font-semibold transition">
                        &larr; Kembali ke Beranda
                    </a>

                    <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                        @if($klaimSaya)
                            <a href="{{ route('claims.show', $klaimSaya) }}" class="w-full sm:w-auto text-center bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-6 py-2.5 rounded-md text-sm font-semibold transition">
                                Lihat Klaim Saya
                            </a>
                        @elseif($bisaKlaim)
                            <a href="{{ route('claims.create', $report) }}" class="w-full sm:w-auto text-center bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-md text-sm font-semibold shadow transition">
                                Ajukan Klaim
                            </a>
                        @endif

                        @if($bisaChat)
                            @if(Route::has('conversations.store'))
                                <form method="POST" action="{{ route('conversations.store', $report) }}">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-md text-sm font-semibold shadow transition">
                                        Chat Pelapor
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled title="Fitur chat dikerjakan di tahap berikutnya" class="w-full sm:w-auto bg-indigo-200 text-indigo-700 px-6 py-2.5 rounded-md text-sm font-semibold cursor-not-allowed">
                                    Chat Pelapor
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>