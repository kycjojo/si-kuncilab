<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassKunciToken extends Model
{
    use HasFactory;

    protected $table = 'pass_kunci_tokens';

    protected $fillable = [
        'from_peminjaman_id',
        'from_murid_id',
        'token',
        'status',
        'expired_at',
        'used_at',
        'used_by',
        'to_peminjaman_id',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function fromPeminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'from_peminjaman_id');
    }

    public function fromMurid()
    {
        return $this->belongsTo(User::class, 'from_murid_id');
    }

    public function usedByUser()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function toPeminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'to_peminjaman_id');
    }
}
