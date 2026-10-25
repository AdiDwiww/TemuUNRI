<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
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

        $query = Laporan::with('user')->latest();

        // Fitur Pencarian & Filter[cite: 4]
        if ($search) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
        }
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        if ($tipe) {
            $query->where('tipe', $tipe);
        }
        if ($status) {
            $query->where('status', $status);
        }

        $laporans = $query->paginate(10); // Pagination daftar laporan[cite: 4]

        // Pengelompokan untuk Beranda[cite: 4]
        $barangHilang = Laporan::where('tipe', 'hilang')->latest()->take(5)->get();
        $barangPenemuan = Laporan::where('tipe', 'penemuan')->latest()->take(5)->get();
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

    // Form Edit Laporan (Milik Sendiri)[cite: 4]
    public function edit(Laporan $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('reports.edit', compact('report'));
    }

    // Update Laporan
    public function update(Request $request, Laporan $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:hilang,penemuan',
            'kategori' => 'required|string',
            'deskripsi' => 'required',
            'lokasi' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

    // Hapus Laporan (Milik Sendiri)[cite: 4]
    public function destroy(Laporan $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($report->foto) {
            Storage::disk('public')->delete($report->foto);
        }

        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus.');
    }
}