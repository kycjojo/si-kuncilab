<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kode', 'kapasitas', 'deskripsi', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Cek apakah lab sedang dipakai pada waktu tertentu
     */
    public function isBusy(string $tanggal, string $jamMulai, string $jamSelesai, ?int $exceptId = null): bool
    {
        $query = $this->peminjaman()
            ->where('tanggal_pinjam', $tanggal)
            ->whereIn('status', ['disetujui', 'berlangsung'])
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                  ->orWhereBetween('jam_selesai_rencana', [$jamMulai, $jamSelesai])
                  ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                      $q2->where('jam_mulai', '<=', $jamMulai)
                         ->where('jam_selesai_rencana', '>=', $jamSelesai);
                  });
            });

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }
}
