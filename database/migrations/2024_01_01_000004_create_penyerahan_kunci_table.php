<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyerahan_kunci', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('pengawas_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('waktu_serah')->nullable()->comment('Waktu kunci diserahkan ke murid');
            $table->timestamp('waktu_terima_kembali')->nullable()->comment('Waktu kunci diterima kembali dari murid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyerahan_kunci');
    }
};
