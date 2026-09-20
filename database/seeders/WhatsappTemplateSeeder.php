<?php

namespace Database\Seeders;

use App\Models\WhatsappTemplate;
use Illuminate\Database\Seeder;

class WhatsappTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'code' => 'keluhan_baru',
                'name' => 'Laporan Kerusakan / Keluhan Armada Baru',
                'description' => 'Notifikasi dikirimkan ke Admin & Teknisi saat pengemudi melaporkan kerusakan kendaraan.',
                'content' => "🚨 *LAPORAN KELUHAN ARMADA BARU*\n\n" .
                    "• *Pelapor*: {{nama_driver}}\n" .
                    "• *Kendaraan*: {{plat_nomor}} ({{merk_tipe}})\n" .
                    "• *Tanggal*: {{tanggal}}\n" .
                    "• *Bagian / Kerusakan*: {{deskripsi_kerusakan}}\n\n" .
                    "Mohon tim teknisi segera melakukan inspeksi dan tindak lanjut.",
                'variables' => ['nama_driver', 'plat_nomor', 'merk_tipe', 'tanggal', 'deskripsi_kerusakan'],
                'is_active' => true,
            ],
            [
                'code' => 'keluhan_status',
                'name' => 'Update Status Perbaikan ke Pengemudi',
                'description' => 'Notifikasi ke Driver saat perbaikan kendaraan sedang diproses atau telah selesai.',
                'content' => "Halo {{nama_driver}},\n\n" .
                    "Status laporan perbaikan untuk kendaraan *{{plat_nomor}}* saat ini: *{{status_perbaikan}}* (Progress: {{progress}}%).\n\n" .
                    "• *Catatan Teknisi*: {{catatan_teknisi}}\n" .
                    "• *Waktu*: {{waktu}}\n\n" .
                    "Terima kasih atas kerja samanya.",
                'variables' => ['nama_driver', 'plat_nomor', 'status_perbaikan', 'progress', 'catatan_teknisi', 'waktu'],
                'is_active' => true,
            ],
            [
                'code' => 'servis_reminder',
                'name' => 'Pengingat Servis Berkala Armada',
                'description' => 'Pengingat jadwal servis berkala atau penggantian oli mesin.',
                'content' => "🔧 *PENGINGAT SERVIS BERKALA ARMADA*\n\n" .
                    "Kendaraan *{{plat_nomor}}* ({{merk_tipe}}) telah mencapai jadwal servis berkala / batas odometer *{{odometer}} km*.\n\n" .
                    "• *Jadwal Servis*: {{tanggal_servis}}\n" .
                    "• *Pengemudi*: {{driver}}\n\n" .
                    "Harap segera membawa armada ke bengkel untuk perawatan rutin.",
                'variables' => ['plat_nomor', 'merk_tipe', 'odometer', 'tanggal_servis', 'driver'],
                'is_active' => true,
            ],
            [
                'code' => 'kir_reminder',
                'name' => 'Peringatan Jatuh Tempo Uji KIR & Pajak',
                'description' => 'Peringatan masa berlaku uji KIR atau pajak STNK armada yang akan habis.',
                'content' => "📋 *PERINGATAN DOKUMEN ARMADA*\n\n" .
                    "Kendaraan *{{plat_nomor}}* ({{merk_tipe}}) mendekati batas masa berlaku:\n" .
                    "• *Jenis*: {{jenis_dokumen}}\n" .
                    "• *Jatuh Tempo*: {{jatuh_tempo}}\n\n" .
                    "Mohon segera dilakukan perpanjangan / uji berkala sebelum masa berlaku berakhir.",
                'variables' => ['plat_nomor', 'merk_tipe', 'jenis_dokumen', 'jatuh_tempo'],
                'is_active' => true,
            ],
            [
                'code' => 'checklist_peringatan',
                'name' => 'Peringatan Kendala Checklist Harian',
                'description' => 'Peringatan komponen bermasalah (Not OK) dari hasil pemeriksaan harian.',
                'content' => "⚠️ *PERINGATAN CHECKLIST HARIAN ARMADA*\n\n" .
                    "Hasil pemeriksaan harian untuk kendaraan *{{plat_nomor}}* pada {{tanggal}} oleh *{{nama_pemeriksa}}*:\n" .
                    "• *Komponen Bermasalah*: {{komponen_bermasalah}}\n" .
                    "• *Catatan*: {{catatan}}\n\n" .
                    "Harap teknisi segera memeriksa sebelum armada diberangkatkan.",
                'variables' => ['plat_nomor', 'nama_pemeriksa', 'tanggal', 'komponen_bermasalah', 'catatan'],
                'is_active' => true,
            ],
            [
                'code' => 'approval_biaya',
                'name' => 'Permohonan Persetujuan Biaya Perbaikan',
                'description' => 'Notifikasi ke Pimpinan untuk biaya perbaikan bernilai besar yang memerlukan approval.',
                'content' => "💼 *PERMOHONAN PERSETUJUAN BIAYA PERBAIKAN*\n\n" .
                    "Terdapat pengajuan biaya perbaikan armada yang memerlukan persetujuan Pimpinan:\n" .
                    "• *Kendaraan*: {{plat_nomor}}\n" .
                    "• *Kategori*: {{jenis_pengeluaran}}\n" .
                    "• *Estimasi Biaya*: Rp {{jumlah_biaya}}\n" .
                    "• *Keterangan*: {{keterangan}}\n\n" .
                    "Silakan buka sistem Fleet Maintenance untuk menyetujui atau meninjau pengajuan ini.",
                'variables' => ['plat_nomor', 'jenis_pengeluaran', 'jumlah_biaya', 'keterangan'],
                'is_active' => true,
            ],
            [
                'code' => 'tugas_driver',
                'name' => 'Penugasan Armada & Rute Pengemudi',
                'description' => 'Pemberitahuan penugasan armada atau rute perjalanan ke driver.',
                'content' => "Halo {{nama_driver}},\n\n" .
                    "Anda telah ditugaskan untuk mengemudikan armada berikut:\n" .
                    "• *Kendaraan*: {{plat_nomor}} ({{merk_tipe}})\n" .
                    "• *Tujuan*: {{tujuan}}\n" .
                    "• *Jadwal*: {{jadwal}}\n" .
                    "• *Catatan*: {{catatan}}\n\n" .
                    "Harap lakukan pemeriksaan checklist harian sebelum berangkat. Selamat bertugas!",
                'variables' => ['nama_driver', 'plat_nomor', 'merk_tipe', 'tujuan', 'jadwal', 'catatan'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $data) {
            WhatsappTemplate::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
