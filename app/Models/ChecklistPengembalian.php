<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistPengembalian extends Model
{
    use HasFactory;

    protected $table = 'checklist_pengembalian';

    protected $fillable = [
        'peminjaman_id', 'murid_id',
        'kondisi_listrik', 'kondisi_ac', 'kondisi_perangkat',
        'kondisi_kebersihan', 'kondisi_pintu',
        'catatan_tambahan', 'foto_path',
    ];

    protected $casts = [
        'kondisi_listrik'    => 'boolean',
        'kondisi_ac'         => 'boolean',
        'kondisi_perangkat'  => 'boolean',
        'kondisi_kebersihan' => 'boolean',
        'kondisi_pintu'      => 'boolean',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function murid()
    {
        return $this->belongsTo(User::class, 'murid_id');
    }
}
