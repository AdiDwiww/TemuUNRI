<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Barang Hilang / Penemuan') }}
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

                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Judul Laporan</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Dompet Hitam Hilang di Dekat Fakultas Teknik" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Tipe Laporan</label>
                            <select name="tipe" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="hilang">Barang Hilang</option>
                                <option value="penemuan">Penemuan Barang</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Kategori Barang</label>
                            <input type="text" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Elektronik, Dompet, Kunci, Dokumen" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Lokasi Kejadian / Penemuan</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Parkiran Gedung Serbaguna" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Tanggal Kejadian</label>
                            <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian') }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Deskripsi Lengkap & Ciri-ciri</label>
                        <textarea name="deskripsi" rows="4" placeholder="Jelaskan ciri-ciri fisik barang, isi di dalam dompet/tas, atau kronologi kejadian secara detail..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Upload Foto Bukti (Opsional)</label>
                        <input type="file" name="foto" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG (Maksimal 2MB).</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-semibold transition">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-semibold shadow transition">Simpan Laporan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>