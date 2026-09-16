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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'no_telepon')) {
                $table->string('no_telepon', 30)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'nomor_sim')) {
                $table->string('nomor_sim', 50)->nullable()->after('no_telepon');
            }
            if (!Schema::hasColumn('users', 'jenis_sim')) {
                $table->string('jenis_sim', 20)->nullable()->after('nomor_sim');
            }
            if (!Schema::hasColumn('users', 'masa_berlaku_sim')) {
                $table->date('masa_berlaku_sim')->nullable()->after('jenis_sim');
            }
        });

        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'liter_bbm')) {
                $table->decimal('liter_bbm', 8, 2)->nullable()->after('jumlah_biaya');
            }
            if (!Schema::hasColumn('expenses', 'odometer_pengisian')) {
                $table->unsignedInteger('odometer_pengisian')->nullable()->after('liter_bbm');
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'driver_id')) {
                $table->foreignId('driver_id')->nullable()->after('supir_utama')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'driver_id')) {
                $table->dropForeign(['driver_id']);
                $table->dropColumn('driver_id');
            }
        });

        Schema::table('expenses', function (Blueprint $table) {
            $columns = array_filter(['liter_bbm', 'odometer_pengisian'], fn($c) => Schema::hasColumn('expenses', $c));
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columns = array_filter(['no_telepon', 'nomor_sim', 'jenis_sim', 'masa_berlaku_sim'], fn($c) => Schema::hasColumn('users', $c));
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
