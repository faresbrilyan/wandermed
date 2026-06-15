<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatKunjungan;
use App\Models\User;

/**
 * WisatawanController
 *
 * Mengelola semua halaman dan logika untuk aktor Wisatawan.
 * Termasuk: Dashboard, Riwayat Kunjungan, Update Catatan pribadi,
 * dan manajemen profil & data medis.
 */
class WisatawanController extends Controller
{
    // =========================================================
    // DASHBOARD WISATAWAN
    // Mengambil data riwayat kunjungan dari database dan mengirim ke view
    // =========================================================
    public function dashboard()
    {
        $authUser = session('auth_user');
        $user     = User::find($authUser['id']);

        // Ambil semua riwayat kunjungan milik wisatawan ini
        // Eager load data faskes agar tidak N+1 query
        $riwayats = RiwayatKunjungan::with('faskes')
                        ->where('user_id', $authUser['id'])
                        ->orderBy('tanggal_kunjungan', 'desc')
                        ->get();

        // Statistik ringkas untuk stat cards
        $totalKunjungan  = $riwayats->count();
        $kunjunganBulan  = $riwayats->filter(function ($r) {
            return $r->tanggal_kunjungan->month === now()->month &&
                   $r->tanggal_kunjungan->year  === now()->year;
        })->count();
        $rekomendasiCount = $riwayats->where('label_warna', 'green')->count();

        return view('dashboard.wisatawan', compact(
            'user',
            'riwayats',
            'totalKunjungan',
            'kunjunganBulan',
            'rekomendasiCount'
        ));
    }

    // =========================================================
    // UPDATE CATATAN PRIBADI (AJAX – Inline Editing)
    // Endpoint: PUT /wisatawan/catatan/{id}
    // Dipanggil oleh Fetch API di dashboard tanpa reload halaman
    // =========================================================
    public function updateCatatan(Request $request, int $id)
    {
        $request->validate(['catatan_pribadi' => 'nullable|string|max:1000']);

        $riwayat = RiwayatKunjungan::where('id', $id)
                       ->where('user_id', session('auth_user.id'))
                       ->firstOrFail();

        $riwayat->update(['catatan_pribadi' => $request->catatan_pribadi]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil disimpan!',
        ]);
    }

    // =========================================================
    // UPDATE LABEL WARNA (AJAX)
    // Endpoint: PUT /wisatawan/label/{id}
    // =========================================================
    public function updateLabel(Request $request, int $id)
    {
        $request->validate([
            'label_warna' => 'required|in:green,yellow,red',
        ]);

        $riwayat = RiwayatKunjungan::where('id', $id)
                       ->where('user_id', session('auth_user.id'))
                       ->firstOrFail();

        $riwayat->update(['label_warna' => $request->label_warna]);

        return response()->json([
            'success' => true,
            'message' => 'Label berhasil diperbarui!',
        ]);
    }

    // =========================================================
    // UPDATE PROFIL & DATA MEDIS
    // Endpoint: POST /wisatawan/profil
    // =========================================================
    public function updateProfil(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'gol_darah'      => 'nullable|in:A,B,AB,O',
            'kontak_darurat' => 'nullable|string|max:15',
            'riwayat_alergi' => 'nullable|string|max:200',
            'riwayat_penyakit' => 'nullable|string|max:200',
        ]);

        $user = User::find(session('auth_user.id'));
        $user->update($request->only(['name', 'gol_darah', 'kontak_darurat', 'riwayat_alergi', 'riwayat_penyakit']));

        // Perbarui nama di session
        session(['auth_user.name' => $user->name]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // =========================================================
    // SIMPAN RIWAYAT KUNJUNGAN (AJAX)
    // Endpoint: POST /wisatawan/kunjungan
    // =========================================================
    public function recordVisit(Request $request)
    {
        $request->validate([
            'faskes_id' => 'required|exists:faskes,id',
        ]);

        $userId = session('auth_user.id');

        // Check if there is already a visit to this faskes today to avoid duplicates
        $exists = RiwayatKunjungan::where('user_id', $userId)
                                    ->where('faskes_id', $request->faskes_id)
                                    ->whereDate('tanggal_kunjungan', today())
                                    ->exists();

        if (!$exists) {
            RiwayatKunjungan::create([
                'user_id'           => $userId,
                'faskes_id'         => $request->faskes_id,
                'tanggal_kunjungan' => today(),
                'label_warna'       => 'yellow', // default label
                'catatan_pribadi'   => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kunjungan berhasil dicatat!',
        ]);
    }

}
