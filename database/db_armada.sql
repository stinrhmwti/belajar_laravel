-- Database dump for db_armada
-- Generated on 2026-08-21 06:03:27

SET FOREIGN_KEY_CHECKS=0;

-- Table structure for table `bukus`
DROP TABLE IF EXISTS `bukus`;
CREATE TABLE `bukus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penulis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sampul` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `cache`
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `cache_locks`
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `complaints`
DROP TABLE IF EXISTS `complaints`;
CREATE TABLE `complaints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `keluhan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Baru','Diproses','Selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baru',
  `progress_perbaikan` int NOT NULL DEFAULT '0',
  `diterima_at` timestamp NULL DEFAULT NULL,
  `diperbaiki_at` timestamp NULL DEFAULT NULL,
  `selesai_at` timestamp NULL DEFAULT NULL,
  `foto_kerusakan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_kerusakan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_penyelesaian` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `complaints_vehicle_id_foreign` (`vehicle_id`),
  KEY `complaints_user_id_foreign` (`user_id`),
  CONSTRAINT `complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaints_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `complaints`
INSERT INTO `complaints` (`id`, `vehicle_id`, `user_id`, `tanggal`, `keluhan`, `status`, `progress_perbaikan`, `diterima_at`, `diperbaiki_at`, `selesai_at`, `foto_kerusakan`, `video_kerusakan`, `catatan_penyelesaian`, `created_at`, `updated_at`) VALUES ('1', '2', '11', '2026-08-01', 'Rem kaki terasa keras dan kurang pakem saat muatan penuh.', 'Diproses', '50', '2026-08-01 09:00:00', '2026-08-02 10:30:00', NULL, NULL, NULL, 'Sedang diganti kampas rem depan dan minyak rem dikuras.', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `complaints` (`id`, `vehicle_id`, `user_id`, `tanggal`, `keluhan`, `status`, `progress_perbaikan`, `diterima_at`, `diperbaiki_at`, `selesai_at`, `foto_kerusakan`, `video_kerusakan`, `catatan_penyelesaian`, `created_at`, `updated_at`) VALUES ('2', '1', '11', '2026-08-02', 'AC bagian kabin panas dan terdengar bunyi mendengung saat blower dinyalakan.', 'Selesai', '100', '2026-08-02 08:30:00', '2026-08-02 09:00:00', '2026-08-02 15:00:00', NULL, NULL, 'Freon AC diisi ulang dan dinamo blower dibersihkan.', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `complaints` (`id`, `vehicle_id`, `user_id`, `tanggal`, `keluhan`, `status`, `progress_perbaikan`, `diterima_at`, `diperbaiki_at`, `selesai_at`, `foto_kerusakan`, `video_kerusakan`, `catatan_penyelesaian`, `created_at`, `updated_at`) VALUES ('3', '3', '12', '2026-08-03', 'Lampu sein kanan belakang mati total dan lampu utama sebelah kiri redup.', 'Baru', '0', NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `complaints` (`id`, `vehicle_id`, `user_id`, `tanggal`, `keluhan`, `status`, `progress_perbaikan`, `diterima_at`, `diperbaiki_at`, `selesai_at`, `foto_kerusakan`, `video_kerusakan`, `catatan_penyelesaian`, `created_at`, `updated_at`) VALUES ('4', '4', '13', '2026-08-03', 'Mesin terasa tersendat (brebet) di putaran bawah, terutama saat menanjak.', 'Diproses', '30', '2026-08-03 14:00:00', '2026-08-04 09:00:00', NULL, NULL, NULL, 'Pengecekan busi dan filter bahan bakar.', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `complaints` (`id`, `vehicle_id`, `user_id`, `tanggal`, `keluhan`, `status`, `progress_perbaikan`, `diterima_at`, `diperbaiki_at`, `selesai_at`, `foto_kerusakan`, `video_kerusakan`, `catatan_penyelesaian`, `created_at`, `updated_at`) VALUES ('5', '5', '14', '2026-08-04', 'Ban depan sebelah kiri tipis/gundul, sangat licin saat hujan.', 'Baru', '0', NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');

-- Table structure for table `daily_checklists`
DROP TABLE IF EXISTS `daily_checklists`;
CREATE TABLE `daily_checklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `nama_teknisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `odometer` bigint unsigned DEFAULT NULL,
  `oli_mesin` enum('OK','Not OK') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OK',
  `air_radiator` enum('OK','Not OK') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OK',
  `minyak_rem` enum('OK','Not OK') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OK',
  `ban_rem` enum('OK','Not OK') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OK',
  `lampu_klakson` enum('OK','Not OK') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OK',
  `kebersihan` enum('OK','Not OK') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OK',
  `catatan_tambahan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `daily_checklists_vehicle_id_foreign` (`vehicle_id`),
  CONSTRAINT `daily_checklists_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `daily_checklists`
INSERT INTO `daily_checklists` (`id`, `vehicle_id`, `tanggal`, `nama_teknisi`, `odometer`, `oli_mesin`, `air_radiator`, `minyak_rem`, `ban_rem`, `lampu_klakson`, `kebersihan`, `catatan_tambahan`, `created_at`, `updated_at`) VALUES ('1', '1', '2026-07-06', 'Budi Santoso', '120600', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK', 'Kondisi aman', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `daily_checklists` (`id`, `vehicle_id`, `tanggal`, `nama_teknisi`, `odometer`, `oli_mesin`, `air_radiator`, `minyak_rem`, `ban_rem`, `lampu_klakson`, `kebersihan`, `catatan_tambahan`, `created_at`, `updated_at`) VALUES ('2', '2', '2026-07-06', 'Dedi Kurniawan', '76900', 'Not OK', 'OK', 'OK', 'OK', 'Not OK', 'OK', 'Oli perlu dicek & lampu sein redup', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `daily_checklists` (`id`, `vehicle_id`, `tanggal`, `nama_teknisi`, `odometer`, `oli_mesin`, `air_radiator`, `minyak_rem`, `ban_rem`, `lampu_klakson`, `kebersihan`, `catatan_tambahan`, `created_at`, `updated_at`) VALUES ('3', '1', '2026-07-07', 'Budi Santoso', '120750', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK', NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `daily_checklists` (`id`, `vehicle_id`, `tanggal`, `nama_teknisi`, `odometer`, `oli_mesin`, `air_radiator`, `minyak_rem`, `ban_rem`, `lampu_klakson`, `kebersihan`, `catatan_tambahan`, `created_at`, `updated_at`) VALUES ('4', '2', '2026-07-07', 'Dedi Kurniawan', '77050', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK', NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `daily_checklists` (`id`, `vehicle_id`, `tanggal`, `nama_teknisi`, `odometer`, `oli_mesin`, `air_radiator`, `minyak_rem`, `ban_rem`, `lampu_klakson`, `kebersihan`, `catatan_tambahan`, `created_at`, `updated_at`) VALUES ('5', '1', '2026-08-01', 'Budi Santoso', '121100', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK', 'Pemeriksaan awal bulan', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `daily_checklists` (`id`, `vehicle_id`, `tanggal`, `nama_teknisi`, `odometer`, `oli_mesin`, `air_radiator`, `minyak_rem`, `ban_rem`, `lampu_klakson`, `kebersihan`, `catatan_tambahan`, `created_at`, `updated_at`) VALUES ('6', '2', '2026-08-01', 'Dedi Kurniawan', '77400', 'OK', 'OK', 'Not OK', 'OK', 'OK', 'OK', 'Minyak rem terindikasi kurang', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `daily_checklists` (`id`, `vehicle_id`, `tanggal`, `nama_teknisi`, `odometer`, `oli_mesin`, `air_radiator`, `minyak_rem`, `ban_rem`, `lampu_klakson`, `kebersihan`, `catatan_tambahan`, `created_at`, `updated_at`) VALUES ('7', '1', '2026-08-04', 'Budi Santoso', '121500', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK', 'Kendaraan prima', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `daily_checklists` (`id`, `vehicle_id`, `tanggal`, `nama_teknisi`, `odometer`, `oli_mesin`, `air_radiator`, `minyak_rem`, `ban_rem`, `lampu_klakson`, `kebersihan`, `catatan_tambahan`, `created_at`, `updated_at`) VALUES ('8', '2', '2026-08-04', 'Dedi Kurniawan', '77650', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK', 'Semua indikator normal', '2026-08-21 04:00:32', '2026-08-21 04:00:32');

-- Table structure for table `expenses`
DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` enum('BBM','Tol','Bengkel','Parkir','Pajak','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_biaya` decimal(15,2) NOT NULL,
  `status_approval` enum('Disetujui','Menunggu Persetujuan','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Disetujui',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_vehicle_id_foreign` (`vehicle_id`),
  CONSTRAINT `expenses_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `expenses`
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('1', '1', '2026-04-21', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('2', '2', '2026-06-02', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('3', '3', '2026-08-11', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('4', '4', '2026-04-21', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('5', '5', '2026-06-02', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('6', '6', '2026-08-11', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('7', '7', '2026-04-21', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('8', '8', '2026-06-02', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('9', '9', '2026-08-11', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('10', '10', '2026-04-21', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('11', '11', '2026-06-02', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('12', '12', '2026-08-11', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('13', '13', '2026-04-21', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('14', '14', '2026-06-02', 'Bengkel', '750000.00', 'Disetujui', NULL, 'Servis berkala rutin (ganti oli & filter)', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('15', '1', '2026-07-06', 'BBM', '200000.00', 'Disetujui', NULL, 'Isi bensin full tank', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('16', '2', '2026-07-06', 'Tol', '50000.00', 'Disetujui', NULL, 'Biaya tol dalam kota', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('17', '1', '2026-04-06', 'Bengkel', '350000.00', 'Disetujui', NULL, 'Ganti oli mesin berkala', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('18', '2', '2026-05-15', 'Bengkel', '450000.00', 'Disetujui', NULL, 'Servis rem dan ganti kampas', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('19', '2', '2026-07-06', 'Parkir', '10000.00', 'Disetujui', NULL, 'Biaya parkir bongkar muat', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('20', '1', '2026-07-06', 'BBM', '150000.00', 'Disetujui', NULL, 'Isi bensin tambahan', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('21', '1', '2026-08-01', 'BBM', '250000.00', 'Disetujui', NULL, 'Isi Pertamax Dex awal bulan', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('22', '2', '2026-08-02', 'Bengkel', '650000.00', 'Disetujui', NULL, 'Perbaikan rem dan ganti minyak rem di bengkel resmi', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('23', '1', '2026-08-03', 'Tol', '85000.00', 'Disetujui', NULL, 'Biaya tol Trans Jawa', '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `expenses` (`id`, `vehicle_id`, `tanggal`, `jenis_pengeluaran`, `jumlah_biaya`, `status_approval`, `catatan_admin`, `keterangan`, `created_at`, `updated_at`) VALUES ('24', '2', '2026-08-04', 'BBM', '180000.00', 'Menunggu Persetujuan', NULL, 'Pengisian BBM rutin mingguan', '2026-08-21 04:00:32', '2026-08-21 04:00:32');

-- Table structure for table `failed_jobs`
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `hasil_ujian`
DROP TABLE IF EXISTS `hasil_ujian`;
CREATE TABLE `hasil_ujian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `mapel_id` bigint unsigned NOT NULL,
  `kategori_id` bigint unsigned NOT NULL,
  `jumlah_benar` int NOT NULL,
  `jumlah_salah` int NOT NULL,
  `nilai` decimal(5,2) NOT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hasil_ujian_user_id_foreign` (`user_id`),
  KEY `hasil_ujian_mapel_id_foreign` (`mapel_id`),
  KEY `hasil_ujian_kategori_id_foreign` (`kategori_id`),
  CONSTRAINT `hasil_ujian_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_ujian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_ujian_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_ujian_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `hasil_ujian`
INSERT INTO `hasil_ujian` (`id`, `user_id`, `mapel_id`, `kategori_id`, `jumlah_benar`, `jumlah_salah`, `nilai`, `tanggal`) VALUES ('1', '5', '1', '1', '2', '0', '100.00', '2026-08-16');
INSERT INTO `hasil_ujian` (`id`, `user_id`, `mapel_id`, `kategori_id`, `jumlah_benar`, `jumlah_salah`, `nilai`, `tanggal`) VALUES ('2', '5', '2', '2', '1', '1', '50.00', '2026-08-19');
INSERT INTO `hasil_ujian` (`id`, `user_id`, `mapel_id`, `kategori_id`, `jumlah_benar`, `jumlah_salah`, `nilai`, `tanggal`) VALUES ('3', '6', '3', '3', '2', '0', '100.00', '2026-08-18');

-- Table structure for table `job_batches`
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `jobs`
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `kategori_ujian`
DROP TABLE IF EXISTS `kategori_ujian`;
CREATE TABLE `kategori_ujian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `kategori_ujian`
INSERT INTO `kategori_ujian` (`id`, `nama_kategori`, `kode_kategori`, `deskripsi`) VALUES ('1', 'Ujian Tengah Semester', 'UTS', 'Evaluasi tengah semester ganjil');
INSERT INTO `kategori_ujian` (`id`, `nama_kategori`, `kode_kategori`, `deskripsi`) VALUES ('2', 'Ujian Akhir Semester', 'UAS', 'Evaluasi akhir semester ganjil');
INSERT INTO `kategori_ujian` (`id`, `nama_kategori`, `kode_kategori`, `deskripsi`) VALUES ('3', 'Latihan Harian', 'LATHAR', 'Kuis dan latihan harian mandiri');

-- Table structure for table `mapel`
DROP TABLE IF EXISTS `mapel`;
CREATE TABLE `mapel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `mapel`
INSERT INTO `mapel` (`id`, `nama_mapel`, `kode_mapel`) VALUES ('1', 'Matematika', 'MTK01');
INSERT INTO `mapel` (`id`, `nama_mapel`, `kode_mapel`) VALUES ('2', 'Bahasa Inggris', 'ING01');
INSERT INTO `mapel` (`id`, `nama_mapel`, `kode_mapel`) VALUES ('3', 'Ilmu Pengetahuan Alam', 'IPA01');

-- Table structure for table `migrations`
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_06_30_044741_create_bukus_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_07_06_083302_create_vehicles_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_07_06_083315_create_daily_checklists_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_07_06_083328_create_expenses_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_07_07_041119_create_complaints_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_07_07_041142_add_approval_to_expenses_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_07_08_035817_fix_vehicles_table_columns', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_07_08_040204_fix_merk_column_vehicles', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_07_08_040908_fix_all_nullable_columns_vehicles', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_07_30_050000_add_repair_progress_media_and_gps_columns', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('14', '2026_07_30_074740_add_tahun_to_vehicles_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('15', '2026_07_31_074635_add_foto_to_vehicles_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('16', '2026_08_04_075602_add_tanggal_servis_manual_to_vehicles_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('17', '2026_08_21_000001_create_mapel_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('18', '2026_08_21_000002_create_kategori_ujian_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('19', '2026_08_21_000003_create_soal_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('20', '2026_08_21_000004_create_hasil_ujian_table', '1');

-- Table structure for table `password_reset_tokens`
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `sessions`
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `soal`
DROP TABLE IF EXISTS `soal`;
CREATE TABLE `soal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mapel_id` bigint unsigned NOT NULL,
  `kategori_id` bigint unsigned NOT NULL,
  `pertanyaan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_a` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_b` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_c` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_d` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jawaban_benar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `soal_mapel_id_foreign` (`mapel_id`),
  KEY `soal_kategori_id_foreign` (`kategori_id`),
  CONSTRAINT `soal_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_ujian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `soal_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `soal`
INSERT INTO `soal` (`id`, `mapel_id`, `kategori_id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `jawaban_benar`) VALUES ('1', '1', '1', 'Berapakah hasil dari 5 + 3 * 2?', '16', '11', '13', '10', 'B');
INSERT INTO `soal` (`id`, `mapel_id`, `kategori_id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `jawaban_benar`) VALUES ('2', '1', '1', 'Jika x + 5 = 12, berapakah nilai x?', '5', '6', '7', '8', 'C');
INSERT INTO `soal` (`id`, `mapel_id`, `kategori_id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `jawaban_benar`) VALUES ('3', '2', '2', 'What is the synonym of \"Happy\"?', 'Sad', 'Joyful', 'Angry', 'Tired', 'B');
INSERT INTO `soal` (`id`, `mapel_id`, `kategori_id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `jawaban_benar`) VALUES ('4', '2', '2', 'Translate: \"Saya sedang membaca buku\" into English.', 'I read a book', 'I am reading a book', 'I was reading a book', 'I have read a book', 'B');
INSERT INTO `soal` (`id`, `mapel_id`, `kategori_id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `jawaban_benar`) VALUES ('5', '3', '3', 'Planet apakah yang terdekat dari Matahari?', 'Venus', 'Bumi', 'Merkurius', 'Mars', 'C');
INSERT INTO `soal` (`id`, `mapel_id`, `kategori_id`, `pertanyaan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `jawaban_benar`) VALUES ('6', '3', '3', 'Gas apakah yang kita hirup saat bernapas?', 'Karbondioksida', 'Oksigen', 'Nitrogen', 'Hidrogen', 'B');

-- Table structure for table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'murid',
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Admin Fleet', 'admin_fleet', 'admin@fleet.com', '$2y$12$mVrVVJiTGK8EcpUnAMlYwuBPoEjyUO5hKaqFccnHyOrw4SifkiwWC', 'admin', NULL, NULL, NULL, '2026-08-21 04:00:24', '2026-08-21 04:00:24');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('2', 'Andi Wijaya', 'admin_andi', 'andi.admin@fleet.com', '$2y$12$zrnCozvraEQ4p.9hevhczeSdlB/MuTIKq2W1ytq7GrpbNBaGQUP1K', 'admin', NULL, NULL, NULL, '2026-08-21 04:00:26', '2026-08-21 04:00:26');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('3', 'Budi Utomo, S.Pd.', 'guru_budi', 'guru.budi@school.com', '$2y$12$MClOt2SrXSpmqitl53HpCuodaK6UwAMZfmM.xCkAgNDH.IgfQRNnC', 'guru', NULL, NULL, NULL, '2026-08-21 04:00:26', '2026-08-21 04:00:26');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('4', 'Siti Aminah, M.Pd.', 'guru_siti', 'guru.siti@school.com', '$2y$12$41IK8iiffLGxNO9mtjJCI.Zofdlg6wvo/FoOUdZHK4Ol0OkMiJDO6', 'guru', NULL, NULL, NULL, '2026-08-21 04:00:26', '2026-08-21 04:00:26');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('5', 'Rizky Pratama', 'murid_rizky', 'rizky@school.com', '$2y$12$bMzv8gf0yabL8mUVN6uvm.GkXYLb0L.pJPcdkxm.Vg0pydLCqScQe', 'murid', NULL, NULL, NULL, '2026-08-21 04:00:27', '2026-08-21 04:00:27');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('6', 'Dewi Lestari', 'murid_dewi', 'dewi@school.com', '$2y$12$J4EDeoiZuWx.zZp0/hrYd.HRVX/jMUkVoEKsgZgxDidZV2zmGQ5sa', 'murid', NULL, NULL, NULL, '2026-08-21 04:00:27', '2026-08-21 04:00:27');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('7', 'Faisal Reza', 'murid_faisal', 'faisal@school.com', '$2y$12$t3DdwZLsdKHuLKa9ZZK.DOO/oRn7YvRGe6KXn2iC0DJJcU.LQC1n6', 'murid', NULL, NULL, NULL, '2026-08-21 04:00:27', '2026-08-21 04:00:27');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('8', 'Budi Santoso', 'teknisi_budi', 'budi.teknisi@fleet.com', '$2y$12$pRfZmb2l3gzugSdE0hIBKurIte7wLhBkTxjjKlysWaTk5VUg9ThY6', 'teknisi', NULL, NULL, NULL, '2026-08-21 04:00:28', '2026-08-21 04:00:28');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('9', 'Fajar Nugroho', 'teknisi_fajar', 'fajar.teknisi@fleet.com', '$2y$12$KcTI8Mizy3oyxohJX2XAL.tfPgAJB26CCaMjLuvtsXZ80MJUnc.QG', 'teknisi', NULL, NULL, NULL, '2026-08-21 04:00:29', '2026-08-21 04:00:29');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('10', 'Rian Saputra', 'teknisi_rian', 'rian.teknisi@fleet.com', '$2y$12$A1F0IRToenQmiM4s9kwhgeK7uOm5hrUuhjHH0bXkmCXkAi5MOwL4e', 'teknisi', NULL, NULL, NULL, '2026-08-21 04:00:29', '2026-08-21 04:00:29');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('11', 'Dedi Kurniawan', 'driver_dedi', 'dedi.driver@fleet.com', '$2y$12$V8TQupFS8Y/IGVRMCySS.eObnmjJy1hLukp1XKxV/GbF.uY/VBwrO', 'user', NULL, NULL, NULL, '2026-08-21 04:00:29', '2026-08-21 04:00:29');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('12', 'Agus Setiawan', 'driver_agus', 'agus.driver@fleet.com', '$2y$12$FXFAYlrN4uym7NddFzP5Puokc35/TLk1HbIqlPdIz2vKJd3WfCvO.', 'user', NULL, NULL, NULL, '2026-08-21 04:00:30', '2026-08-21 04:00:30');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('13', 'Rudi Hartono', 'driver_rudi', 'rudi.driver@fleet.com', '$2y$12$iQg6Gj45.n51/.s/T1jXLubGFx7NA2Nuag4Y3hVPgtsYN6zCdOZa.', 'user', NULL, NULL, NULL, '2026-08-21 04:00:30', '2026-08-21 04:00:30');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('14', 'Slamet Riyadi', 'driver_slamet', 'slamet.driver@fleet.com', '$2y$12$HrsyYgugrOv6GdHrDT7.qekJExIcgTCD2UcKFgEEktz8ds2laIaiy', 'user', NULL, NULL, NULL, '2026-08-21 04:00:31', '2026-08-21 04:00:31');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('15', 'Tono Wijaya', 'driver_tono', 'tono.driver@fleet.com', '$2y$12$b1K6lECBz2RA7ppgcTAv.ehwbnb44nJmBP1s38pOI4saaPJeIm3rO', 'user', NULL, NULL, NULL, '2026-08-21 04:00:31', '2026-08-21 04:00:31');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('16', 'Hendra Gunawan', 'driver_hendra', 'hendra.driver@fleet.com', '$2y$12$WHPwx//edLKjvsXzcLxt8O1HbNzyR4/xzvULhO1WwUswhEKzrKNUG', 'user', NULL, NULL, NULL, '2026-08-21 04:00:31', '2026-08-21 04:00:31');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `kelas`, `nis`, `remember_token`, `created_at`, `updated_at`) VALUES ('17', 'Wawan Setiadi', 'driver_wawan', 'wawan.driver@fleet.com', '$2y$12$npZqOZFUrmI2hpfVmNdPYe1NjffXH1leMgUfIfPpvbgRQlbLcFYpi', 'user', NULL, NULL, NULL, '2026-08-21 04:00:31', '2026-08-21 04:00:31');

-- Table structure for table `vehicles`
DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis_kendaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int DEFAULT '2024',
  `plat_nomor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_pool` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supir_utama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `odometer_awal` bigint unsigned NOT NULL DEFAULT '0',
  `pajak_tahunan` decimal(15,2) DEFAULT NULL,
  `pajak_5_tahunan` decimal(15,2) DEFAULT NULL,
  `jatuh_tempo_kir` date DEFAULT NULL,
  `tanggal_servis_manual` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Siap Pakai',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_plat_nomor_unique` (`plat_nomor`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `vehicles`
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('1', 'Mobil Boks', 'Mitsubishi', 'Canter FE 74', '2024', 'B 1234 KTR', 'Jakarta', 'Budi Santoso', '120500', '4500000.00', '20000000.00', '2026-03-15', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:31', '2026-08-21 04:00:31');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('2', 'Mobil Pick Up', 'Suzuki', 'Carry Pickup', '2024', 'B 1112 KTR', 'Bandung', 'Dedi Kurniawan', '76800', '2100000.00', '10000000.00', '2026-02-05', NULL, 'Sedang Diservis', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('3', 'Mobil Boks', 'Isuzu', 'Elf NMR 71', '2024', 'B 2201 KTR', 'Jakarta', 'Agus Setiawan', '95300', '5200000.00', '22000000.00', '2026-09-10', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('4', 'Mobil Pick Up', 'Daihatsu', 'Gran Max Pick Up', '2024', 'B 3305 KTR', 'Bekasi', 'Rudi Hartono', '62100', '1900000.00', '9500000.00', '2026-01-20', NULL, 'Sedang Diservis', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('5', 'Mobil Boks', 'Mitsubishi', 'Colt Diesel FE 71', '2024', 'B 4410 KTR', 'Tangerang', 'Slamet Riyadi', '143200', '4800000.00', '21000000.00', '2026-11-25', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('6', 'Motor Kurir', 'Honda', 'Revo X', '2024', 'B 5521 KTR', 'Jakarta', 'Tono Wijaya', '28900', '350000.00', '1200000.00', '2026-08-14', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('7', 'Mobil Pick Up', 'Suzuki', 'Carry Futura', '2024', 'B 6630 KTR', 'Depok', 'Hendra Gunawan', '87400', '2300000.00', '11000000.00', '2025-12-30', NULL, 'Sedang Diservis', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('8', 'Mobil Boks', 'Hino', 'Dutro 110 SD', '2024', 'B 7741 KTR', 'Bogor', 'Wawan Setiadi', '55600', '6100000.00', '25000000.00', '2026-06-18', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('9', 'Mobil Boks', 'Isuzu', 'Elf NLR 55', '2024', 'B 9214 KTR', 'Tangerang', 'Yudi Pratama', '104200', '5000000.00', '21500000.00', '2026-08-25', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('10', 'Mobil Pick Up', 'Daihatsu', 'Gran Max Pick Up 1.5', '2024', 'B 8130 KTR', 'Bekasi', 'Andi Wijaya', '51200', '1950000.00', '9800000.00', '2026-05-12', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('11', 'Motor Kurir', 'Yamaha', 'Gear 125', '2024', 'B 3089 KTR', 'Jakarta', 'Rian Hidayat', '15300', '320000.00', '1100000.00', '2026-04-10', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('12', 'Mobil Boks', 'Hino', 'Dutro 130 HD', '2024', 'B 9972 KTR', 'Bogor', 'Mulyono', '135800', '6400000.00', '26000000.00', '2026-02-18', NULL, 'Sedang Diservis', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('13', 'Mobil Pick Up', 'Toyota', 'Hilux Single Cabin', '2024', 'B 4118 KTR', 'Depok', 'Eko Prasetyo', '42100', '2800000.00', '13000000.00', '2026-06-05', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');
INSERT INTO `vehicles` (`id`, `jenis_kendaraan`, `merek`, `tipe`, `tahun`, `plat_nomor`, `lokasi_pool`, `supir_utama`, `odometer_awal`, `pajak_tahunan`, `pajak_5_tahunan`, `jatuh_tempo_kir`, `tanggal_servis_manual`, `status`, `foto`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES ('14', 'Motor Kurir', 'Honda', 'Vario 125', '2024', 'B 6245 KTR', 'Jakarta', 'Fajar Ramadhan', '32400', '380000.00', '1300000.00', '2026-07-22', NULL, 'Siap Pakai', NULL, NULL, NULL, '2026-08-21 04:00:32', '2026-08-21 04:00:32');

SET FOREIGN_KEY_CHECKS=1;
