<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoBarang extends Model
{
    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_barang',
        'path_foto',
    ];

    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}