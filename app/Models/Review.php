<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $primaryKey = 'id_review';

    protected $fillable = [
        'id_transaksi',
        'id_pembeli',
        'id_penjual',
        'rating',
        'ulasan',
        'tanggal_review',
    ];

    protected $casts = [
        'tanggal_review' => 'datetime',
    ];

    public $timestamps = false;

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function pembeli()
    {
        return $this->belongsTo(User::class, 'id_pembeli', 'id_user');
    }

    public function penjual()
    {
        return $this->belongsTo(User::class, 'id_penjual', 'id_user');
    }
}
