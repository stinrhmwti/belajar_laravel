<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kendaraan');      // Mobil Boks, Mobil Pick Up, dll
            $table->string('merek');                // Mitsubishi, Suzuki, dll
            $table->string('tipe');                 // Canter FE 74, Carry Pickup, dll
            $table->string('plat_nomor')->unique(); // B 1234 KTR
            $table->string('lokasi_pool')->nullable();
            $table->string('supir_utama')->nullable();
            $table->unsignedBigInteger('odometer_awal')->default(0);
            $table->decimal('pajak_tahunan', 15, 2)->nullable();
            $table->decimal('pajak_5_tahunan', 15, 2)->nullable();
            $table->date('jatuh_tempo_kir')->nullable();
            $table->string('status')->default('Siap Pakai'); // Siap Pakai, Sedang Diservis, Selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
