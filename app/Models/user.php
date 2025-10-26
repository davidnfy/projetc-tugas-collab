<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * Kolom yang boleh diisi (mass assignable)
     */
    protected $fillable = [
        'nama',        // nama pengguna (bukan 'name')
        'email',
        'password',
        'google_id',
        'avatar',
    ];

    /**
     * Kolom yang disembunyikan saat dikonversi ke JSON/array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast tipe data otomatis
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * (Opsional) Relasi-relasi nanti bisa ditaruh di sini
     * Misal: todos, categories, dsb
     */
}
