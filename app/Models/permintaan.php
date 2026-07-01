<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class permintaan extends Model
{
    protected $table = 'permintaans';
    protected $primaryKey = 'id_permintaan';

    protected $fillable = [
        'id_user',
        'id_donasi',
        'jumlah_permintaan',
        'catatan',
        'status',
    ];

    public function donasi()
    {
        return $this->belongsTo(\App\Models\Donasi::class, 'id_donasi', 'id_donasi');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }

    public function rating()
    {
        return $this->hasOne(\App\Models\rating::class, 'id_permintaan', 'id_permintaan');
    }
}
