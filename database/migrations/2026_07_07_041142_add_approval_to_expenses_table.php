<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->enum('status_approval', ['Disetujui', 'Menunggu Persetujuan', 'Ditolak'])
                ->default('Disetujui')->after('jumlah_biaya');
            $table->text('catatan_admin')->nullable()->after('status_approval');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['status_approval', 'catatan_admin']);
        });
    }
};
