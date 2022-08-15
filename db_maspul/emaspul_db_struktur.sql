# ************************************************************
# Sequel Ace SQL dump
# Version 20033
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 8.0.29)
# Database: emonev
# Generation Time: 2022-08-15 07:08:03 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table akses
# ------------------------------------------------------------

CREATE TABLE `akses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_akses` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `akses_nama_akses_unique` (`nama_akses`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table akses_role
# ------------------------------------------------------------

CREATE TABLE `akses_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_role` bigint unsigned NOT NULL,
  `id_akses` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `akses_role_id_role_foreign` (`id_role`),
  KEY `akses_role_id_akses_foreign` (`id_akses`),
  CONSTRAINT `akses_role_id_akses_foreign` FOREIGN KEY (`id_akses`) REFERENCES `akses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `akses_role_id_role_foreign` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table backup_report
# ------------------------------------------------------------

CREATE TABLE `backup_report` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `triwulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table backup_report_detail_realisasi
# ------------------------------------------------------------

CREATE TABLE `backup_report_detail_realisasi` (
  `key` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint NOT NULL,
  `triwulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_realisasi` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `id_sumber_dana_dpa` bigint unsigned NOT NULL,
  `realisasi_keuangan` double NOT NULL DEFAULT '0',
  `realisasi_fisik` double NOT NULL DEFAULT '0',
  `periode` int NOT NULL COMMENT 'dalam triwulan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_dpa_backup` bigint unsigned DEFAULT NULL,
  `id_sumber_dana_dpa_backup` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table backup_report_dpa
# ------------------------------------------------------------

CREATE TABLE `backup_report_dpa` (
  `key` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint NOT NULL,
  `triwulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_non_urusan` tinyint(1) NOT NULL,
  `id_program` bigint unsigned NOT NULL,
  `id_kegiatan` bigint unsigned NOT NULL,
  `id_sub_kegiatan` bigint unsigned NOT NULL,
  `id_pegawai_penanggung_jawab` bigint unsigned NOT NULL,
  `nilai_pagu_dpa` double NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_unit_kerja` bigint unsigned NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table backup_report_paket_dak
# ------------------------------------------------------------

CREATE TABLE `backup_report_paket_dak` (
  `key` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint NOT NULL,
  `triwulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` double NOT NULL,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `penerima_manfaat` double NOT NULL,
  `anggaran_dak` double NOT NULL,
  `pendampingan` double NOT NULL,
  `total_biaya` double NOT NULL DEFAULT '0',
  `swakelola` double NOT NULL,
  `kontrak` double NOT NULL,
  `tahun` int NOT NULL,
  `id_sumber_dana_dpa` bigint unsigned NOT NULL,
  `kesesuaian_rkpd` enum('Y','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kesesuaian_dpa_skpd` enum('Y','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_dpa` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_dpa_backup` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table backup_report_realisasi
# ------------------------------------------------------------

CREATE TABLE `backup_report_realisasi` (
  `key` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint NOT NULL,
  `triwulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` int NOT NULL COMMENT 'dalam triwulan',
  `realisasi_keuangan` double NOT NULL DEFAULT '0',
  `realisasi_fisik` double NOT NULL DEFAULT '0',
  `tahun` int NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `permasalahan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_dpa_backup` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table backup_report_realisasi_dak
# ------------------------------------------------------------

CREATE TABLE `backup_report_realisasi_dak` (
  `key` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint NOT NULL,
  `triwulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_paket_dak` int NOT NULL,
  `periode` int NOT NULL COMMENT 'dalam triwulan',
  `realisasi_keuangan` double NOT NULL DEFAULT '0',
  `realisasi_fisik` double NOT NULL DEFAULT '0',
  `total_keuangan` double NOT NULL DEFAULT '0',
  `total_fisik` double NOT NULL DEFAULT '0',
  `tahun` int NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `permasalahan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_dpa_backup` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table backup_report_sumber_dana_dpa
# ------------------------------------------------------------

CREATE TABLE `backup_report_sumber_dana_dpa` (
  `key` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint NOT NULL,
  `triwulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_belanja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sumber_dana` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metode_pelaksanaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_pagu` double NOT NULL,
  `tahun` int NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_dpa_backup` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table backup_report_target
# ------------------------------------------------------------

CREATE TABLE `backup_report_target` (
  `key` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint NOT NULL,
  `triwulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` int NOT NULL COMMENT 'dalam triwulan',
  `target_keuangan` double DEFAULT NULL,
  `target_fisik` double DEFAULT NULL,
  `persentase` double DEFAULT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_dpa_backup` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table backup_report_tolak_ukur
# ------------------------------------------------------------

CREATE TABLE `backup_report_tolak_ukur` (
  `key` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint NOT NULL,
  `triwulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `tolak_ukur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` double NOT NULL,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_dpa_backup` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table bidang_urusan
# ------------------------------------------------------------

CREATE TABLE `bidang_urusan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_bidang_urusan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_bidang_urusan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_urusan` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bidang_urusan_id_urusan_foreign` (`id_urusan`),
  CONSTRAINT `bidang_urusan_id_urusan_foreign` FOREIGN KEY (`id_urusan`) REFERENCES `urusan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table bidang_verifikasi
# ------------------------------------------------------------

CREATE TABLE `bidang_verifikasi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_bidang` varchar(255) DEFAULT NULL,
  `status` enum('0','1') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` int DEFAULT NULL,
  `user_update` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table desa
# ------------------------------------------------------------

CREATE TABLE `desa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kecamatan` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table detail_realisasi
# ------------------------------------------------------------

CREATE TABLE `detail_realisasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_realisasi` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `id_sumber_dana_dpa` bigint unsigned NOT NULL,
  `realisasi_keuangan` double NOT NULL DEFAULT '0',
  `realisasi_fisik` double NOT NULL DEFAULT '0',
  `periode` int NOT NULL COMMENT 'dalam triwulan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_realisasi_dpa_foreign` (`id_dpa`),
  CONSTRAINT `detail_realisasi_dpa_foreign` FOREIGN KEY (`id_dpa`) REFERENCES `dpa` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table documents
# ------------------------------------------------------------

CREATE TABLE `documents` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_documents` varchar(100) DEFAULT NULL,
  `periode_awal` year DEFAULT NULL,
  `periode_akhir` year DEFAULT NULL,
  `file_document` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status_document` enum('1','2','3','4') DEFAULT NULL,
  `jenis_document` enum('1','2','3','4','5','6','7','8','9','10') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nomor_perbub` varchar(50) DEFAULT NULL,
  `tanggal_perbub` date DEFAULT NULL,
  `tahun` year DEFAULT NULL,
  `id_perangkat` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` int DEFAULT NULL,
  `user_update` int DEFAULT NULL,
  `id_verifikator` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table documents_history
# ------------------------------------------------------------

CREATE TABLE `documents_history` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(100) DEFAULT NULL,
  `id_documents` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` int DEFAULT NULL,
  `user_update` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table dpa
# ------------------------------------------------------------

CREATE TABLE `dpa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_non_urusan` tinyint(1) NOT NULL,
  `id_program` bigint unsigned NOT NULL,
  `id_kegiatan` bigint unsigned NOT NULL,
  `id_sub_kegiatan` bigint unsigned NOT NULL,
  `id_pegawai_penanggung_jawab` bigint unsigned NOT NULL,
  `nilai_pagu_dpa` double NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_unit_kerja` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dpa_sub_kegiatan_foreign` (`id_sub_kegiatan`),
  KEY `dpa_pj_foreign` (`id_pegawai_penanggung_jawab`),
  CONSTRAINT `dpa_pj_foreign` FOREIGN KEY (`id_pegawai_penanggung_jawab`) REFERENCES `pegawai_penanggung_jawab` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `dpa_sub_kegiatan_foreign` FOREIGN KEY (`id_sub_kegiatan`) REFERENCES `sub_kegiatan` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table error
# ------------------------------------------------------------

CREATE TABLE `error` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table failed_jobs
# ------------------------------------------------------------

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jabatans
# ------------------------------------------------------------

CREATE TABLE `jabatans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jadwal
# ------------------------------------------------------------

CREATE TABLE `jadwal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahapan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_tahapan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jadwal_mulai` date NOT NULL,
  `jadwal_selesai` date NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jenis_belanja
# ------------------------------------------------------------

CREATE TABLE `jenis_belanja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jenis_belanja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table kecamatan
# ------------------------------------------------------------

CREATE TABLE `kecamatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table kegiatan
# ------------------------------------------------------------

CREATE TABLE `kegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kegiatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_program` bigint unsigned NOT NULL,
  `hasil_kegiatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kegiatan_id_program_foreign` (`id_program`),
  CONSTRAINT `kegiatan_id_program_foreign` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table master_verifikasi
# ------------------------------------------------------------

CREATE TABLE `master_verifikasi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `indikator` varchar(250) DEFAULT NULL,
  `jenis_documents` enum('1','2','3','4') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` int DEFAULT NULL,
  `user_update` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table metode_pelaksanaan
# ------------------------------------------------------------

CREATE TABLE `metode_pelaksanaan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_metode_pelaksanaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table migrations
# ------------------------------------------------------------

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table oauth_access_tokens
# ------------------------------------------------------------

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table oauth_auth_codes
# ------------------------------------------------------------

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table oauth_clients
# ------------------------------------------------------------

CREATE TABLE `oauth_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table oauth_personal_access_clients
# ------------------------------------------------------------

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table oauth_refresh_tokens
# ------------------------------------------------------------

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table pagu_desa
# ------------------------------------------------------------

CREATE TABLE `pagu_desa` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_perangkat_desa` int DEFAULT NULL,
  `tahun` varchar(10) DEFAULT NULL,
  `pagu_desa` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` int DEFAULT NULL,
  `user_update` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table paket_dak
# ------------------------------------------------------------

CREATE TABLE `paket_dak` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` double NOT NULL,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `penerima_manfaat` double NOT NULL,
  `anggaran_dak` double NOT NULL,
  `pendampingan` double NOT NULL,
  `total_biaya` double NOT NULL DEFAULT '0',
  `swakelola` double NOT NULL,
  `kontrak` double NOT NULL,
  `tahun` int NOT NULL,
  `id_sumber_dana_dpa` bigint unsigned NOT NULL,
  `kesesuaian_rkpd` enum('Y','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kesesuaian_dpa_skpd` enum('Y','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_dpa` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `kecamatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paket_dak_foreign` (`id_sumber_dana_dpa`),
  CONSTRAINT `paket_dak_foreign` FOREIGN KEY (`id_sumber_dana_dpa`) REFERENCES `sumber_dana_dpa` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table paket_dak_lokasi
# ------------------------------------------------------------

CREATE TABLE `paket_dak_lokasi` (
  `id` bigint unsigned NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_paket_dak` int NOT NULL,
  `id_desa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kecamatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table paket_dau
# ------------------------------------------------------------

CREATE TABLE `paket_dau` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_paket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` double NOT NULL,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pagu` double NOT NULL,
  `tahun` int NOT NULL,
  `id_sumber_dana_dpa` bigint unsigned NOT NULL,
  `id_dpa` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `kecamatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `paket_dak_foreign` (`id_sumber_dana_dpa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table paket_dau_lokasi
# ------------------------------------------------------------

CREATE TABLE `paket_dau_lokasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_paket_dau` int NOT NULL,
  `id_desa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kecamatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table password_resets
# ------------------------------------------------------------

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table pegawai
# ------------------------------------------------------------

CREATE TABLE `pegawai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tempat_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `no_telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_unit_kerja` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pegawai_nip_unique` (`nip`),
  KEY `pegawai_id_unit_kerja_foreign` (`id_unit_kerja`),
  CONSTRAINT `pegawai_id_unit_kerja_foreign` FOREIGN KEY (`id_unit_kerja`) REFERENCES `unit_kerja` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table pegawai_penanggung_jawab
# ------------------------------------------------------------

CREATE TABLE `pegawai_penanggung_jawab` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_unit_kerja` bigint unsigned NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table perangkat_desa
# ------------------------------------------------------------

CREATE TABLE `perangkat_desa` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_desa` int DEFAULT NULL,
  `nama_desa` varchar(100) DEFAULT NULL,
  `nama_kepala` varchar(100) DEFAULT NULL,
  `jabatan_kepala` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `kode_desa` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` int DEFAULT NULL,
  `user_update` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table periode
# ------------------------------------------------------------

CREATE TABLE `periode` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_periode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table profile_daerah
# ------------------------------------------------------------

CREATE TABLE `profile_daerah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_daerah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pimpinan_daerah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `visi_daerah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `misi_daerah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table program
# ------------------------------------------------------------

CREATE TABLE `program` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_program` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_program` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bidang_urusan` bigint unsigned NOT NULL,
  `capaian_program` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `program_id_bidang_urusan_foreign` (`id_bidang_urusan`),
  CONSTRAINT `program_id_bidang_urusan_foreign` FOREIGN KEY (`id_bidang_urusan`) REFERENCES `bidang_urusan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table realisasi
# ------------------------------------------------------------

CREATE TABLE `realisasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` int NOT NULL COMMENT 'dalam triwulan',
  `realisasi_keuangan` double NOT NULL DEFAULT '0',
  `realisasi_kinerja` double NOT NULL,
  `realisasi_fisik` double NOT NULL DEFAULT '0',
  `tahun` int NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `permasalahan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `realisasi_dpa_foreign` (`id_dpa`),
  CONSTRAINT `realisasi_dpa_foreign` FOREIGN KEY (`id_dpa`) REFERENCES `dpa` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table realisasi_dak
# ------------------------------------------------------------

CREATE TABLE `realisasi_dak` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_paket_dak` int NOT NULL,
  `periode` int NOT NULL COMMENT 'dalam triwulan',
  `realisasi_keuangan` double NOT NULL DEFAULT '0',
  `realisasi_fisik` double NOT NULL DEFAULT '0',
  `total_keuangan` double NOT NULL DEFAULT '0',
  `total_fisik` double NOT NULL DEFAULT '0',
  `tahun` int NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `permasalahan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `realisasi_dpa_foreign` (`id_dpa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table renstra_kegiatan
# ------------------------------------------------------------

CREATE TABLE `renstra_kegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tujuan` int NOT NULL,
  `id_sasaran` int NOT NULL,
  `id_urusan` int NOT NULL,
  `id_bidang_urusan` int NOT NULL,
  `id_program` bigint unsigned NOT NULL,
  `kode_program` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_renstra_program_outcome` int NOT NULL,
  `id_renstra_program` bigint unsigned NOT NULL,
  `id_kegiatan` bigint unsigned NOT NULL,
  `id_unit_kerja` int NOT NULL,
  `periode` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `renstra_kegiatan_id_renstra_program_foreign` (`id_renstra_program`),
  CONSTRAINT `renstra_kegiatan_id_renstra_program_foreign` FOREIGN KEY (`id_renstra_program`) REFERENCES `renstra_program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table renstra_kegiatan_output
# ------------------------------------------------------------

CREATE TABLE `renstra_kegiatan_output` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_renstra_kegiatan` int NOT NULL,
  `volume` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `output` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table renstra_program
# ------------------------------------------------------------

CREATE TABLE `renstra_program` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tujuan` bigint unsigned NOT NULL,
  `id_sasaran` bigint unsigned NOT NULL,
  `id_urusan` int NOT NULL,
  `id_bidang_urusan` int NOT NULL,
  `id_program` int NOT NULL,
  `kode_program` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_unit_kerja` int NOT NULL,
  `periode` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `program_id_sasaran_foreign` (`id_sasaran`),
  CONSTRAINT `program_id_sasaran_foreign` FOREIGN KEY (`id_sasaran`) REFERENCES `sasaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table renstra_program_outcome
# ------------------------------------------------------------

CREATE TABLE `renstra_program_outcome` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_renstra_program` int NOT NULL,
  `volume` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `outcome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table renstra_realisasi_sub_kegiatan
# ------------------------------------------------------------

CREATE TABLE `renstra_realisasi_sub_kegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_renstra_sub_kegiatan` int NOT NULL,
  `volume` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `realisasi_keuangan` double NOT NULL,
  `id_unit_kerja` int NOT NULL,
  `tahun` year NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table renstra_sub_kegiatan
# ------------------------------------------------------------

CREATE TABLE `renstra_sub_kegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tujuan` int NOT NULL,
  `id_sasaran` int NOT NULL,
  `id_urusan` int NOT NULL,
  `id_bidang_urusan` int NOT NULL,
  `id_program` int NOT NULL,
  `kode_program` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kegiatan` int NOT NULL,
  `id_unit_kerja` int NOT NULL,
  `id_sub_kegiatan` int NOT NULL,
  `id_renstra_kegiatan` bigint unsigned DEFAULT NULL,
  `total_pagu_renstra` double NOT NULL,
  `total_volume` double DEFAULT NULL,
  `periode` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `renstra_sub_kegaiatan` (`id_renstra_kegiatan`),
  CONSTRAINT `renstra_sub_kegiatan_id_kegiatan_foreign` FOREIGN KEY (`id_renstra_kegiatan`) REFERENCES `renstra_kegiatan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table renstra_sub_kegiatan_indikator
# ------------------------------------------------------------

CREATE TABLE `renstra_sub_kegiatan_indikator` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_renstra_sub_kegiatan` int NOT NULL,
  `volume` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `indikator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table renstra_sub_kegiatan_target
# ------------------------------------------------------------

CREATE TABLE `renstra_sub_kegiatan_target` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_renstra_sub_kegiatan` int NOT NULL,
  `volume` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pagu` double NOT NULL,
  `tahun` year NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table rkpd
# ------------------------------------------------------------

CREATE TABLE `rkpd` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_rpjmd_keuangan` double NOT NULL,
  `target_rpjmd_kinerja` double NOT NULL,
  `realisasi_rkpd_lalu_keuangan` double NOT NULL,
  `realisasi_rkpd_lalu_kinerja` double NOT NULL,
  `target_rkpd_sekarang_keuangan` double NOT NULL,
  `target_rkpd_sekarang_kinerja` double NOT NULL,
  `semester` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table role
# ------------------------------------------------------------

CREATE TABLE `role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_nama_role_unique` (`nama_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sasaran
# ------------------------------------------------------------

CREATE TABLE `sasaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL,
  `id_tujuan` bigint unsigned NOT NULL,
  `sasaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_unit_kerja` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sasaran_id_tujuan_foreign` (`id_tujuan`),
  CONSTRAINT `sasaran_id_tujuan_foreign` FOREIGN KEY (`id_tujuan`) REFERENCES `tujuan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table satuan
# ------------------------------------------------------------

CREATE TABLE `satuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sub_kegiatan
# ------------------------------------------------------------

CREATE TABLE `sub_kegiatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_sub_kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_sub_kegiatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kinerja` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `indikator` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_kegiatan` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_kegiatan_id_kegiatan_foreign` (`id_kegiatan`),
  CONSTRAINT `sub_kegiatan_id_kegiatan_foreign` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sub_tahapan
# ------------------------------------------------------------

CREATE TABLE `sub_tahapan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_tahapan` bigint unsigned NOT NULL,
  `sub_tahapan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sumber_dana
# ------------------------------------------------------------

CREATE TABLE `sumber_dana` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_sumber_dana` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sumber_dana_dpa
# ------------------------------------------------------------

CREATE TABLE `sumber_dana_dpa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_belanja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sumber_dana` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metode_pelaksanaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_pagu` double NOT NULL,
  `tahun` int NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sumber_dana_dpa_foreign` (`id_dpa`),
  CONSTRAINT `sumber_dana_dpa_foreign` FOREIGN KEY (`id_dpa`) REFERENCES `dpa` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table tahapan
# ------------------------------------------------------------

CREATE TABLE `tahapan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tahapan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table target
# ------------------------------------------------------------

CREATE TABLE `target` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` int NOT NULL COMMENT 'dalam triwulan',
  `target_keuangan` double DEFAULT NULL,
  `target_fisik` double DEFAULT NULL,
  `persentase` double DEFAULT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `target_dpa_foreign` (`id_dpa`),
  CONSTRAINT `target_dpa_foreign` FOREIGN KEY (`id_dpa`) REFERENCES `dpa` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table tolak_ukur
# ------------------------------------------------------------

CREATE TABLE `tolak_ukur` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_dpa` bigint unsigned NOT NULL,
  `tolak_ukur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` double NOT NULL,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tolak_ukur_dpa_foreign` (`id_dpa`),
  CONSTRAINT `tolak_ukur_dpa_foreign` FOREIGN KEY (`id_dpa`) REFERENCES `dpa` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table tujuan
# ------------------------------------------------------------

CREATE TABLE `tujuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL,
  `tujuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `id_unit_kerja` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table unit_bidang_verifikasi
# ------------------------------------------------------------

CREATE TABLE `unit_bidang_verifikasi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_perangkat` int DEFAULT NULL,
  `id_bidang` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` int DEFAULT NULL,
  `user_update` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table unit_kerja
# ------------------------------------------------------------

CREATE TABLE `unit_kerja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_unit_kerja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_unit_kerja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kepala` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip_kepala` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pangkat_kepala` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_kepala` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  `max_pagu` bigint DEFAULT NULL,
  `nama_jabatan_kepala` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table unit_kerja_bidang_urusan
# ------------------------------------------------------------

CREATE TABLE `unit_kerja_bidang_urusan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_unit_kerja` bigint unsigned NOT NULL,
  `id_bidang_urusan` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_kerja_foreign` (`id_unit_kerja`),
  KEY `bidang_urusan_foreign` (`id_bidang_urusan`),
  CONSTRAINT `bidang_urusan_foreign` FOREIGN KEY (`id_bidang_urusan`) REFERENCES `bidang_urusan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `unit_kerja_foreign` FOREIGN KEY (`id_unit_kerja`) REFERENCES `unit_kerja` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table unit_kerja_pagu
# ------------------------------------------------------------

CREATE TABLE `unit_kerja_pagu` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_unit_kerja` int NOT NULL,
  `tahun` year NOT NULL,
  `max_pagu_tahun` bigint NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;



# Dump of table urusan
# ------------------------------------------------------------

CREATE TABLE `urusan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_urusan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_urusan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `urusan_kode_urusan_unique` (`kode_urusan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user
# ------------------------------------------------------------

CREATE TABLE `user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_unit_kerja` bigint unsigned DEFAULT NULL,
  `id_role` bigint unsigned NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` bigint unsigned DEFAULT NULL,
  `user_update` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table verifikasi_documents
# ------------------------------------------------------------

CREATE TABLE `verifikasi_documents` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `verifikasi` enum('0','1') DEFAULT NULL,
  `id_master_verifikasi` int DEFAULT NULL,
  `id_documents` int DEFAULT NULL,
  `tindak_lanjut` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_insert` int DEFAULT NULL,
  `user_update` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
