<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiPenjual extends Model
{
    protected $table = 'verifikasi_penjuals';

    protected $primaryKey = 'id_verif_pen';

    protected $fillable = [
        'id_user',
        'nik',
        'foto_ktp',
        'status_verifikasi',
        'nama_toko',
        'alamat_toko',
        'deskripsi_toko',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
