<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Laporan: ') . $report->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 lg:p-8">
                
                <!-- Menampilkan Error Validasi -->
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

                <form action="{{ route('reports.update', $report->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Judul Laporan</label>
                        <input type="text" name="judul" value="{{ old('judul', $report->judul) }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Tipe Laporan</label>
                            <select name="tipe" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="hilang" {{ old('tipe', $report->tipe) == 'hilang' ? 'selected' : '' }}>Barang Hilang</option>
                                <option value="penemuan" {{ old('tipe', $report->tipe) == 'penemuan' ? 'selected' : '' }}>Penemuan Barang</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Kategori Barang</label>
                            <input type="text" name="kategori" value="{{ old('kategori', $report->kategori) }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Lokasi Kejadian / Penemuan</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $report->lokasi) }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Tanggal Kejadian</label>
                            <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian', $report->tanggal_kejadian) }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Status Laporan</label>
                        <select name="status" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="pending" {{ old('status', $report->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="proses" {{ old('status', $report->status) == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ old('status', $report->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Deskripsi Lengkap & Ciri-ciri</label>
                        <textarea name="deskripsi" rows="4" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('deskripsi', $report->deskripsi) }}</textarea>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Ganti Foto Bukti (Opsional)</label>
                        @if($report->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $report->foto) }}" alt="Foto Lama" class="w-20 h-20 object-cover rounded-md border">
                                <span class="text-xs text-gray-500 block mt-1">Foto saat ini</span>
                            </div>
                        @endif
                        <input type="file" name="foto" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-semibold transition">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-semibold shadow transition">Perbarui Laporan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>