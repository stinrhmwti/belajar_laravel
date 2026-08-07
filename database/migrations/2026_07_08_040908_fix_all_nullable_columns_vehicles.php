<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'pajak_tahunan')) {
                $table->decimal('pajak_tahunan', 15, 2)->nullable()->default(null)->change();
            }
            if (Schema::hasColumn('vehicles', 'pajak_5_tahunan')) {
                $table->decimal('pajak_5_tahunan', 15, 2)->nullable()->default(null)->change();
            }
            if (Schema::hasColumn('vehicles', 'jatuh_tempo_kir')) {
                $table->date('jatuh_tempo_kir')->nullable()->default(null)->change();
            }
            if (Schema::hasColumn('vehicles', 'lokasi_pool')) {
                $table->string('lokasi_pool')->nullable()->default(null)->change();
            }
            if (Schema::hasColumn('vehicles', 'supir_utama')) {
                $table->string('supir_utama')->nullable()->default(null)->change();
            }
            if (Schema::hasColumn('vehicles', 'merk')) {
                $table->string('merk')->nullable()->default(null)->change();
            }
            if (Schema::hasColumn('vehicles', 'status')) {
                $table->string('status')->nullable()->default('Siap Pakai')->change();
            }
        });
    }

    public function down(): void
    {
        //
    }
};
