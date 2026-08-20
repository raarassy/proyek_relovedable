<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $primaryKey = 'id_follow';

    public $timestamps = false;

    protected $fillable = [
        'id_pengikut',
        'id_diikuti',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function pengikut()
    {
        return $this->belongsTo(User::class, 'id_pengikut', 'id_user');
    }

    public function diikuti()
    {
        return $this->belongsTo(User::class, 'id_diikuti', 'id_user');
    }
}
