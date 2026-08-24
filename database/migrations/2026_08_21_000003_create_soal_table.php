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
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori_ujian')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->string('pilihan_a');
            $table->string('pilihan_b');
            $table->string('pilihan_c');
            $table->string('pilihan_d');
            $table->string('jawaban_benar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};
