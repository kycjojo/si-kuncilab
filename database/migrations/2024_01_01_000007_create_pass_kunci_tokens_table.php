<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pass_kunci_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('from_murid_id')->constrained('users')->onDelete('cascade');
            $table->string('token', 64)->unique();
            $table->enum('status', ['unused', 'used', 'expired'])->default('unused');
            $table->timestamp('expired_at');
            $table->timestamp('used_at')->nullable();
            $table->foreignId('used_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('to_peminjaman_id')->nullable()->constrained('peminjaman')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pass_kunci_tokens');
    }
};
