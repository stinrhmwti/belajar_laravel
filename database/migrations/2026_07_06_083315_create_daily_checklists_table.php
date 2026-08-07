<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('nama_teknisi');
            $table->unsignedBigInteger('odometer')->nullable();
            $table->enum('oli_mesin', ['OK', 'Not OK'])->default('OK');
            $table->enum('air_radiator', ['OK', 'Not OK'])->default('OK');
            $table->enum('minyak_rem', ['OK', 'Not OK'])->default('OK');
            $table->enum('ban_rem', ['OK', 'Not OK'])->default('OK');
            $table->enum('lampu_klakson', ['OK', 'Not OK'])->default('OK');
            $table->enum('kebersihan', ['OK', 'Not OK'])->default('OK');
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_checklists');
    }
};
