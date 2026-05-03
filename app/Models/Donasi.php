<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasis'; // Nama tabel di database
    protected $primaryKey = 'id_donasi'; // Primary key sesuai ERD

    protected $fillable = [
        'id_user',
        'id_lokasi',
        'nama_makanan',
        'kategori',
        'jumlah',
        'tanggal_kadaluarsa',
        'deskripsi',
        'status_donasi'
    ];
}