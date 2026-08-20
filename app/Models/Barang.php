<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $primaryKey = 'id_barang';

    // Daftar kategori baku — satu sumber untuk form upload, filter katalog, & pills home
    public const KATEGORI = [
        'Fashion',
        'Tas',
        'Sepatu',
        'Aksesoris',
        'Elektronik',
        'Buku',
        'Lainnya',
    ];

    protected $fillable = [
        'id_toko',
        'nama_barang',
        'kategori',
        'harga',
        'deskripsi',
        'kondisi',
        'bisa_cod',
        'bisa_ekspedisi',
        'status_barang',
    ];

    protected $casts = [
        'bisa_cod' => 'boolean',
        'bisa_ekspedisi' => 'boolean',
    ];

    // Relasi ke Toko
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    // Penjual (user pemilik toko) — aksesor: $barang->penjual
    public function getPenjualAttribute()
    {
        return $this->toko?->user;
    }

    // Relasi ke FotoBarang
    public function fotoBarangs()
    {
        return $this->hasMany(FotoBarang::class, 'id_barang', 'id_barang');
    }

    // Foto utama
    public function fotoUtama()
    {
        return $this->hasOne(FotoBarang::class, 'id_barang', 'id_barang');
    }

    // Relasi ke Chat
    public function chats()
    {
        return $this->hasMany(Chat::class, 'id_barang', 'id_barang');
    }

    // Relasi ke Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_barang', 'id_barang');
    }

    // Relasi ke Favorit
    public function favorits()
    {
        return $this->hasMany(Favorit::class, 'id_barang', 'id_barang');
    }

    // Apakah difavoritkan oleh user tertentu
    public function difavoritkanOleh($userId): bool
    {
        if (! $userId) {
            return false;
        }

        return $this->favorits()->where('id_user', $userId)->exists();
    }
}
