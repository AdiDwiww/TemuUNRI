<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajukan Klaim: {{ $report->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 lg:p-8">
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 text-sm rounded">
                        <p class="font-bold">Terjadi kesalahan input:</p>
                        <ul class="list-disc ml-5 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6 bg-gray-50 border rounded-xl p-4 text-sm">
                    <p class="text-gray-500">Laporan penemuan</p>
                    <p class="font-semibold text-gray-900">{{ $report->judul }}</p>
                    <p class="text-gray-600 mt-1">Lokasi: {{ $report->lokasi }} · Pelapor: {{ $report->user->name ?? 'Anonim' }}</p>
                </div>

                <form action="{{ route('claims.store', $report) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Keterangan / ciri kepemilikan</label>
                        <textarea name="description" rows="5" required minlength="10"
                            placeholder="Jelaskan ciri khusus barang yang hanya diketahui pemilik, misalnya isi dalam tas, goresan, atau kelengkapan dokumen."
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Minimal 10 karakter. Admin akan memakai keterangan ini untuk memeriksa klaim.</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('reports.show', $report) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-semibold transition">Batal</a>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-md text-sm font-semibold shadow transition">
                            Kirim Klaim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
