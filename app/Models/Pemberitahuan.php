<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemberitahuan extends Model
{
    use HasFactory;

    protected $table = 'pemberitahuans';
    protected $primaryKey = 'id_pemberitahuan';

    protected $fillable = [
        'judul',
        'pesan',
        'tipe',
        'tanggal_mulai',
        'tanggal_selesai',
    ];
}
