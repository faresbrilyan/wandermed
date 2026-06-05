<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    // Menonaktifkan default timestamps Laravel (created_at/updated_at)
    // karena kita menggunakan login_at yang diisi otomatis oleh database
    public $timestamps = false;

    protected $fillable = [
        'email',
        'name',
        'role',
        'type',
        'user_id',
        'mitra_id',
        'login_at',
    ];

    protected $casts = [
        'login_at' => 'datetime',
    ];

    /**
     * Relasi ke model User (Wisatawan).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Mitra.
     */
    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}
