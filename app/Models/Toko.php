<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Review;

class Toko extends Model
{
    protected $primaryKey = 'id_toko';

    protected $fillable = [
        'id_user',
        'nama_toko',
        'alamat_toko',
        'deskripsi_toko',
    ];

    // Pemilik toko
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Barang di toko
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_toko', 'id_toko');
    }

    public function ratingRata(): ?float
    {
        return Review::where('id_penjual', $this->id_user)->avg('rating');
    }

    public function jumlahReview(): int
    {
        return Review::where('id_penjual', $this->id_user)->count();
    }
}
