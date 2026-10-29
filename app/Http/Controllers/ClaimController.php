<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    /**
     * Daftar klaim milik pengguna yang sedang login (Klaim Saya).
     */
    public function index()
    {
        $userId = auth()->id();

        $claims = Claim::with('laporan')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        $jumlahKlaimMasukPending = Claim::whereHas('laporan', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('tipe', 'penemuan');
        })->where('status', 'pending')->count();

        return view('claims.index', compact('claims', 'jumlahKlaimMasukPending'));
    }

    /**
     * Daftar klaim masuk dari pengguna lain pada laporan penemuan milik pengguna yang sedang login.
     */
    public function incoming()
    {
        $userId = auth()->id();

        $claims = Claim::with(['laporan', 'user'])
            ->whereHas('laporan', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('tipe', 'penemuan');
            })
            ->latest()
            ->paginate(10);

        $jumlahKlaimMasukPending = Claim::whereHas('laporan', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('tipe', 'penemuan');
        })->where('status', 'pending')->count();

        return view('claims.incoming', compact('claims', 'jumlahKlaimMasukPending'));
    }

    /**
     * Form pengajuan klaim pada laporan penemuan.
     */
    public function create(Laporan $report)
    {
        if ($pesan = $this->alasanTidakBisaKlaim($report)) {
            return redirect()
                ->route('reports.show', $report)
                ->with('error', $pesan);
        }

        return view('claims.create', ['report' => $report]);
    }

    /**
     * Simpan pengajuan klaim.
     */
    public function store(Request $request, Laporan $report)
    {
        if ($pesan = $this->alasanTidakBisaKlaim($report)) {
            return redirect()
                ->route('reports.show', $report)
                ->with('error', $pesan);
        }

        $validated = $request->validate([
            'description' => ['required', 'string', 'min:10'],
        ]);

        $claim = Claim::create([
            'laporan_id' => $report->id,
            'user_id' => auth()->id(),
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('claims.show', $claim)
            ->with('success', 'Klaim berhasil diajukan dan menunggu keputusan pelapor.');
    }

    /**
     * Proses keputusan penerimaan atau penolakan klaim oleh pelapor penemuan.
     */
    public function decide(Request $request, Claim $claim)
    {
        $user = auth()->user();
        $claim->load('laporan');

        // Keamanan: Hanya pelapor penemuan barang yang boleh memutuskan klaim
        if ($claim->laporan->user_id !== $user->id) {
            abort(403, 'Hanya pelapor penemuan barang yang berhak memutuskan klaim ini.');
        }

        // Pengaju klaim tidak boleh memutuskan klaimnya sendiri
        if ($claim->user_id === $user->id) {
            abort(403, 'Anda tidak dapat memutuskan klaim milik Anda sendiri.');
        }

        $validated = $request->validate([
            'decision' => ['required', 'in:accepted,rejected'],
        ]);

        return DB::transaction(function () use ($claim, $validated) {
            // Kunci record klaim dan laporan untuk mencegah race condition
            $lockedClaim = Claim::where('id', $claim->id)->lockForUpdate()->firstOrFail();
            $lockedLaporan = Laporan::where('id', $claim->laporan_id)->lockForUpdate()->firstOrFail();

            // Klaim yang sudah diproses tidak boleh diproses kembali
            if ($lockedClaim->status !== 'pending') {
                return back()->with('error', 'Klaim ini sudah diproses sebelumnya.');
            }

            // Laporan harus bertipe penemuan dan berstatus proses
            if ($lockedLaporan->tipe !== 'penemuan' || $lockedLaporan->status !== 'proses') {
                return back()->with('error', 'Laporan tidak dalam status yang dapat menerima keputusan klaim.');
            }

            if ($validated['decision'] === 'accepted') {
                // 1. Status klaim yang dipilih berubah menjadi accepted
                $lockedClaim->update(['status' => 'accepted']);

                // 2. Status laporan penemuan berubah menjadi selesai
                $lockedLaporan->update(['status' => 'selesai']);

                // 3. Klaim lain yang masih pending pada laporan yang sama otomatis menjadi rejected
                Claim::where('laporan_id', $lockedLaporan->id)
                    ->where('id', '!=', $lockedClaim->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'rejected']);

                return redirect()
                    ->route('claims.incoming')
                    ->with('success', 'Klaim kepemilikan berhasil diterima. Laporan telah ditandai selesai.');
            }

            // Keputusan ditolak: status klaim menjadi rejected, status laporan tetap proses
            $lockedClaim->update(['status' => 'rejected']);

            return redirect()
                ->route('claims.incoming')
                ->with('success', 'Klaim kepemilikan telah ditolak. Laporan tetap aktif untuk klaim lainnya.');
        });
    }

    /**
     * Detail status klaim.
     */
    public function show(Claim $claim)
    {
        $user = auth()->user();
        $claim->load(['laporan.user', 'user']);

        $bolehLihat = $claim->user_id === $user->id
            || $claim->laporan->user_id === $user->id
            || $user->isAdmin();

        if (! $bolehLihat) {
            abort(403, 'Anda tidak memiliki akses ke klaim ini.');
        }

        return view('claims.show', compact('claim'));
    }

    /**
     * Aturan klaim: hanya laporan penemuan milik orang lain yang berstatus proses,
     * dan pengguna belum pernah mengajukan klaim untuk laporan itu.
     */
    private function alasanTidakBisaKlaim(Laporan $report): ?string
    {
        if ($report->user_id === auth()->id()) {
            return 'Anda tidak dapat mengklaim laporan milik sendiri.';
        }

        if ($report->tipe !== 'penemuan') {
            return 'Klaim hanya dapat diajukan pada laporan penemuan barang.';
        }

        if ($report->status !== 'proses') {
            return 'Klaim hanya dapat diajukan pada laporan yang sudah disetujui admin dan sedang diproses.';
        }

        $sudahKlaim = Claim::where('laporan_id', $report->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($sudahKlaim) {
            return 'Anda sudah mengajukan klaim untuk laporan ini.';
        }

        return null;
    }
}

