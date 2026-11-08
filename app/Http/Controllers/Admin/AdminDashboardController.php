<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin baru (tab: Laporan, Klaim, Pengguna & Log).
     */
    public function index(Request $request)
    {
        // --- Statistik ---
        $jumlahPending  = Laporan::where('status', 'pending')->count();
        $jumlahProses   = Laporan::where('status', 'proses')->count();
        $jumlahSelesai  = Laporan::where('status', 'selesai')->count();
        $jumlahDitolak  = Laporan::where('status', 'Ditolak')->count();
        $jumlahTotal    = Laporan::count();

        // --- TAB 1: Semua Laporan dengan filter & search ---
        $searchLaporan  = $request->input('search');
        $filterStatus   = $request->input('status_filter');

        $queryLaporan = Laporan::with('user')->latest();

        if ($searchLaporan) {
            $queryLaporan->where(function ($q) use ($searchLaporan) {
                $q->where('judul', 'like', "%{$searchLaporan}%")
                  ->orWhere('lokasi', 'like', "%{$searchLaporan}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$searchLaporan}%"));
            });
        }

        if ($filterStatus && $filterStatus !== 'ALL') {
            $statusMap = [
                'MENUNGGU' => 'pending',
                'DIPROSES' => 'proses',
                'SELESAI'  => 'selesai',
                'DITOLAK'  => 'Ditolak',
            ];
            $dbStatus = $statusMap[$filterStatus] ?? null;
            if ($dbStatus) {
                $queryLaporan->where('status', $dbStatus);
            }
        }

        $semuaLaporan = $queryLaporan->paginate(15, ['*'], 'laporan_page')
                                     ->withQueryString();

        // Alias untuk kompatibilitas view / test
        $laporanPending = Laporan::with('user')
            ->where('status', 'pending')
            ->oldest()
            ->paginate(15, ['*'], 'pending_page')
            ->withQueryString();

        // --- TAB 2: Klaim masuk (semua laporan penemuan) ---
        $klaimMasuk = Claim::with(['laporan.user', 'user'])
            ->whereHas('laporan', fn ($q) => $q->where('tipe', 'penemuan'))
            ->where('status', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'klaim_page')
            ->withQueryString();

        $jumlahKlaimPending = Claim::whereHas('laporan', fn ($q) => $q->where('tipe', 'penemuan'))
            ->where('status', 'pending')
            ->count();

        // --- TAB 3: Pengguna & Log aktivitas ---
        $semuaPengguna = User::withCount('laporans')
            ->latest()
            ->paginate(10, ['*'], 'user_page')
            ->withQueryString();

        $aktivitasTerbaru = Laporan::with('user')
            ->latest('updated_at')
            ->take(20)
            ->get();

        return view('admin.dashboard', compact(
            'jumlahPending',
            'jumlahProses',
            'jumlahSelesai',
            'jumlahDitolak',
            'jumlahTotal',
            'semuaLaporan',
            'laporanPending',
            'klaimMasuk',
            'jumlahKlaimPending',
            'semuaPengguna',
            'aktivitasTerbaru',
            'searchLaporan',
            'filterStatus'
        ));
    }

    /**
     * Setujui laporan: ubah status 'pending' menjadi 'proses'.
     */
    public function setujui(Laporan $laporan)
    {
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
     */
    public function tolak(Laporan $laporan)
    {
        if ($laporan->status !== 'pending') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Laporan tidak dapat ditolak karena statusnya bukan "Menunggu".');
        }

        $laporan->update(['status' => 'Ditolak']);

        return redirect()->route('admin.dashboard')
            ->with('success', "Laporan \"{$laporan->judul}\" telah ditolak.");
    }

    /**
     * Tampilkan detail laporan untuk diperiksa admin.
     */
    public function show(Laporan $laporan)
    {
        $laporan->load(['user', 'claims.user']);
        return view('admin.laporan-detail', compact('laporan'));
    }

    /**
     * Admin: Setujui klaim yang masuk (ubah status klaim → accepted, laporan → selesai).
     */
    public function setujuiKlaim(Claim $claim)
    {
        $claim->load('laporan');

        if ($claim->status !== 'pending') {
            return redirect()->route('admin.dashboard', ['tab' => 'klaim'])
                ->with('error', 'Klaim ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($claim) {
            $claim->update(['status' => 'accepted']);
            $claim->laporan->update(['status' => 'selesai']);

            // Tolak semua klaim lain yang masih pending pada laporan yang sama
            Claim::where('laporan_id', $claim->laporan_id)
                ->where('id', '!=', $claim->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        });

        return redirect()->route('admin.dashboard', ['tab' => 'klaim'])
            ->with('success', "Klaim berhasil disetujui. Laporan telah ditandai selesai.");
    }

    /**
     * Admin: Tolak klaim yang masuk.
     */
    public function tolakKlaim(Claim $claim)
    {
        if ($claim->status !== 'pending') {
            return redirect()->route('admin.dashboard', ['tab' => 'klaim'])
                ->with('error', 'Klaim ini sudah diproses sebelumnya.');
        }

        $claim->update(['status' => 'rejected']);

        return redirect()->route('admin.dashboard', ['tab' => 'klaim'])
            ->with('success', "Klaim telah ditolak.");
    }

    /**
     * Export laporan sebagai CSV.
     */
    public function exportCsv()
    {
        $laporans = Laporan::with('user')->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan_temuunri_' . now()->format('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($laporans) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 agar Excel otomatis mendeteksi encoding dengan benar
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['ID', 'Judul', 'Tipe', 'Kategori', 'Lokasi', 'Tanggal Kejadian', 'Status', 'Pelapor', 'Email', 'Dibuat Pada']);
            foreach ($laporans as $l) {
                fputcsv($handle, [
                    $l->id,
                    $l->judul,
                    $l->tipe === 'hilang' ? 'Barang Hilang' : 'Barang Temuan',
                    $l->kategori,
                    $l->lokasi,
                    $l->tanggal_kejadian,
                    match($l->status) {
                        'pending' => 'Menunggu',
                        'proses'  => 'Diproses',
                        'selesai' => 'Selesai',
                        'Ditolak' => 'Ditolak',
                        default   => $l->status,
                    },
                    $l->user->name ?? 'Anonim',
                    $l->user->email ?? '-',
                    $l->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }
}
