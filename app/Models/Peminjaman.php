<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'murid_id', 'lab_id', 'tujuan', 'keterangan',
        'tanggal_pinjam', 'jam_mulai', 'jam_selesai_rencana',
        'jam_selesai_aktual', 'status', 'catatan_pengawas', 'disetujui_oleh',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
    ];

    // Status constants
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_BERLANGSUNG = 'berlangsung';
    const STATUS_CHECKLIST_DIISI = 'checklist_diisi';
    const STATUS_SELESAI = 'selesai';
    const STATUS_TERLAMBAT = 'terlambat';

    public static function statusLabel(string $status): string
    {
        return match($status) {
            'menunggu'        => 'Menunggu',
            'disetujui'       => 'Disetujui',
            'ditolak'         => 'Ditolak',
            'berlangsung'     => 'Berlangsung',
            'checklist_diisi' => 'Checklist Diisi',
            'selesai'         => 'Selesai',
            'terlambat'       => 'Terlambat',
            default           => 'Unknown',
        };
    }

    public static function statusBadge(string $status): string
    {
        return match($status) {
            'menunggu'        => 'warning',
            'disetujui'       => 'info',
            'ditolak'         => 'danger',
            'berlangsung'     => 'primary',
            'checklist_diisi' => 'secondary',
            'selesai'         => 'success',
            'terlambat'       => 'danger',
            default           => 'secondary',
        };
    }

    public function murid()
    {
        return $this->belongsTo(User::class, 'murid_id');
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function penyerahanKunci()
    {
        return $this->hasOne(PenyerahanKunci::class);
    }

    public function checklistPengembalian()
    {
        return $this->hasOne(ChecklistPengembalian::class);
    }

    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class);
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function isTerlambat(): bool
    {
        if (!$this->jam_selesai_aktual) return false;
        return $this->jam_selesai_aktual > $this->jam_selesai_rencana;
    }
}
