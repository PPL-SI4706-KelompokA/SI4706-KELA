<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasis'; 
    protected $primaryKey = 'id_donasi'; 

    protected $fillable = [
        'id_user',
        'id_lokasi',
        'nama_makanan',
        'kategori',
        'jumlah',
        'tanggal_kadaluarsa',
        'deskripsi',
        'status_donasi',
        'foto_url',
    ];

    // Relasi ke User (Donatur)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Lokasi
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    // Relasi ke Permintaan
    public function permintaans()
    {
        return $this->hasMany(permintaan::class, 'id_donasi', 'id_donasi');
    }
}