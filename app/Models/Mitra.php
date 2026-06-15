<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model: Mitra
 *
 * Merepresentasikan akun pengelola (Mitra Faskes).
 * Bertindak sebagai "user" khusus untuk aktor mitra — terpisah dari tabel users wisatawan.
 */
class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitras';

    protected $fillable = [
        'nama_penanggung_jawab',
        'email',
        'password',
        'no_telp',
        'jenis_mitra',
        'is_verified',
        'is_auto_approved',
        'is_auto_approve_cancelled',
        'is_active',
        'blocking_reason',
        'catatan_admin',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_verified'               => 'boolean',
        'is_auto_approved'          => 'boolean',
        'is_auto_approve_cancelled' => 'boolean',
        'password'                  => 'hashed',
        'last_login_at'             => 'datetime',
    ];

    // =========================================================
    // RELASI ELOQUENT
    // =========================================================

    /**
     * Mitra jenis 'faskes' memiliki satu profil faskes.
     * Relasi: Mitra -> hasOne -> Faskes
     */
    public function faskes()
    {
        return $this->hasOne(Faskes::class, 'mitra_id');
    }

    /**
     * Mitra memiliki banyak log login.
     */
    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    // =========================================================
    // HELPER / SCOPE
    // =========================================================

    /**
     * Scope: filter hanya mitra yang sudah terverifikasi admin.
     * Penggunaan: Mitra::verified()->get()
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope: filter mitra yang belum terverifikasi (tampil di antrean admin).
     * Penggunaan: Mitra::pending()->get()
     */
    public function scopePending($query)
    {
        return $query->where('is_verified', false);
    }
}
