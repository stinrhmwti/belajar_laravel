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
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'lokasi_asal')) {
                $table->string('lokasi_asal')->nullable()->after('lokasi_pool');
            }
            if (!Schema::hasColumn('vehicles', 'lokasi_tujuan')) {
                $table->string('lokasi_tujuan')->nullable()->after('lokasi_asal');
            }
            if (!Schema::hasColumn('vehicles', 'tujuan_latitude')) {
                $table->decimal('tujuan_latitude', 10, 7)->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('vehicles', 'tujuan_longitude')) {
                $table->decimal('tujuan_longitude', 10, 7)->nullable()->after('tujuan_latitude');
            }
            if (!Schema::hasColumn('vehicles', 'status_perjalanan')) {
                $table->string('status_perjalanan')->default('Standby di Pool')->after('lokasi_tujuan');
            }
            if (!Schema::hasColumn('vehicles', 'kecepatan_kmh')) {
                $table->integer('kecepatan_kmh')->default(0)->after('status_perjalanan');
            }
            if (!Schema::hasColumn('vehicles', 'estimasi_tiba')) {
                $table->dateTime('estimasi_tiba')->nullable()->after('kecepatan_kmh');
            }
            if (!Schema::hasColumn('vehicles', 'jarak_sisa_km')) {
                $table->decimal('jarak_sisa_km', 8, 2)->nullable()->after('estimasi_tiba');
            }
            if (!Schema::hasColumn('vehicles', 'catatan_perjalanan')) {
                $table->text('catatan_perjalanan')->nullable()->after('jarak_sisa_km');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $columns = [
                'lokasi_asal',
                'lokasi_tujuan',
                'tujuan_latitude',
                'tujuan_longitude',
                'status_perjalanan',
                'kecepatan_kmh',
                'estimasi_tiba',
                'jarak_sisa_km',
                'catatan_perjalanan',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('vehicles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
