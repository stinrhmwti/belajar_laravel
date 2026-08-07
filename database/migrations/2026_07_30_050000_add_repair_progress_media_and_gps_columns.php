<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->integer('progress_perbaikan')->default(0)->after('status');
            $table->timestamp('diterima_at')->nullable()->after('progress_perbaikan');
            $table->timestamp('diperbaiki_at')->nullable()->after('diterima_at');
            $table->timestamp('selesai_at')->nullable()->after('diperbaiki_at');
            $table->string('foto_kerusakan')->nullable()->after('selesai_at');
            $table->string('video_kerusakan')->nullable()->after('foto_kerusakan');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('status');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });

        // Set default coordinates for existing vehicles in Jakarta area
        $coords = [
            [-6.208763, 106.845599], // Pool Jakarta Pusat
            [-6.175392, 106.827153], // Pool Monas
            [-6.244431, 106.800635], // Pool Blok M
            [-6.126588, 106.905663], // Pool Tj Priok
            [-6.300641, 106.814095], // Pool Ragunan
            [-6.201720, 106.782155], // Pool Palmerah
            [-6.258882, 106.852443], // Pool Cawang
            [-6.195301, 106.822301], // Pool Sudirman
        ];

        try {
            $vehicles = DB::table('vehicles')->get();
            foreach ($vehicles as $index => $vehicle) {
                $coord = $coords[$index % count($coords)];
                DB::table('vehicles')->where('id', $vehicle->id)->update([
                    'latitude' => $coord[0],
                    'longitude' => $coord[1],
                ]);
            }
        } catch (Exception $e) {
            // Silently skip if query fails during fresh install
        }
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn([
                'progress_perbaikan',
                'diterima_at',
                'diperbaiki_at',
                'selesai_at',
                'foto_kerusakan',
                'video_kerusakan',
            ]);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
