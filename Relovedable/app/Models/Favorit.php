<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    protected $primaryKey = 'id_favorit';

    public $timestamps = false;

    protected $fillable = [
        'id_barang',
        'id_user',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
