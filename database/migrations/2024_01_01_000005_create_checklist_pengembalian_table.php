<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('murid_id')->constrained('users')->onDelete('cascade');
            $table->boolean('kondisi_listrik')->default(false)->comment('Listrik sudah dimatikan');
            $table->boolean('kondisi_ac')->default(false)->comment('AC sudah dimatikan');
            $table->boolean('kondisi_perangkat')->default(false)->comment('Perangkat dalam kondisi baik');
            $table->boolean('kondisi_kebersihan')->default(false)->comment('Ruangan sudah dibersihkan');
            $table->boolean('kondisi_pintu')->default(false)->comment('Pintu/jendela sudah terkunci');
            $table->text('catatan_tambahan')->nullable();
            $table->string('foto_path')->nullable()->comment('Path foto kondisi ruangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_pengembalian');
    }
};
