<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('murid_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('peminjaman_id')->nullable()->constrained('peminjaman')->onDelete('set null');
            $table->foreignId('dicatat_oleh')->constrained('users')->onDelete('cascade');
            $table->enum('tipe', ['keterlambatan', 'kerusakan_alat', 'kebersihan', 'lainnya'])->default('lainnya');
            $table->integer('poin')->default(1);
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran');
    }
};
