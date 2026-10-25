<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin dengan ringkasan dan daftar laporan pending.
     */
    public function index()
    {
        // Ringkasan statistik semua laporan
        $jumlahPending  = Laporan::where('status', 'pending')->count();
        $jumlahProses   = Laporan::where('status', 'proses')->count();
        $jumlahSelesai  = Laporan::where('status', 'selesai')->count();
        $jumlahDitolak  = Laporan::where('status', 'Ditolak')->count();
        $jumlahTotal    = Laporan::count();

        // Daftar laporan menunggu persetujuan (pending), diurut dari terlama agar tidak terbengkalai
        $laporanPending = Laporan::with('user')
            ->where('status', 'pending')
            ->oldest()
            ->paginate(15);

        return view('admin.dashboard', compact(
            'jumlahPending',
            'jumlahProses',
            'jumlahSelesai',
            'jumlahDitolak',
            'jumlahTotal',
            'laporanPending'
        ));
    }

    /**
     * Setujui laporan: ubah status 'pending' menjadi 'proses'.
     * Hanya bisa dilakukan pada laporan berstatus 'pending'.
     */
    public function setujui(Laporan $laporan)
    {
        // Validasi server: hanya laporan pending yang bisa disetujui
        if ($laporan->status !== 'pending') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Laporan tidak dapat disetujui karena statusnya bukan "Menunggu".');
        }

        $laporan->update(['status' => 'proses']);

        return redirect()->route('admin.dashboard')
            ->with('success', "Laporan \"{$laporan->judul}\" berhasil disetujui dan sekarang tampil di beranda publik.");
    }

    /**
     * Tolak laporan: ubah status 'pending' menjadi 'Ditolak'.
     * Hanya bisa dilakukan pada laporan berstatus 'pending'.
     */
    public function tolak(Laporan $laporan)
    {
        // Validasi server: hanya laporan pending yang bisa ditolak
        if ($laporan->status !== 'pending') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Laporan tidak dapat ditolak karena statusnya bukan "Menunggu".');
        }

        $laporan->update(['status' => 'Ditolak']);

        return redirect()->route('admin.dashboard')
            ->with('success', "Laporan \"{$laporan->judul}\" telah ditolak.");
    }

    /**
     * Tampilkan detail laporan untuk diperiksa admin sebelum mengambil keputusan.
     */
    public function show(Laporan $laporan)
    {
        return view('admin.laporan-detail', compact('laporan'));
    }
}
