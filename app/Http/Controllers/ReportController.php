<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    // Halaman Beranda (Menampilkan 3 bagian: Barang Hilang, Penemuan, Selesai)[cite: 4]
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $tipe = $request->input('tipe');
        $status = $request->input('status');

        // Daftar Laporan Aktif di Beranda: Hanya menampilkan laporan berstatus 'proses' (disetujui admin)
        $query = Laporan::with('user')
            ->where('status', 'proses')
            ->latest();

        // Fitur Pencarian & Filter (hanya berlaku pada laporan aktif 'proses')
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }
        if ($kategori) {
            $query->where('kategori', 'like', "%{$kategori}%");
        }
        if ($tipe) {
            $query->where('tipe', $tipe);
        }
        if ($status) {
            if ($status !== 'proses') {
                $query->whereRaw('0 = 1');
            }
        }

        $laporans = $query->paginate(10)->withQueryString(); // Pagination daftar laporan

        // Respons AJAX Asynchronous untuk Live Search & Filter Dinamis
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('reports.partials.report-list', compact('laporans'))->render();

            return response()->json([
                'status'  => 'success',
                'success' => true,
                'message' => 'Daftar laporan berhasil dimuat.',
                'total'   => $laporans->total(),
                'html'    => $html,
                'data'    => [
                    'total'          => $laporans->total(),
                    'current_page'   => $laporans->currentPage(),
                    'last_page'      => $laporans->lastPage(),
                    'per_page'       => $laporans->perPage(),
                    'from'           => $laporans->firstItem(),
                    'to'             => $laporans->lastItem(),
                    'has_more_pages' => $laporans->hasMorePages(),
                ],
            ]);
        }

        // Pengelompokan untuk Beranda:
        // Laporan aktif hanya yang berstatus 'proses'
        $barangHilang = Laporan::where('tipe', 'hilang')->where('status', 'proses')->latest()->take(5)->get();
        $barangPenemuan = Laporan::where('tipe', 'penemuan')->where('status', 'proses')->latest()->take(5)->get();
        // Bagian riwayat terpisah untuk laporan yang sudah selesai
        $laporanSelesai = Laporan::where('status', 'selesai')->latest()->take(5)->get();

        return view('reports.index', compact('laporans', 'barangHilang', 'barangPenemuan', 'laporanSelesai'));
    }

    // Form Tambah Laporan
    public function create()
    {
        return view('reports.create');
    }

    // Simpan Laporan Baru (Disertai Upload Foto & Validasi)[cite: 4]
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:hilang,penemuan',
            'kategori' => 'required|string',
            'deskripsi' => 'required',
            'lokasi' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', $this->validateFotoRatio()],
        ], [
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'foto.max'   => 'Ukuran foto maksimal 2 MB.',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('laporan_foto', 'public');
        }

        Laporan::create([
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'foto' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat.');
    }

    // Detail Laporan (Titik masuk ke chat dan klaim)[cite: 4]
    public function show(Laporan $report)
    {
        return view('reports.show', compact('report'));
    }

    /**
     * Tandai laporan kehilangan sebagai selesai karena barang sudah ditemukan oleh pemilik.
     */
    public function markAsFound(Laporan $report)
    {
        // 1. Keamanan: Hanya pemilik laporan yang boleh menandai barang ditemukan
        if ($report->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menyelesaikan laporan ini.');
        }

        // 2. Validasi tipe: Hanya berlaku untuk laporan dengan tipe = 'hilang'
        if ($report->tipe !== 'hilang') {
            return redirect()->route('reports.show', $report)
                ->with('error', 'Hanya laporan kehilangan yang dapat ditandai sebagai ditemukan.');
        }

        // 3. Validasi status: Hanya dapat digunakan ketika status laporan adalah 'proses'
        if ($report->status !== 'proses') {
            return redirect()->route('reports.show', $report)
                ->with('error', 'Hanya laporan berstatus proses yang dapat ditandai sebagai ditemukan.');
        }

        return DB::transaction(function () use ($report) {
            // Kunci record laporan untuk mencegah race condition / perubahan status bersamaan
            $lockedReport = Laporan::where('id', $report->id)->lockForUpdate()->firstOrFail();

            // Validasi ulang di dalam transaksi
            if ($lockedReport->user_id !== auth()->id()) {
                abort(403, 'Anda tidak memiliki akses untuk menyelesaikan laporan ini.');
            }

            if ($lockedReport->tipe !== 'hilang') {
                return redirect()->route('reports.show', $lockedReport)
                    ->with('error', 'Hanya laporan kehilangan yang dapat ditandai sebagai ditemukan.');
            }

            if ($lockedReport->status !== 'proses') {
                return redirect()->route('reports.show', $lockedReport)
                    ->with('error', 'Hanya laporan berstatus proses yang dapat ditandai sebagai ditemukan.');
            }

            // Ubah status laporan menjadi selesai
            $lockedReport->update(['status' => 'selesai']);

            // Jika terdapat klaim berstatus pending pada laporan tersebut,
            // otomatis ubah menjadi rejected agar tidak ada klaim yang menggantung.
            // Klaim yang sudah accepted atau rejected tidak diubah.
            Claim::where('laporan_id', $lockedReport->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            return redirect()->route('reports.show', $lockedReport)
                ->with('success', 'Laporan berhasil ditandai sebagai selesai. Barang telah ditemukan!');
        });
    }

    // Form Edit Laporan (Milik Sendiri, hanya jika status 'pending')
    public function edit(Laporan $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        if ($report->status !== 'pending') {
            return redirect()->route('reports.show', $report->id)
                ->with('error', 'Laporan tidak dapat diedit karena statusnya sudah bukan "Menunggu".');
        }

        return view('reports.edit', compact('report'));
    }

    // Update Laporan (hanya jika status 'pending')
    public function update(Request $request, Laporan $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui laporan ini.');
        }

        if ($report->status !== 'pending') {
            return redirect()->route('reports.show', $report->id)
                ->with('error', 'Laporan tidak dapat diperbarui karena statusnya sudah bukan "Menunggu".');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:hilang,penemuan',
            'kategori' => 'required|string',
            'deskripsi' => 'required',
            'lokasi' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', $this->validateFotoRatio()],
        ], [
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'foto.max'   => 'Ukuran foto maksimal 2 MB.',
        ]);

        $path = $report->foto;
        if ($request->hasFile('foto')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('foto')->store('laporan_foto', 'public');
        }

        $report->update([
            'judul' => $request->judul,
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'foto' => $path,
        ]);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    // Hapus Laporan (Pemilik jika pending/proses di beranda, atau Admin)
    public function destroy(Laporan $report)
    {
        $user = auth()->user();
        $isOwner = $user && $report->user_id === $user->id;
        $isAdmin = $user && $user->isAdmin();

        if (!$isOwner && !$isAdmin) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus laporan ini.');
        }

        // Pengguna biasa hanya boleh menghapus laporan berstatus 'pending' atau 'proses' (sudah di beranda)
        if (!$isAdmin && !in_array($report->status, ['pending', 'proses'])) {
            return redirect()->route('reports.show', $report->id)
                ->with('error', 'Laporan tidak dapat dihapus karena statusnya sudah bukan "Menunggu" atau "Diproses".');
        }

        if ($report->foto) {
            Storage::disk('public')->delete($report->foto);
        }

        $report->delete();

        if ($isAdmin && request()->routeIs('admin.*')) {
            return redirect()->route('admin.dashboard')->with('success', 'Laporan berhasil dihapus.');
        }

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Aturan validasi rasio aspek 4:5 untuk foto barang.
     */
    private function validateFotoRatio(): \Closure
    {
        return function ($attribute, $value, $fail) {
            if (!$value || !$value->isValid()) {
                return;
            }
            $dimensions = @getimagesize($value->getRealPath());
            if (!$dimensions) {
                $fail('File yang diunggah bukan gambar yang valid.');
                return;
            }
            [$width, $height] = $dimensions;
            if ($height <= 0) {
                $fail('Dimensi gambar tidak valid.');
                return;
            }
            $ratio = $width / $height;
            $targetRatio = 4 / 5; // 0.8
            // Toleransi 0.03 untuk mengantisipasi pembulatan piksel
            if (abs($ratio - $targetRatio) > 0.03) {
                $fail('Foto barang harus memiliki rasio aspek 4:5 (portrait). Silakan gunakan fitur crop yang tersedia.');
            }
        };
    }
}