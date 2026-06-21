<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan Splash Screen Wisatawan (Halaman Awal)
     */
    public function wisatawanHome() {
        return view('home');
    }

    /**
     * Menampilkan Halaman Login
     */
    public function login() {
        return view('login');
    }

    public function daftarPilihan() {
        return view('daftar.pilihan');
    }

    public function daftarPariwisata() {
        return view('daftar.form_pariwisata');
    }

    public function daftarFaskes() {
        return view('daftar.form_faskes');
    }

    // Tambahkan di dalam class HomeController

    public function daftarWisatawan() {
        return view('daftar.form_wisatawan');
    }

    public function petaFaskes() {
        // Ambil faskes terverifikasi
        $faskes = \App\Models\Faskes::with(['mitra', 'jadwals'])
            ->withAvg('ulasans', 'rating')
            ->withCount('ulasans')
            ->whereHas('mitra', fn($q) => $q->where('is_verified', true))
            ->get()
            ->map(fn($f) => [
                'id'       => $f->id,
                'name'     => $f->nama_faskes,
                'type'     => $f->jenis_faskes,
                'address'  => $f->alamat,
                'phone'    => $f->no_telp,
                'lat'      => (float) $f->latitude,
                'lng'      => (float) $f->longitude,
                'status'   => $f->status_operasional,
                'jam_buka' => $f->jam_buka ? substr($f->jam_buka, 0, 5) : null,
                'jam_tutup'=> $f->jam_tutup ? substr($f->jam_tutup, 0, 5) : null,
                'is_24_jam'=> (bool) $f->is_24_jam,
                'bpjs'     => (bool) $f->dukungan_bpjs,
                'facilities' => $f->layanan_tersedia ?? [],
                'notes'    => $f->pengumuman,
                'rating_avg' => round($f->ulasans_avg_rating ?? 0, 1),
                'rating_count' => $f->ulasans_count ?? 0,
                'jadwals'  => $f->jadwals->map(fn($j) => [
                    'dokter'       => $j->nama_dokter,
                    'spesialisasi' => $j->spesialisasi,
                    'hari'         => $j->hari,
                    'jam'          => substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5),
                ]),
            ]);

        // Ambil pariwisata yang disetujui (Dari model form publik)
        $pariwisata = \App\Models\PendaftaranPariwisata::disetujui()
            ->get()
            ->map(fn($w) => [
                'id'         => 'p_' . $w->id,
                'name'       => $w->nama_wisata,
                'kategori'   => $w->kategori,
                'deskripsi'  => $w->deskripsi,
                'alamat'     => $w->alamat,
                'lat'        => (float) $w->latitude,
                'lng'        => (float) $w->longitude,
                'tiket'      => $w->harga_tiket ?? 0,
                'pengelola'  => $w->nama_pengelola,
                'telp'       => $w->no_telp,
                'foto'       => $w->foto_path ? asset('storage/' . $w->foto_path) : null,
            ]);

        // Gabungkan pariwisata mitra jika ada modelnya (Sudah dihapus: sekarang hanya pakai PendaftaranPariwisata)
        $daftarFaskes = $faskes;
        $daftarPariwisata = $pariwisata;

        return view('peta', compact('daftarFaskes', 'daftarPariwisata'));
    }

    public function jadwalFaskes($id) {
        $faskes = \App\Models\Faskes::with(['jadwals'])
            ->where('id', $id)
            ->whereHas('mitra', fn($q) => $q->where('is_verified', true))
            ->firstOrFail();
            
        return view('jadwal', compact('faskes'));
    }

    // Dashboard Routes
    public function dashboardWisatawan() {
        return view('dashboard.wisatawan');
    }

    public function dashboardFaskes() {
        return view('dashboard.faskes');
    }

    public function dashboardAdmin() {
        return view('dashboard.admin');
    }

    /**
     * Memproses Laporan Masalah dari Wisatawan/Publik
     */
    public function submitLaporan(Request $request) {
        try {
            $request->validate([
                'subjek' => 'required|string|max:255',
                'deskripsi' => 'required|string|max:200',
                'faskes_id' => 'nullable|exists:faskes,id'
            ]);

            \App\Models\LaporanMasalah::create([
                'user_id' => session('auth_user.id'), // null jika tidak login
                'faskes_id' => $request->faskes_id,
                'subjek' => $request->subjek,
                'deskripsi' => $request->deskripsi,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan Anda telah berhasil dikirim. Tim kami akan segera meninjaunya.'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim laporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mengirim laporan.'
            ], 500);
        }
    }
}
