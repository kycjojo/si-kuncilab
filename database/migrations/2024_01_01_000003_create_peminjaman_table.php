<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('murid_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->string('tujuan');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_pinjam');
            $table->time('jam_mulai');
            $table->time('jam_selesai_rencana');
            $table->time('jam_selesai_aktual')->nullable();
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
                'berlangsung',
                'checklist_diisi',
                'selesai',
                'terlambat'
            ])->default('menunggu');
            $table->text('catatan_pengawas')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
