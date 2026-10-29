<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Laporan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard pengguna yang sedang login.
     * Hanya menampilkan laporan milik pengguna tersebut.
     */
    public function index()
    {
        $user = auth()->user();

        // Ambil laporan milik pengguna yang sedang login, dengan pagination
        $laporans = Laporan::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Ringkasan jumlah laporan berdasarkan status
        $jumlahPending  = Laporan::where('user_id', $user->id)->where('status', 'pending')->count();
        $jumlahProses   = Laporan::where('user_id', $user->id)->where('status', 'proses')->count();
        $jumlahSelesai  = Laporan::where('user_id', $user->id)->where('status', 'selesai')->count();
        $jumlahDitolak  = Laporan::where('user_id', $user->id)->where('status', 'Ditolak')->count();
        $jumlahTotal    = Laporan::where('user_id', $user->id)->count();

        // Jumlah klaim masuk berstatus pending pada laporan penemuan milik pengguna
        $jumlahKlaimMasukPending = Claim::whereHas('laporan', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('tipe', 'penemuan');
        })->where('status', 'pending')->count();

        return view('dashboard', compact(
            'user',
            'laporans',
            'jumlahPending',
            'jumlahProses',
            'jumlahSelesai',
            'jumlahDitolak',
            'jumlahTotal',
            'jumlahKlaimMasukPending'
        ));
    }
}

