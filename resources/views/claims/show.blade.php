<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Klaim
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 lg:p-8 space-y-6">
                <div class="flex justify-between items-start gap-4 border-b pb-4">
                    <div>
                        <p class="text-sm text-gray-500">Klaim untuk laporan</p>
                        <h1 class="text-xl font-bold text-gray-900">{{ $claim->laporan->judul }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Pengaju: {{ $claim->user->name }}</p>
                    </div>
                    <div>
                        @if($claim->status === 'pending')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 uppercase">Menunggu</span>
                        @elseif($claim->status === 'accepted')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 uppercase">Diterima</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 uppercase">Ditolak</span>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Keterangan kepemilikan</h3>
                    <div class="bg-gray-50 p-4 rounded-xl text-gray-800 text-sm whitespace-pre-line leading-relaxed border">
                        {{ $claim->description }}
                    </div>
                </div>

                <p class="text-xs text-gray-400">
                    Penerimaan atau penolakan klaim dilakukan oleh admin. Status saat ini: {{ $claim->status }}.
                </p>

                <div class="pt-4 border-t flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('claims.index') }}" class="text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-md text-sm font-semibold transition">
                        Daftar Klaim
                    </a>
                    <a href="{{ route('reports.show', $claim->laporan) }}" class="text-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-md text-sm font-semibold transition">
                        Lihat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
