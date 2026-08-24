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
        Schema::create('vehicle_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->date('tanggal');
            $table->foreignId('teknisi_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('jenis_pekerjaan');
            $table->string('sparepart_digunakan')->nullable();
            $table->decimal('biaya', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_histories');
    }
};
