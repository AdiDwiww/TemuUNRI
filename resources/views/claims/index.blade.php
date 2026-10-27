<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Klaim Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                @if($claims->isEmpty())
                    <div class="p-8 text-center text-gray-500 text-sm">
                        Anda belum mengajukan klaim.
                        <a href="{{ route('reports.index') }}" class="text-indigo-600 hover:underline">Cari laporan penemuan</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wide">Laporan</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wide">Status</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wide">Diajukan</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($claims as $claim)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-gray-900">{{ $claim->laporan->judul ?? 'Laporan dihapus' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($claim->description, 80) }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($claim->status === 'pending')
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu</span>
                                            @elseif($claim->status === 'accepted')
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Diterima</span>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">{{ $claim->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('claims.show', $claim) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Lihat</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4">
                        {{ $claims->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
