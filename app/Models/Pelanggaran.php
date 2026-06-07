<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran';

    protected $fillable = ['murid_id', 'peminjaman_id', 'dicatat_oleh', 'tipe', 'poin', 'keterangan'];

    public static function tipeLabel(string $tipe): string
    {
        return match($tipe) {
            'keterlambatan'  => 'Keterlambatan',
            'kerusakan_alat' => 'Kerusakan Alat',
            'kebersihan'     => 'Kebersihan',
            'lainnya'        => 'Lainnya',
            default          => 'Unknown',
        };
    }

    public function murid()
    {
        return $this->belongsTo(User::class, 'murid_id');
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function dicatatOleh()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
