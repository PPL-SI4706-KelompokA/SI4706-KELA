<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $table = 'pesans';
    protected $primaryKey = 'id_pesan';

    protected $fillable = [
        'id_pengirim',
        'id_penerima',
        'pesan',
        'status_baca',
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'id_pengirim', 'id_user');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_penerima', 'id_user');
    }
}
