<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Laporan;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    /**
     * Daftar klaim milik pengguna yang sedang login.
     */
    public function index()
    {
        $claims = Claim::with('laporan')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('claims.index', compact('claims'));
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
            ->with('success', 'Klaim berhasil diajukan dan menunggu pemeriksaan admin.');
    }

    /**
     * Detail status klaim.
     */
    public function show(Claim $claim)
    {
        $user = auth()->user();
        $claim->load(['laporan', 'user']);

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
            return 'Klaim hanya dapat diajukan pada laporan yang sudah disetujui admin.';
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
