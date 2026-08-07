<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (! Schema::hasColumn('vehicles', 'jenis_kendaraan')) {
                $table->string('jenis_kendaraan')->nullable();
            }
            if (! Schema::hasColumn('vehicles', 'merek')) {
                $table->string('merek')->nullable();
            }
            if (! Schema::hasColumn('vehicles', 'tipe')) {
                $table->string('tipe')->nullable();
            }
            if (! Schema::hasColumn('vehicles', 'plat_nomor')) {
                $table->string('plat_nomor')->nullable();
            }
            if (! Schema::hasColumn('vehicles', 'lokasi_pool')) {
                $table->string('lokasi_pool')->nullable();
            }
            if (! Schema::hasColumn('vehicles', 'supir_utama')) {
                $table->string('supir_utama')->nullable();
            }
            if (! Schema::hasColumn('vehicles', 'odometer_awal')) {
                $table->unsignedBigInteger('odometer_awal')->default(0);
            }
            if (! Schema::hasColumn('vehicles', 'pajak_tahunan')) {
                $table->decimal('pajak_tahunan', 15, 2)->nullable();
            }
            if (! Schema::hasColumn('vehicles', 'pajak_5_tahunan')) {
                $table->decimal('pajak_5_tahunan', 15, 2)->nullable();
            }
            if (! Schema::hasColumn('vehicles', 'jatuh_tempo_kir')) {
                $table->date('jatuh_tempo_kir')->nullable();
            }
            if (! Schema::hasColumn('vehicles', 'status')) {
                $table->string('status')->default('Siap Pakai');
            }
        });
    }

    public function down(): void
    {
        // Sengaja dikosongkan - migration ini hanya menambah kolom yang hilang
    }
};
