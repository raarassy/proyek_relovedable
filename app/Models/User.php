<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'nama',
        'email',
        'password',
        'no_hp',
        'foto_profil',
        'role',
        'status_akun',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ===== Helpers role =====
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPenjual(): bool
    {
        return $this->role === 'penjual';
    }

    public function isAktif(): bool
    {
        return $this->status_akun === 'aktif';
    }

    // ===== Relasi =====

    // Toko milik user (hanya untuk penjual)
    public function toko()
    {
        return $this->hasOne(Toko::class, 'id_user', 'id_user');
    }

    // Barang yang dijual user (lewat toko)
    public function barangs()
    {
        return $this->hasManyThrough(
            Barang::class,
            Toko::class,
            'id_user',  // FK di tokos
            'id_toko',  // FK di barangs
            'id_user',  // PK user
            'id_toko'   // PK toko
        );
    }

    // Pengajuan verifikasi penjual (terbaru)
    public function verifikasiPenjual()
    {
        return $this->hasOne(VerifikasiPenjual::class, 'id_user', 'id_user')
            ->latestOfMany('id_verif_pen');
    }

    // Barang favorit
    public function favorits()
    {
        return $this->hasMany(Favorit::class, 'id_user', 'id_user');
    }

    // User yang diikuti (saya sebagai pengikut)
    public function diikuti()
    {
        return $this->belongsToMany(User::class, 'follows', 'id_pengikut', 'id_diikuti')
            ->withPivot('created_at');
    }

    // Pengikut saya
    public function pengikut()
    {
        return $this->belongsToMany(User::class, 'follows', 'id_diikuti', 'id_pengikut')
            ->withPivot('created_at');
    }

    // Chat dikirim / diterima
    public function chatDikirim()
    {
        return $this->hasMany(Chat::class, 'id_pengirim', 'id_user');
    }

    public function chatDiterima()
    {
        return $this->hasMany(Chat::class, 'id_penerima', 'id_user');
    }

    // Transaksi sebagai pembeli / penjual
    public function transaksiPembeli()
    {
        return $this->hasMany(Transaksi::class, 'id_pembeli', 'id_user');
    }

    public function transaksiPenjual()
    {
        return $this->hasMany(Transaksi::class, 'id_penjual', 'id_user');
    }

    // Review yang diterima sebagai penjual
    public function reviewDiterima()
    {
        return $this->hasMany(Review::class, 'id_penjual', 'id_user');
    }
}
