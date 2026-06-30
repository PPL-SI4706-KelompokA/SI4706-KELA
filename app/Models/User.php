<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable([
    'nama', 
    'email', 
    'password', 
    'role', 
    'no_telp', 
    'alamat', 
    'status_verifikasi',
    'id_lokasi',
    'foto_url',
    'banned_status',
    'banned_reason',
    'banned_until'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Beritahu Laravel bahwa Primary Key kamu bukan 'id'
    protected $primaryKey = 'id_user';

    // Relasi ke Lokasi
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}