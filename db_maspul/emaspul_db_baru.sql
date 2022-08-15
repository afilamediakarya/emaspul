# ************************************************************
# Sequel Ace SQL dump
# Version 20033
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 8.0.29)
# Database: emonev
# Generation Time: 2022-08-15 07:05:10 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table bidang_verifikasi
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bidang_verifikasi`;

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

LOCK TABLES `bidang_verifikasi` WRITE;
/*!40000 ALTER TABLE `bidang_verifikasi` DISABLE KEYS */;

INSERT INTO `bidang_verifikasi` (`id`, `nama_bidang`, `status`, `created_at`, `updated_at`, `user_insert`, `user_update`)
VALUES
	(2,'Infrastruktur','1','2022-08-11 03:23:51','2022-08-11 03:23:51',1,NULL),
	(3,'Ekonomi','1','2022-08-11 03:49:31','2022-08-11 03:49:31',1,NULL),
	(6,'Infrastruktur','1','2022-08-12 07:27:53','2022-08-12 07:27:53',1,NULL),
	(7,'Pembangunan Manuasia','1','2022-08-12 11:20:09','2022-08-12 11:20:09',1,NULL);

/*!40000 ALTER TABLE `bidang_verifikasi` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table documents
# ------------------------------------------------------------

DROP TABLE IF EXISTS `documents`;

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

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;

INSERT INTO `documents` (`id`, `nama_documents`, `periode_awal`, `periode_akhir`, `file_document`, `status_document`, `jenis_document`, `nomor_perbub`, `tanggal_perbub`, `tahun`, `id_perangkat`, `created_at`, `updated_at`, `user_insert`, `user_update`, `id_verifikator`)
VALUES
	(31,'Tes Dokumen RKPDes 01',NULL,NULL,'0_dokumen_desa_tes_dokumen_rkpdes_01_rkpdes_2022.pdf','3','2',NULL,NULL,'2022',0,'2022-08-14 20:07:11','2022-08-14 20:11:32',53,NULL,54),
	(32,'Tes Dokumen RKPDes 02',NULL,NULL,'0_dokumen_desa_tes_dokumen_rkpdes_02_rkpdes_2022.pdf','4','2',NULL,NULL,'2022',0,'2022-08-14 20:19:53','2022-08-14 20:27:43',53,NULL,1),
	(33,'tes dokumen spgdes 01',NULL,NULL,'0_dokumen_desa_tes_dokumen_spgdes_01_sdgs_2022.pdf','4','5',NULL,NULL,'2022',0,'2022-08-14 21:03:21','2022-08-14 21:03:21',53,NULL,NULL),
	(34,'Tes Dokumen RKPDes 03',NULL,NULL,'0_dokumen_desa_tes_dokumen_rkpdes_03_rkpdes_.pdf','1','2',NULL,NULL,NULL,0,'2022-08-14 21:52:33','2022-08-14 21:52:33',53,NULL,NULL),
	(35,'Tes Dokumen RPJMDes 01','2020','2021','0_dokumen_desa_tes_dokumen_rpjmdes_01_rpjmdes_.pdf','3','1',NULL,NULL,NULL,0,'2022-08-14 22:05:57','2022-08-14 22:06:45',53,NULL,54),
	(36,'Tes Dokumen RPJMDes 02','2020','2021','0_dokumen_desa_tes_dokumen_rpjmdes_02_rpjmdes_.pdf','4','1',NULL,NULL,NULL,0,'2022-08-15 06:37:03','2022-08-15 06:41:03',53,NULL,1);

/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table documents_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `documents_history`;

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

LOCK TABLES `documents_history` WRITE;
/*!40000 ALTER TABLE `documents_history` DISABLE KEYS */;

INSERT INTO `documents_history` (`id`, `action`, `id_documents`, `created_at`, `updated_at`, `user_insert`, `user_update`)
VALUES
	(18,'tambah data',31,'2022-08-14 20:07:11','2022-08-14 20:07:11',53,NULL),
	(19,'tambah data',32,'2022-08-14 20:19:53','2022-08-14 20:19:53',53,NULL),
	(20,'tambah data',33,'2022-08-14 21:03:21','2022-08-14 21:03:21',53,NULL),
	(21,'tambah data',34,'2022-08-14 21:52:33','2022-08-14 21:52:33',53,NULL),
	(22,'tambah data',35,'2022-08-14 22:05:57','2022-08-14 22:05:57',53,NULL),
	(23,'tambah data',36,'2022-08-15 06:37:03','2022-08-15 06:37:03',53,NULL);

/*!40000 ALTER TABLE `documents_history` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table master_verifikasi
# ------------------------------------------------------------

DROP TABLE IF EXISTS `master_verifikasi`;

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

LOCK TABLES `master_verifikasi` WRITE;
/*!40000 ALTER TABLE `master_verifikasi` DISABLE KEYS */;

INSERT INTO `master_verifikasi` (`id`, `indikator`, `jenis_documents`, `created_at`, `updated_at`, `user_insert`, `user_update`)
VALUES
	(1,'Surat Keputusan Kepala Desa tentang Tim Penyusun RPJM Desa','1',NULL,NULL,NULL,NULL),
	(2,'Surat Keputusan Kepala Desa tentang Tim Penyusun RPJM Desa','1',NULL,NULL,NULL,NULL),
	(3,'Surat Keputusan Kepala Desa tentang Tim Penyusun RPJM Desa','1',NULL,NULL,NULL,NULL),
	(4,'Surat Keputusan Kepala Desa tentang Tim Penyusun RPJM Desa','1',NULL,NULL,NULL,NULL),
	(5,'Verifikasi A RKPDES','2',NULL,NULL,NULL,NULL),
	(6,'Verifikasi B RKPDES','2',NULL,NULL,NULL,NULL),
	(7,'Verifikasi A RENSTRA','3',NULL,NULL,NULL,NULL),
	(8,'Verifikasi B RENSTRA','3',NULL,NULL,NULL,NULL),
	(9,'Verifikasi C RENSTRA','3',NULL,NULL,NULL,NULL),
	(10,'Verifikasi A RENJA','4',NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `master_verifikasi` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table perangkat_desa
# ------------------------------------------------------------

DROP TABLE IF EXISTS `perangkat_desa`;

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

LOCK TABLES `perangkat_desa` WRITE;
/*!40000 ALTER TABLE `perangkat_desa` DISABLE KEYS */;

INSERT INTO `perangkat_desa` (`id`, `id_desa`, `nama_desa`, `nama_kepala`, `jabatan_kepala`, `status`, `kode_desa`, `created_at`, `updated_at`, `user_insert`, `user_update`)
VALUES
	(2,2,'Bangkala','Syamsuddin M.M','Kaur pemerintahan','definitif','001','2022-08-09 16:08:01','2022-08-12 07:09:08',1,NULL),
	(3,3,'Patondon Salu','Andi Abdillah','Kaur pemerintahan','plt','002','2022-08-10 08:41:13','2022-08-12 07:08:23',1,NULL),
	(4,8,'Puncak Harapan','Alfian','Kaur Pembangunan','definitif','010','2022-08-10 09:00:08','2022-08-12 11:20:36',1,NULL),
	(5,3,'Patondon Salu','Haerul Azwar','Kaur pemerintahan','plt','005','2022-08-12 11:17:52','2022-08-12 11:24:52',1,NULL);

/*!40000 ALTER TABLE `perangkat_desa` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table unit_bidang_verifikasi
# ------------------------------------------------------------

DROP TABLE IF EXISTS `unit_bidang_verifikasi`;

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

LOCK TABLES `unit_bidang_verifikasi` WRITE;
/*!40000 ALTER TABLE `unit_bidang_verifikasi` DISABLE KEYS */;

INSERT INTO `unit_bidang_verifikasi` (`id`, `id_perangkat`, `id_bidang`, `created_at`, `updated_at`, `user_insert`, `user_update`)
VALUES
	(1,2,2,'2022-08-11 03:23:51','2022-08-11 03:23:51',1,NULL),
	(2,4,2,'2022-08-11 03:23:51','2022-08-11 03:23:51',1,NULL),
	(3,2,3,'2022-08-11 03:49:31','2022-08-11 03:49:31',1,NULL),
	(4,4,6,'2022-08-12 07:27:53','2022-08-12 07:27:53',1,NULL),
	(5,3,6,'2022-08-12 07:27:53','2022-08-12 07:27:53',1,NULL),
	(6,2,7,'2022-08-12 11:20:09','2022-08-12 11:20:09',1,NULL),
	(7,5,7,'2022-08-12 11:20:09','2022-08-12 11:20:09',1,NULL);

/*!40000 ALTER TABLE `unit_bidang_verifikasi` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table verifikasi_documents
# ------------------------------------------------------------

DROP TABLE IF EXISTS `verifikasi_documents`;

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

LOCK TABLES `verifikasi_documents` WRITE;
/*!40000 ALTER TABLE `verifikasi_documents` DISABLE KEYS */;

INSERT INTO `verifikasi_documents` (`id`, `verifikasi`, `id_master_verifikasi`, `id_documents`, `tindak_lanjut`, `created_at`, `updated_at`, `user_insert`, `user_update`)
VALUES
	(17,'0',5,31,'B','2022-08-14 20:07:11','2022-08-14 20:26:53',53,NULL),
	(18,'1',6,31,'A','2022-08-14 20:07:11','2022-08-14 20:26:53',53,NULL),
	(19,'1',5,32,'A','2022-08-14 20:19:53','2022-08-14 20:27:43',53,NULL),
	(20,'1',6,32,'A','2022-08-14 20:19:53','2022-08-14 20:27:43',53,NULL),
	(21,'0',5,34,NULL,'2022-08-14 21:52:33','2022-08-14 21:52:33',53,NULL),
	(22,'0',6,34,NULL,'2022-08-14 21:52:33','2022-08-14 21:52:33',53,NULL),
	(23,'1',1,35,NULL,'2022-08-14 22:05:57','2022-08-14 22:06:45',53,NULL),
	(24,'0',2,35,NULL,'2022-08-14 22:05:57','2022-08-14 22:05:57',53,NULL),
	(25,'0',3,35,NULL,'2022-08-14 22:05:57','2022-08-14 22:05:57',53,NULL),
	(26,'0',4,35,NULL,'2022-08-14 22:05:57','2022-08-14 22:05:57',53,NULL),
	(27,'1',1,36,NULL,'2022-08-15 06:37:03','2022-08-15 06:37:54',53,NULL),
	(28,'1',2,36,NULL,'2022-08-15 06:37:03','2022-08-15 06:37:54',53,NULL),
	(29,'1',3,36,NULL,'2022-08-15 06:37:03','2022-08-15 06:41:03',53,NULL),
	(30,'1',4,36,NULL,'2022-08-15 06:37:03','2022-08-15 06:41:03',53,NULL);

/*!40000 ALTER TABLE `verifikasi_documents` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
