<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambahkan kolom attempts dan menyesuaikan kolom status pada tabel whatsapp_logs.
     */
    public function up(): void
    {
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            // Ubah tipe kolom status menjadi string agar mendukung status 'pending', 'sent', 'failed', 'success'
            $table->string('status', 20)->default('pending')->change();

            // Tambahkan kolom attempts untuk melacak jumlah percobaan pengiriman oleh Queue Job
            if (!Schema::hasColumn('whatsapp_logs', 'attempts')) {
                $table->unsignedInteger('attempts')->default(0)->after('status');
            }
        });
    }

    /**
     * Kembalikan perubahan migrasi.
     */
    public function down(): void
    {
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            if (Schema::hasColumn('whatsapp_logs', 'attempts')) {
                $table->dropColumn('attempts');
            }
        });
    }
};
