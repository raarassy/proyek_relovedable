<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_barang',
        'id_pembeli',
        'id_penjual',
        'tanggal_transaksi',
        'status_transaksi',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function pembeli()
    {
        return $this->belongsTo(User::class, 'id_pembeli', 'id_user');
    }

    public function penjual()
    {
        return $this->belongsTo(User::class, 'id_penjual', 'id_user');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'id_transaksi', 'id_transaksi');
    }
}
