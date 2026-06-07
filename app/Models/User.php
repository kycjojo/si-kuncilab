<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nis', 'kelas',
        'poin_pelanggaran', 'is_blocked',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_blocked' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPengawas(): bool
    {
        return $this->role === 'pengawas';
    }

    public function isMurid(): bool
    {
        return $this->role === 'murid';
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'murid_id');
    }

    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'murid_id');
    }

    public function penyerahanKunci()
    {
        return $this->hasMany(PenyerahanKunci::class, 'pengawas_id');
    }
}
