-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table daengweb.provinces
CREATE TABLE IF NOT EXISTS `provinces` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table daengweb.provinces: ~34 rows (approximately)
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Bali', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(2, 'Bangka Belitung', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(3, 'Banten', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(4, 'Bengkulu', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(5, 'DI Yogyakarta', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(6, 'DKI Jakarta', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(7, 'Gorontalo', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(8, 'Jambi', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(9, 'Jawa Barat', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(10, 'Jawa Tengah', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(11, 'Jawa Timur', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(12, 'Kalimantan Barat', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(13, 'Kalimantan Selatan', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(14, 'Kalimantan Tengah', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(15, 'Kalimantan Timur', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(16, 'Kalimantan Utara', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(17, 'Kepulauan Riau', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(18, 'Lampung', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(19, 'Maluku', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(20, 'Maluku Utara', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(21, 'Nanggroe Aceh Darussalam (NAD)', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(22, 'Nusa Tenggara Barat (NTB)', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(23, 'Nusa Tenggara Timur (NTT)', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(24, 'Papua', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(25, 'Papua Barat', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(26, 'Riau', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(27, 'Sulawesi Barat', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(28, 'Sulawesi Selatan', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(29, 'Sulawesi Tengah', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(30, 'Sulawesi Tenggara', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(31, 'Sulawesi Utara', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(32, 'Sumatera Barat', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(33, 'Sumatera Selatan', '2019-08-29 12:55:52', '2019-08-29 12:55:52'),
	(34, 'Sumatera Utara', '2019-08-29 12:55:52', '2019-08-29 12:55:52');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
