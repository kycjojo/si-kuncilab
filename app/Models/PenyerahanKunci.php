<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyerahanKunci extends Model
{
    use HasFactory;

    protected $table = 'penyerahan_kunci';

    protected $fillable = ['peminjaman_id', 'pengawas_id', 'waktu_serah', 'waktu_terima_kembali'];

    protected $casts = [
        'waktu_serah' => 'datetime',
        'waktu_terima_kembali' => 'datetime',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function pengawas()
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }
}
