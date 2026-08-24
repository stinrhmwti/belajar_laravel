<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hasil_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori_ujian')->cascadeOnDelete();
            $table->integer('jumlah_benar');
            $table->integer('jumlah_salah');
            $table->decimal('nilai', 5, 2);
            $table->date('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_ujian');
    }
};
