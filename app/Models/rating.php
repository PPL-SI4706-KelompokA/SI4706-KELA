<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rating extends Model
{
    protected $table = 'ratings';
    protected $primaryKey = 'id_rating';

    protected $fillable = [
        'id_user',
        'id_permintaan',
        'nilai_rating',
        'komentar',
    ];

    public function permintaan()
    {
        return $this->belongsTo(permintaan::class, 'id_permintaan', 'id_permintaan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
