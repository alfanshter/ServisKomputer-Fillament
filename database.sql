-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: db_pwscomp
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('pwscomp-cache-dashboard_stats_overview','a:15:{s:12:\"totalPesanan\";i:25;s:17:\"pesananBelumMulai\";i:1;s:14:\"pesananAnalisa\";i:11;s:17:\"pesananKonfirmasi\";i:3;s:18:\"pesananDalamProses\";i:3;s:14:\"pesananSelesai\";i:1;s:14:\"pesananDibayar\";i:6;s:12:\"pesananBatal\";i:0;s:14:\"totalPelanggan\";i:20;s:10:\"pendapatan\";s:10:\"2072000.00\";s:11:\"pengeluaran\";s:9:\"615500.00\";s:10:\"labaBersih\";d:1456500;s:14:\"totalSparepart\";i:9;s:17:\"sparepartLowStock\";i:4;s:9:\"poPending\";i:1;}',1763622074);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_10_06_172625_create_pesanans_table',1),(5,'2025_10_06_173641_create_pesanan_order_photos_table',1),(6,'2025_10_24_034332_create_spareparts_table',1),(7,'2025_10_24_041431_create_transactions_table',1),(8,'2025_11_11_235417_add_kelengkapan_to_pesanans_table',2),(9,'2025_11_12_001620_add_kelengkapan_to_pesanans_table',3),(10,'2025_11_12_001924_add_kelengkapan_to_pesanans_table',4),(11,'2025_11_15_153447_create_pesanan_status_histories_table',5),(12,'2025_11_16_024200_create_pesanan_sparepart_table',5),(13,'2025_11_16_033705_add_total_cost_to_pesanans_table',5),(14,'2025_11_16_033714_add_total_cost_to_pesanans_table',5),(15,'2025_11_16_052736_add_pricing_fields_to_spareparts_table',5),(16,'2025_11_16_052811_create_sparepart_purchases_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan_order_photos`
--

DROP TABLE IF EXISTS `pesanan_order_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan_order_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint unsigned NOT NULL,
  `type` enum('before','after','progress') COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_order_photos_pesanan_id_foreign` (`pesanan_id`),
  CONSTRAINT `pesanan_order_photos_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan_order_photos`
--

LOCK TABLES `pesanan_order_photos` WRITE;
/*!40000 ALTER TABLE `pesanan_order_photos` DISABLE KEYS */;
INSERT INTO `pesanan_order_photos` VALUES (13,7,'before','foto-before/01K9C6T6RYB5WXD5Q5QDAY7GR9.jpg','2025-11-06 02:08:56','2025-11-06 02:08:56'),(14,13,'before','foto-before/01K9C7N57XDBVHH7S0V81P77DT.jpg','2025-11-06 02:23:39','2025-11-06 02:23:39'),(15,14,'before','foto-before/01K9C7X35J2Y9SQM4XEG6S5HP9.jpg','2025-11-06 02:27:59','2025-11-06 02:27:59'),(16,5,'progress','progress_photos/01K9C9C1WYXAFCMVF7F9127MQR.jpeg','2025-11-06 02:53:38','2025-11-06 02:53:38'),(17,5,'progress','progress_photos/01K9C9C1XAT9G2JFMJ4841FJCV.jpeg','2025-11-06 02:53:38','2025-11-06 02:53:38'),(21,8,'before','foto-before/01K9DZ28YSPD9M4J6M9Z4XJ657.jpg','2025-11-06 18:32:01','2025-11-06 18:32:01'),(22,8,'before','foto-before/01K9DZ28YV10X9EN78XYA7WGEH.jpg','2025-11-06 18:32:01','2025-11-06 18:32:01'),(23,8,'before','foto-before/01K9DZ28YX4YSRAJND6G33ENAS.jpg','2025-11-06 18:32:01','2025-11-06 18:32:01'),(24,8,'before','foto-before/01K9DZ28YY8MH5M9K47745T5C6.jpg','2025-11-06 18:32:02','2025-11-06 18:32:02'),(25,11,'before','foto-before/01K9DZ6HV3ESERSWRC6VHSDT9R.jpg','2025-11-06 18:34:21','2025-11-06 18:34:21'),(26,11,'before','foto-before/01K9DZ6HV63H1WB13Y5A8JKBZG.jpg','2025-11-06 18:34:21','2025-11-06 18:34:21'),(27,9,'before','foto-before/01K9DZ94J6Z8934EA61HE7JS12.jpg','2025-11-06 18:35:46','2025-11-06 18:35:46'),(28,9,'before','foto-before/01K9DZ94J8KBR881Y9F36HVPXY.jpg','2025-11-06 18:35:46','2025-11-06 18:35:46'),(32,6,'progress','progress_photos/01K9DZWNWVGCMS81CBHF572PDW.jpg','2025-11-06 18:46:26','2025-11-06 18:46:26'),(33,6,'progress','progress_photos/01K9DZWNWXFFGCW02KPKF82WJ5.jpg','2025-11-06 18:46:26','2025-11-06 18:46:26'),(34,6,'progress','progress_photos/01K9DZWNWYX88J9DWP7Q3C18B9.jpg','2025-11-06 18:46:26','2025-11-06 18:46:26'),(44,10,'progress','progress_photos/01K9E128NRE2WA55FDFGX72PXR.jpg','2025-11-06 19:06:58','2025-11-06 19:06:58'),(45,10,'progress','progress_photos/01K9E128NT550WFSJGEQH86V8X.jpg','2025-11-06 19:06:58','2025-11-06 19:06:58'),(46,10,'progress','progress_photos/01K9E128NWVY840SV5BB3PC7D3.jpg','2025-11-06 19:06:58','2025-11-06 19:06:58'),(47,10,'progress','progress_photos/01K9E128NXWS5001SHZPKN7Z3E.jpg','2025-11-06 19:06:58','2025-11-06 19:06:58'),(48,10,'progress','progress_photos/01K9E128NY1GH3NG48BQG07CNC.jpg','2025-11-06 19:06:58','2025-11-06 19:06:58'),(49,10,'progress','progress_photos/01K9E128NZHXEGTHWWW3FYF7FM.jpg','2025-11-06 19:06:58','2025-11-06 19:06:58'),(52,15,'before','foto-before/01K9EDWHANT2FWRYSX1MKAFGKP.jpg','2025-11-06 22:51:02','2025-11-06 22:51:02'),(53,15,'before','foto-before/01K9EDWHAPST3WCXA4BXJFCY7J.jpg','2025-11-06 22:51:02','2025-11-06 22:51:02'),(54,15,'before','foto-before/01K9EDWHARGZF28H64PDH0B24S.jpg','2025-11-06 22:51:02','2025-11-06 22:51:02'),(55,15,'before','foto-before/01K9EDWHARGZF28H64PDH0B24T.jpg','2025-11-06 22:51:02','2025-11-06 22:51:02'),(56,4,'before','foto-before/01K9EE78QJ9RN8EC206BRECR46.jpg','2025-11-06 22:56:53','2025-11-06 22:56:53'),(57,4,'before','foto-before/01K9EE78QM1YGZSXVQ28WE1T1W.jpg','2025-11-06 22:56:53','2025-11-06 22:56:53'),(58,3,'before','foto-before/01K9EEX49588DX9C52J8QS5P4J.jpg','2025-11-06 23:08:50','2025-11-06 23:08:50'),(59,3,'before','foto-before/01K9EEX4960KD6NRHSJD2PQK5D.jpg','2025-11-06 23:08:50','2025-11-06 23:08:50'),(60,3,'before','foto-before/01K9EEX497VVE3FS4K8XCT5A4F.jpg','2025-11-06 23:08:50','2025-11-06 23:08:50'),(61,3,'before','foto-before/01K9EEX49807K6A9FF5DAQ5NEY.jpg','2025-11-06 23:08:50','2025-11-06 23:08:50'),(76,6,'before','foto-before/01K9DZCFF0FFEVWNXWEXN8Y06D.jpg','2025-11-07 01:34:25','2025-11-07 01:34:25'),(77,6,'before','foto-before/01K9DZCFF2DECV95YZMRTMR5T1.jpg','2025-11-07 01:34:25','2025-11-07 01:34:25'),(78,6,'before','foto-before/01K9DZCFF3HYDEA4B8XHR586X7.jpg','2025-11-07 01:34:25','2025-11-07 01:34:25'),(79,6,'after','after/01K9E03KJKW4R3SEAT1YPDXPQX.jpg','2025-11-07 01:34:25','2025-11-07 01:34:25'),(84,10,'before','foto-before/01K9DYDZ5S4CW546RDCJ6HBK80.jpg','2025-11-07 18:13:00','2025-11-07 18:13:00'),(85,10,'before','foto-before/01K9DYDZ5VJA7NJD34QR50XPCJ.jpg','2025-11-07 18:13:00','2025-11-07 18:13:00'),(86,10,'before','foto-before/01K9DYDZ5W32WZ3Q6EAZCNECKZ.jpg','2025-11-07 18:13:00','2025-11-07 18:13:00'),(87,10,'after','after/01K9E1KQDZDRGBYTRG1XDWP9RK.jpg','2025-11-07 18:13:00','2025-11-07 18:13:00'),(88,10,'after','after/01K9E1KQE0WC2NZBR5RSMZE7FE.jpg','2025-11-07 18:13:00','2025-11-07 18:13:00'),(97,5,'before','foto-before/01K9C5S60890X2Y98Y57ZX1Z6X.jpeg','2025-11-07 19:33:22','2025-11-07 19:33:22'),(98,5,'before','foto-before/01K9C5S60A5MNRKMDAGB907AN3.jpeg','2025-11-07 19:33:22','2025-11-07 19:33:22'),(99,5,'before','foto-before/01K9C5S60BK4TX1XGQGNBE56J9.jpeg','2025-11-07 19:33:22','2025-11-07 19:33:22'),(100,5,'before','foto-before/01K9C5S60CS7JB8CJVZT653VFG.jpeg','2025-11-07 19:33:22','2025-11-07 19:33:22'),(115,16,'progress','progress_photos/01K9NS36AZ89XVQAT636HWZCMR.jpg','2025-11-09 19:21:35','2025-11-09 19:21:35'),(116,16,'progress','progress_photos/01K9NS36B2CY2KYEER54NHWTQR.jpg','2025-11-09 19:21:35','2025-11-09 19:21:35'),(117,16,'progress','progress_photos/01K9NS36B3PGTY8TN5MY5W1VY9.jpg','2025-11-09 19:21:35','2025-11-09 19:21:35'),(118,16,'progress','progress_photos/01K9NS36B3PGTY8TN5MY5W1VYA.jpg','2025-11-09 19:21:35','2025-11-09 19:21:35'),(119,11,'progress','progress_photos/01K9P4A5HW1SVEC8PG1VSKX8YF.jpg','2025-11-09 22:37:38','2025-11-09 22:37:38'),(120,11,'progress','progress_photos/01K9P4A5HYARABKKYGM2YNXB6P.jpg','2025-11-09 22:37:38','2025-11-09 22:37:38'),(121,11,'progress','progress_photos/01K9P4A5HZS4PHHYRCE1M0QPT7.jpg','2025-11-09 22:37:38','2025-11-09 22:37:38'),(122,11,'progress','progress_photos/01K9P4A5J024WJRDT5AVRS20S9.jpg','2025-11-09 22:37:38','2025-11-09 22:37:38'),(123,11,'after','after/01K9P4DG305HRZFVH2QQSXQDD8.jpg','2025-11-09 22:39:27','2025-11-09 22:39:27'),(124,19,'before','foto-before/01K9NP9M4BWDKNFT3NJFB1XASS.jpg','2025-11-09 22:56:55','2025-11-09 22:56:55'),(125,19,'before','foto-before/01K9NP9M4DYE0P3GVTZVBYX3JT.jpg','2025-11-09 22:56:55','2025-11-09 22:56:55'),(126,19,'before','foto-before/01K9NP9M4EX3GTKT4D7NYNQ0QS.jpg','2025-11-09 22:56:55','2025-11-09 22:56:55'),(127,20,'before','foto-before/01K9PG3481V38VQX6AP3EGR2PH.jpg','2025-11-10 02:03:30','2025-11-10 02:03:30'),(128,20,'before','foto-before/01K9PG3483T73BBS1AS2MTA2E2.jpg','2025-11-10 02:03:30','2025-11-10 02:03:30'),(129,20,'before','foto-before/01K9PG3484KW4RGH1Q4CM8W7C9.jpg','2025-11-10 02:03:30','2025-11-10 02:03:30'),(130,21,'before','foto-before/01K9R819JB41ZVAQE0S8T8RKNC.jpg','2025-11-10 18:21:10','2025-11-10 18:21:10'),(131,21,'before','foto-before/01K9R819JW96C2KNHM8CY6P3FP.jpg','2025-11-10 18:21:10','2025-11-10 18:21:10'),(132,21,'before','foto-before/01K9R819JXBDFWZYJXYR42J1DW.jpg','2025-11-10 18:21:11','2025-11-10 18:21:11'),(133,22,'before','foto-before/01K9R823T2HD7PYW0PZ549QRE0.jpg','2025-11-10 18:21:37','2025-11-10 18:21:37'),(134,22,'before','foto-before/01K9R823T46J753N0FXSRG1J9V.jpg','2025-11-10 18:21:37','2025-11-10 18:21:37'),(135,22,'before','foto-before/01K9R823T5G6YA0WGB64RCKVW6.jpg','2025-11-10 18:21:37','2025-11-10 18:21:37'),(136,2,'progress','progress_photos/01K9RC0Q7TD9NH5Y222PEH6DPQ.jpg','2025-11-10 19:30:46','2025-11-10 19:30:46'),(137,2,'progress','progress_photos/01K9RC0Q7YHSQQEP7526VHGZCD.jpg','2025-11-10 19:30:46','2025-11-10 19:30:46'),(138,2,'progress','progress_photos/01K9RC0Q80GZQD6DXATCYYP0NE.jpg','2025-11-10 19:30:46','2025-11-10 19:30:46'),(139,2,'progress','progress_photos/01K9RC0Q81SXXEV96VCTNJEENP.jpg','2025-11-10 19:30:46','2025-11-10 19:30:46'),(146,16,'before','foto-before/01K9EFFF02Z378FJBY1560YT75.jpg','2025-11-11 03:46:27','2025-11-11 03:46:27'),(147,16,'before','foto-before/01K9EFFF04HRXJ3S1C0GCQ9MNK.jpg','2025-11-11 03:46:27','2025-11-11 03:46:27'),(148,16,'before','foto-before/01K9EFFF05PMB3EWSSEKDSNFZ1.jpg','2025-11-11 03:46:27','2025-11-11 03:46:27'),(149,16,'before','foto-before/01K9EFFF0607TDE4XX7XAHBY0W.jpg','2025-11-11 03:46:27','2025-11-11 03:46:27'),(150,16,'after','after/01K9RCE75KV1HTZBDES0VYT644.jpg','2025-11-11 03:46:27','2025-11-11 03:46:27'),(156,2,'before','foto-before/01K9C5CMCH64BDBFQ2MRAMDQNX.jpeg','2025-11-11 17:22:54','2025-11-11 17:22:54'),(157,2,'before','foto-before/01K9C5CMCK3GK7GRXS0AE3NCC7.jpeg','2025-11-11 17:22:54','2025-11-11 17:22:54'),(158,2,'before','foto-before/01K9C5CMCK3GK7GRXS0AE3NCC8.jpeg','2025-11-11 17:22:54','2025-11-11 17:22:54'),(159,2,'before','foto-before/01K9C5CMCMQA2HGJJ79KQCP72H.jpeg','2025-11-11 17:22:54','2025-11-11 17:22:54'),(160,2,'after','after/01K9RC2AH9ZE5B7K6P7GJ7FYBA.jpg','2025-11-11 17:22:54','2025-11-11 17:22:54'),(161,22,'progress','progress_photos/01K9V1W9T0D7B5FG757V7WRR1P.jpg','2025-11-11 20:31:19','2025-11-11 20:31:19'),(162,22,'progress','progress_photos/01K9V1W9T4RWKFRGRXA75DYB8K.jpg','2025-11-11 20:31:19','2025-11-11 20:31:19'),(163,22,'progress','progress_photos/01K9V1W9T5DDN8KQWEW9ZDG4HR.jpg','2025-11-11 20:31:19','2025-11-11 20:31:19'),(164,22,'progress','progress_photos/01K9V1W9T7D4MHRFTAZXR5YTB9.jpg','2025-11-11 20:31:19','2025-11-11 20:31:19'),(165,22,'progress','progress_photos/01K9V1W9T81FZF8QY7MVWCBMJ4.jpg','2025-11-11 20:31:19','2025-11-11 20:31:19'),(166,22,'progress','progress_photos/01K9V1W9T9JKMM17VNQ4M2FS9Q.jpg','2025-11-11 20:31:19','2025-11-11 20:31:19'),(167,22,'progress','progress_photos/01K9V1W9TBGK1CEWBW66954E3C.jpg','2025-11-11 20:31:19','2025-11-11 20:31:19'),(168,22,'after','after/01K9V2N6B0ATA6KXSV60D2GHE7.jpg','2025-11-11 20:44:54','2025-11-11 20:44:54'),(169,22,'after','after/01K9V2N6B4WF8W6EY5JMR49X7F.jpg','2025-11-11 20:44:54','2025-11-11 20:44:54'),(173,25,'before','foto-before/01KA2NWCH0FBQYNK5GYX1A7W1C.jpg','2025-11-14 19:35:34','2025-11-14 19:35:34'),(174,25,'before','foto-before/01KA2NWCH2BPF97XPWY76X383F.jpg','2025-11-14 19:35:34','2025-11-14 19:35:34'),(175,25,'before','foto-before/01KA2NWCH3Y0BHZZDYJMPZ7CB5.jpg','2025-11-14 19:35:34','2025-11-14 19:35:34'),(176,26,'before','foto-before/01KA2PN2H4T1NRYWE1DTPB0BAP.jpg','2025-11-14 19:49:03','2025-11-14 19:49:03'),(177,26,'before','foto-before/01KA2PN2H6XK6WF2NRJFWVVH8G.jpg','2025-11-14 19:49:03','2025-11-14 19:49:03'),(178,26,'before','foto-before/01KA2PN2H7A02D4NMJQQ8GVDWF.jpg','2025-11-14 19:49:03','2025-11-14 19:49:03'),(179,25,'progress','progress_photos/01KA5CS3GMPGC2MB0WDX55DC1Q.jpg','2025-11-15 20:54:12','2025-11-15 20:54:12'),(180,25,'after','after/01KA5GT7FJXQ58X3M8J54XPTZQ.jpg','2025-11-15 22:04:44','2025-11-15 22:04:44'),(181,27,'before','foto-before/01KA7X317M5Z8AZR3Y3WQSB4D2.jpg','2025-11-16 20:17:44','2025-11-16 20:17:44'),(182,27,'before','foto-before/01KA7X317PMMCPNPJPM4NWE56R.jpg','2025-11-16 20:17:44','2025-11-16 20:17:44'),(183,5,'after','after/01KA8CY6EREFE1319YNYXAY98W.jpeg','2025-11-17 00:54:43','2025-11-17 00:54:43'),(184,28,'before','foto-before/01KA8F2X5RV9M4QAX1395S5H4V.jpg','2025-11-17 01:32:14','2025-11-17 01:32:14'),(185,28,'before','foto-before/01KA8F2X5WT405TNJSCZZRJ3GG.jpg','2025-11-17 01:32:14','2025-11-17 01:32:14'),(186,28,'before','foto-before/01KA8F2X5XQ1MX7RJHJZB9SVC0.jpg','2025-11-17 01:32:14','2025-11-17 01:32:14'),(192,31,'before','foto-before/01KAAEZTHV30ME8GH9FYTXVQ6E.jpg','2025-11-17 20:09:02','2025-11-17 20:09:02'),(193,31,'before','foto-before/01KAAEZTHWP1HPBSNZN495DWVC.jpg','2025-11-17 20:09:03','2025-11-17 20:09:03'),(194,31,'before','foto-before/01KAAEZTHX5K8MKHMXP32DSR4E.jpg','2025-11-17 20:09:03','2025-11-17 20:09:03'),(195,31,'before','foto-before/01KAAEZTHX5K8MKHMXP32DSR4F.jpg','2025-11-17 20:09:03','2025-11-17 20:09:03'),(196,9,'progress','progress_photos/01KAAKRD276KAV32XZ4YXB2NWB.jpg','2025-11-17 21:32:22','2025-11-17 21:32:22'),(197,9,'progress','progress_photos/01KAAKRD29A7PJYW3H64HN9CAW.jpg','2025-11-17 21:32:22','2025-11-17 21:32:22'),(200,33,'before','foto-before/01KACZZ5SN32JQZJH6M3J7TA2W.jpg','2025-11-18 19:44:15','2025-11-18 19:44:15'),(201,33,'before','foto-before/01KACZZ5SQXJASASNYMCKXE2NS.jpg','2025-11-18 19:44:16','2025-11-18 19:44:16'),(202,33,'progress','progress_photos/01KAD030VG3S3XZ0HX70SB5RQN.jpg','2025-11-18 19:46:21','2025-11-18 19:46:21'),(203,33,'progress','progress_photos/01KAD030VJ491W2Z382SCW0AFW.jpg','2025-11-18 19:46:21','2025-11-18 19:46:21'),(204,31,'progress','progress_photos/01KAD1PZNMGZ0RSZ2FG7APTA4K.jpg','2025-11-18 20:14:44','2025-11-18 20:14:44'),(205,31,'progress','progress_photos/01KAD1PZNP0N2B1ZT11PG5M50Y.jpg','2025-11-18 20:14:44','2025-11-18 20:14:44'),(206,27,'progress','progress_photos/01KAFPJRNTQD5HK0W05GWKB35M.jpg','2025-11-19 20:57:55','2025-11-19 20:57:55'),(207,27,'progress','progress_photos/01KAFPJRNW61S6VFPN000CH7NT.jpg','2025-11-19 20:57:55','2025-11-19 20:57:55'),(208,8,'progress','progress_photos/01KAFQ8VXG1JZSFBY35MGG0N1W.jpg','2025-11-19 21:09:59','2025-11-19 21:09:59');
/*!40000 ALTER TABLE `pesanan_order_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan_sparepart`
--

DROP TABLE IF EXISTS `pesanan_sparepart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan_sparepart` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint unsigned NOT NULL,
  `sparepart_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_sparepart_pesanan_id_foreign` (`pesanan_id`),
  KEY `pesanan_sparepart_sparepart_id_foreign` (`sparepart_id`),
  CONSTRAINT `pesanan_sparepart_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_sparepart_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan_sparepart`
--

LOCK TABLES `pesanan_sparepart` WRITE;
/*!40000 ALTER TABLE `pesanan_sparepart` DISABLE KEYS */;
INSERT INTO `pesanan_sparepart` VALUES (2,9,7,1,138000.00,138000.00,'2025-11-17 21:32:22','2025-11-17 21:32:22'),(5,33,9,1,140500.00,140500.00,'2025-11-18 19:46:21','2025-11-18 19:46:21'),(6,33,10,1,265000.00,265000.00,'2025-11-18 19:46:21','2025-11-18 19:46:21'),(7,31,11,1,164000.00,164000.00,'2025-11-18 20:14:44','2025-11-18 20:14:44');
/*!40000 ALTER TABLE `pesanan_sparepart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan_status_histories`
--

DROP TABLE IF EXISTS `pesanan_status_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan_status_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint unsigned NOT NULL,
  `old_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_status_histories_pesanan_id_foreign` (`pesanan_id`),
  KEY `pesanan_status_histories_changed_by_foreign` (`changed_by`),
  CONSTRAINT `pesanan_status_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pesanan_status_histories_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan_status_histories`
--

LOCK TABLES `pesanan_status_histories` WRITE;
/*!40000 ALTER TABLE `pesanan_status_histories` DISABLE KEYS */;
INSERT INTO `pesanan_status_histories` VALUES (1,27,'belum mulai','analisa',1,NULL,'2025-11-16 20:17:44','2025-11-16 20:17:44'),(2,11,'selesai','dibayar',1,NULL,'2025-11-16 21:14:21','2025-11-16 21:14:21'),(3,25,'selesai','dibayar',1,NULL,'2025-11-16 21:16:12','2025-11-16 21:16:12'),(4,11,'dibayar','revisi',1,NULL,'2025-11-16 21:16:45','2025-11-16 21:16:45'),(5,6,'revisi','dibayar',1,NULL,'2025-11-16 21:18:07','2025-11-16 21:18:07'),(6,6,'dibayar','revisi',1,NULL,'2025-11-16 21:18:39','2025-11-16 21:18:39'),(7,6,'revisi','dibayar',1,NULL,'2025-11-16 21:18:58','2025-11-16 21:18:58'),(8,10,'revisi','dibayar',1,NULL,'2025-11-17 00:51:43','2025-11-17 00:51:43'),(9,22,'revisi','dibayar',1,NULL,'2025-11-17 00:53:15','2025-11-17 00:53:15'),(10,5,'dalam proses','selesai',1,'Instal lulang saja, untuk Ram tidak jadi ','2025-11-17 00:54:43','2025-11-17 00:54:43'),(11,28,'belum mulai','analisa',1,NULL,'2025-11-17 01:32:14','2025-11-17 01:32:14'),(18,5,'selesai','dibayar',1,NULL,'2025-11-17 18:59:12','2025-11-17 18:59:12'),(20,31,'belum mulai','analisa',1,NULL,'2025-11-17 20:09:03','2025-11-17 20:09:03'),(21,9,'analisa','selesai_analisa',1,'kemasukan air\n','2025-11-17 21:32:22','2025-11-17 21:32:22'),(22,9,'selesai_analisa','konfirmasi',1,NULL,'2025-11-17 21:33:57','2025-11-17 21:33:57'),(23,9,'konfirmasi','dalam proses',1,'Customer menyetujui perbaikan','2025-11-17 21:34:23','2025-11-17 21:34:23'),(27,33,'belum mulai','analisa',1,NULL,'2025-11-18 19:44:16','2025-11-18 19:44:16'),(28,33,'analisa','selesai_analisa',1,'ganti baterai dan keyboard','2025-11-18 19:46:22','2025-11-18 19:46:22'),(29,33,'selesai_analisa','konfirmasi',1,NULL,'2025-11-18 19:47:00','2025-11-18 19:47:00'),(30,33,'konfirmasi','dalam proses',1,'Customer menyetujui perbaikan','2025-11-18 19:47:17','2025-11-18 19:47:17'),(31,31,'analisa','selesai_analisa',1,'lemot, speaker rusak, keyboard lepas, instal sofware(nitro dll)','2025-11-18 20:14:44','2025-11-18 20:14:44'),(32,31,'selesai_analisa','konfirmasi',1,NULL,'2025-11-19 01:45:35','2025-11-19 01:45:35'),(33,27,'selesai_analisa','konfirmasi',1,NULL,'2025-11-19 20:58:08','2025-11-19 20:58:08'),(34,8,'selesai_analisa','konfirmasi',1,NULL,'2025-11-19 21:11:06','2025-11-19 21:11:06');
/*!40000 ALTER TABLE `pesanan_status_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanans`
--

DROP TABLE IF EXISTS `pesanans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `device_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `damage_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelengkapan` text COLLATE utf8mb4_unicode_ci,
  `solution` text COLLATE utf8mb4_unicode_ci,
  `analisa` text COLLATE utf8mb4_unicode_ci,
  `priority` enum('normal','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `status` enum('belum mulai','analisa','selesai_analisa','konfirmasi','dalam proses','menunggu sparepart','selesai','dibayar','batal','revisi','on hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum mulai',
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `service_cost` decimal(12,2) DEFAULT NULL,
  `total_cost` decimal(12,2) DEFAULT NULL,
  `capital_cost` decimal(12,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanans_user_id_foreign` (`user_id`),
  CONSTRAINT `pesanans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanans`
--

LOCK TABLES `pesanans` WRITE;
/*!40000 ALTER TABLE `pesanans` DISABLE KEYS */;
INSERT INTO `pesanans` VALUES (2,3,' HP Probook 440 G10','Mati Total','Tas, Charger','ngisi ic power','ic power','normal','dibayar','2025-11-05 00:00:00',NULL,650000.00,650000.00,NULL,NULL,'2025-11-05 00:56:32','2025-11-16 18:36:20'),(3,4,'hp Omen','matot',NULL,NULL,NULL,'normal','analisa','2025-10-06 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-05 01:17:22','2025-11-06 23:08:49'),(4,4,'hp convertible x360','ganti ram 8gb + keyboard nambah baut',NULL,'ganti ram 8gb + keyboard nambah baut',NULL,'normal','analisa','2025-09-26 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-05 01:30:07','2025-11-06 22:56:53'),(5,5,'Lenovo Thinkpad L450','Mesin Nyala Layar Hitam',NULL,'ganti ram 4gb ddr3l, ganti baterai CMOS','salah satu ram rusak , baterai cmos lemah ','normal','dibayar','2025-11-05 00:00:00',NULL,100000.00,100000.00,NULL,'Instal lulang saja, untuk Ram tidak jadi ','2025-11-05 01:35:16','2025-11-17 18:59:12'),(6,6,'HP pavilion TS 11 notebook pc','ganti SSD 512gb',NULL,'Penggantian penyimpanan ke SSD 512GB berhasil menjadi solusi yang tepat untuk meningkatkan kecepatan dan performa perangkat. Perangkat kini dapat digunakan dengan cepat, stabil, dan efisien.','Pemasangan SSD 512GB telah dilakukan dengan baik dan benar. SSD berfungsi normal, terdeteksi oleh sistem, dan meningkatkan performa perangkat secara signifikan (booting lebih cepat, aplikasi lebih responsif).','normal','dibayar','2025-10-23 00:00:00',NULL,625.00,625.00,NULL,NULL,'2025-11-05 02:03:53','2025-11-16 21:18:58'),(7,7,'ASUS X441UA','mouse pad',NULL,'ganti kabel flexibel mousefed',NULL,'normal','analisa','2025-10-18 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-05 02:11:21','2025-11-06 02:08:56'),(8,8,'lenovo thinkpad x280','layar bermasalah & speaker,keyboard kadang ga fungsi pas baru nyala',NULL,'ganti kabel fleksibel+ganti LCD','layar bermasalah & speaker,keyboard kadang ga fungsi pas baru nyala\n','normal','konfirmasi','2025-10-21 00:00:00',NULL,125000.00,125000.00,NULL,NULL,'2025-11-05 22:30:27','2025-11-19 21:11:06'),(9,9,'hp redmi8','kemasukan air',NULL,'ganti baterai','kemasukan air\n','normal','dalam proses','2025-10-18 00:00:00',NULL,0.00,138000.00,NULL,NULL,'2025-11-05 22:35:36','2025-11-17 21:34:23'),(10,10,'lenovo ideapad 130-14ast','ganti keyboard, ganti baterai (opsional)',NULL,'ganti baterai dan keyboard','baterai rusak dan keyboard error','normal','dibayar','2025-10-25 00:00:00',NULL,403.00,403.00,NULL,NULL,'2025-11-05 22:40:01','2025-11-17 00:51:43'),(11,11,'LENOVO G40-45','GANTI BATERAI + GANTI SSD 128GB',NULL,'ganti ssd 128gb dan ganti baterai','ganti ssd 128gb dan ganti baterai','normal','revisi','2025-10-28 00:00:00',NULL,461000.00,461000.00,NULL,NULL,'2025-11-05 22:43:12','2025-11-16 21:16:45'),(13,13,'laptop dell chrombook 11','mati total',NULL,NULL,NULL,'normal','analisa','2025-11-06 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-06 01:34:34','2025-11-06 02:23:39'),(14,14,'hp','Mesin Nyala , Tampilan Mati',NULL,NULL,NULL,'normal','analisa','2025-11-05 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-06 02:16:39','2025-11-06 02:27:59'),(15,4,'DELL LATITUDE 2100','matot',NULL,NULL,NULL,'normal','analisa','2025-11-06 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-06 22:42:24','2025-11-06 22:51:01'),(16,13,'laptop hp 14 ac004tx','instal ulang',NULL,'ganti ssd 128gb','instal ulang dan ganti ssd 128gb','normal','selesai','2025-11-06 00:00:00',NULL,100000.00,100000.00,NULL,NULL,'2025-11-06 22:59:23','2025-11-16 18:36:21'),(19,17,'BROTHER/MFC-J3520','-',NULL,'-',NULL,'normal','analisa','2025-11-10 00:00:00',NULL,NULL,NULL,NULL,'-','2025-11-09 18:30:30','2025-11-09 18:32:40'),(20,4,'TOSHIBA','MATOT',NULL,'-',NULL,'normal','analisa','2025-11-10 00:00:00',NULL,NULL,NULL,NULL,'-','2025-11-10 01:57:09','2025-11-10 02:03:30'),(21,17,'EPSON/L 14150','-kalo scan ada garisnya \n-gak bisa scant print',NULL,NULL,NULL,'normal','analisa','2025-11-10 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-10 18:17:15','2025-11-10 18:21:10'),(22,4,'AXUS X201E','laptop bunyi ketika di nyalakan',NULL,'ganti pasta & bersihin kipas/van','pasta kering\n','normal','dibayar','2025-11-10 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-10 18:19:53','2025-11-17 00:53:15'),(25,23,'notebook hp','windows nya gak tampil',NULL,'install ulang dan ganti baterai cmos','tidak bisa masuk windows dan baterai cmos lemah','normal','dibayar','2025-11-15 00:00:00',NULL,150000.00,150000.00,NULL,NULL,'2025-11-14 19:35:02','2025-11-16 21:16:12'),(26,23,'ausus notebook x200m','tidak bisa login windows','-','instal ulang windows',NULL,'normal','analisa','2025-11-15 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-14 19:48:18','2025-11-14 19:49:03'),(27,24,'DELL PRECISION T1500','pcnya mati',NULL,'ganti motherboard','PC mati','normal','konfirmasi','2025-11-17 00:00:00',NULL,100000.00,100000.00,NULL,NULL,'2025-11-16 20:15:12','2025-11-19 20:58:08'),(28,25,'axioo p401/my book 14','instal ulang',NULL,NULL,NULL,'normal','analisa','2025-11-17 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-17 01:29:21','2025-11-17 01:32:14'),(31,15,'ASUS GL553V','lemot, speaker rusak, keyboard lepas, instal sofware(nitro dll)',NULL,'ganti speaker ,instal ulang, pasang keyboard','lemot, speaker rusak, keyboard lepas, instal sofware(nitro dll)','normal','konfirmasi','2025-11-08 00:00:00',NULL,0.00,164000.00,NULL,NULL,'2025-11-17 20:06:27','2025-11-19 01:45:35'),(32,26,'Laptop Dell E5440','Matot','Charger',NULL,NULL,'normal','belum mulai','2025-11-18 00:00:00',NULL,NULL,NULL,NULL,NULL,'2025-11-17 23:29:59','2025-11-17 23:29:59'),(33,22,'asus x441b','\nganti speaker, instal sofware,pasang keyboard',NULL,'ganti baterai dan keyboard','ganti baterai dan keyboard','normal','dalam proses','2025-11-14 00:00:00',NULL,0.00,405500.00,NULL,NULL,'2025-11-18 19:43:45','2025-11-18 19:47:17');
/*!40000 ALTER TABLE `pesanans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` text COLLATE utf8mb4_unicode_ci,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5TlTRIAaSvUkRLDAU64uD49AGko6UxVvkyHEBemc',1,'103.19.78.14','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiWlU2QzVadDFMWWNyWlUwMWt3RllGdXoxTzJQZlZ4UDlDRFFic3BsTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9wd3Njb21wLnB0cHdzLmlkL2FkbWluL3NwYXJlcGFydHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJDdQTzZHZ2dkTlB1eUZCLlhCanFEcmUvNzJPS0NOaTQ3TGY2cDl2dEdrSHVTQTVoTkRaSjVDIjtzOjY6InRhYmxlcyI7YToyOntzOjQwOiJlZmVjMTE5MGUxZDEzYTE4MTU2OTJmNzZkOGFjNTc0NV9jb2x1bW5zIjthOjY6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJ1c2VyLm5hbWUiO3M6NToibGFiZWwiO3M6ODoiQ3VzdG9tZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJkZXZpY2VfdHlwZSI7czo1OiJsYWJlbCI7czo5OiJQZXJhbmdrYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6InByaW9yaXR5IjtzOjU6ImxhYmVsIjtzOjg6IlByaW9yaXR5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoic3RhcnRfZGF0ZSI7czo1OiJsYWJlbCI7czoxMDoiU3RhcnQgZGF0ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InRvdGFsX2Nvc3QiO3M6NToibGFiZWwiO3M6MTE6IlRvdGFsIEJpYXlhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiI0MjNiZjdlMDc3MWU1ZjRlY2IyMTk3NWQ2MDkzZTFhN19jb2x1bW5zIjthOjg6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjQ6Ik5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjM6InNrdSI7czo1OiJsYWJlbCI7czozOiJTS1UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6InF1YW50aXR5IjtzOjU6ImxhYmVsIjtzOjg6IlF1YW50aXR5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJtaW5fc3RvY2siO3M6NToibGFiZWwiO3M6OToiTWluIHN0b2NrIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJwcmljZSI7czo1OiJsYWJlbCI7czo1OiJQcmljZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6ODoibG9jYXRpb24iO3M6NToibGFiZWwiO3M6ODoiTG9jYXRpb24iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fX19',1763608443),('ammmGD3rX22ZfgdDmCRpK4OKP9me8CAZFqCkkxaC',NULL,'3.89.200.198','Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWUpCMUxCaGdJUndNM0JWaDlnaE5BTWtJZVRla0J6dWxlNU1ndlJYUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NzoiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vc3BhcmVwYXJ0cy84L2VkaXQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNToiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1763611848),('EKa2Lu36XBenmPmjElh3kDG1MtCkaG6sQwgMR0zu',NULL,'3.81.124.225','Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUVhTQlJoWktJSEIxWEpjWFg2VUxFSTF0N0R6S0lYVTFmT29SM3FXNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MToiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vcGVzYW5hbnMvMjciO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNToiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1763610816),('Fl7WEWpmf1H1SjNJIBMYKANZQB4NeMtYaRZUFdk2',NULL,'54.80.235.18','Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRmxUcmZ3YnBwdmtYNGlRSFJzMlAxS3ljT0V2QVJ3VFNNVWlCNXczdCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MToiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vcGVzYW5hbnMvMjciO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNToiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1763609317),('fQVlgdLSuIFjimaVDSiWM7bQZZ1TstZtib1hnDzK',NULL,'54.204.238.58','Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM3hRazN3a1J3dUlzcFZMOEpTanQ1R0FqVW1FZHM1a3J0TTU2U0RoaiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vcGVzYW5hbnMvOCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vcHdzY29tcC5wdHB3cy5pZC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763611752),('iXg9l91pImcReIA9U4p9sklQVOxHe5fij6i8H62F',NULL,'13.217.150.208','Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVmJTcldvR2FiTm5yY3psR3Y4QTRFZ3AyUW9HVnBXQjh4QTlCSE53eSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vc3BhcmVwYXJ0cy8xMi9lZGl0Ijt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9wd3Njb21wLnB0cHdzLmlkL2FkbWluL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763611085),('JGV82axwXv1MUhPO7Slvb5Zabgl2XsWkokaD9tE0',1,'103.19.78.14','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiUlJ0bjFVWTRubGNZZVd6allLa1VaWkdVZ1pzOTNubWJXSFNpSDRVcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9wd3Njb21wLnB0cHdzLmlkL2FkbWluL3Blc2FuYW5zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJDdQTzZHZ2dkTlB1eUZCLlhCanFEcmUvNzJPS0NOaTQ3TGY2cDl2dEdrSHVTQTVoTkRaSjVDIjtzOjY6InRhYmxlcyI7YTo0OntzOjQwOiJlZmVjMTE5MGUxZDEzYTE4MTU2OTJmNzZkOGFjNTc0NV9jb2x1bW5zIjthOjY6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJ1c2VyLm5hbWUiO3M6NToibGFiZWwiO3M6ODoiQ3VzdG9tZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJkZXZpY2VfdHlwZSI7czo1OiJsYWJlbCI7czo5OiJQZXJhbmdrYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6InByaW9yaXR5IjtzOjU6ImxhYmVsIjtzOjg6IlByaW9yaXR5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoic3RhcnRfZGF0ZSI7czo1OiJsYWJlbCI7czoxMDoiU3RhcnQgZGF0ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InRvdGFsX2Nvc3QiO3M6NToibGFiZWwiO3M6MTE6IlRvdGFsIEJpYXlhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiJiNzFkYjAxZGQ1NWRhNGRmMGU3ZDYxY2I1NzZmMDM4YV9jb2x1bW5zIjthOjU6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxOToiVGFuZ2dhbCAmYW1wOyBXYWt0dSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6Im9sZF9zdGF0dXMiO3M6NToibGFiZWwiO3M6MTE6IlN0YXR1cyBMYW1hIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoibmV3X3N0YXR1cyI7czo1OiJsYWJlbCI7czoxMToiU3RhdHVzIEJhcnUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6InVzZXIubmFtZSI7czo1OiJsYWJlbCI7czoxMToiRGl1YmFoIE9sZWgiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6Im5vdGVzIjtzOjU6ImxhYmVsIjtzOjc6IkNhdGF0YW4iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fXM6NDE6ImVmZWMxMTkwZTFkMTNhMTgxNTY5MmY3NmQ4YWM1NzQ1X3Blcl9wYWdlIjtzOjI6IjUwIjtzOjQwOiI0MjNiZjdlMDc3MWU1ZjRlY2IyMTk3NWQ2MDkzZTFhN19jb2x1bW5zIjthOjg6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjQ6Ik5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjM6InNrdSI7czo1OiJsYWJlbCI7czozOiJTS1UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6InF1YW50aXR5IjtzOjU6ImxhYmVsIjtzOjg6IlF1YW50aXR5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJtaW5fc3RvY2siO3M6NToibGFiZWwiO3M6OToiTWluIHN0b2NrIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJwcmljZSI7czo1OiJsYWJlbCI7czo1OiJQcmljZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6ODoibG9jYXRpb24iO3M6NToibGFiZWwiO3M6ODoiTG9jYXRpb24iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fX1zOjg6ImZpbGFtZW50IjthOjA6e319',1763611931),('JIcgOMzfDJ6qq3tq4IxZBRmiESQ0JhhfDKPmbQ6T',1,'103.19.78.14','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiQUs5R1N2N21LaFJEZndtVlJwQnhNN0J1SHNTSDBSOWVPdmNUSmVUcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly9wd3Njb21wLnB0cHdzLmlkL2FkbWluL3NwYXJlcGFydC1wdXJjaGFzZS1vcmRlcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJDdQTzZHZ2dkTlB1eUZCLlhCanFEcmUvNzJPS0NOaTQ3TGY2cDl2dEdrSHVTQTVoTkRaSjVDIjtzOjY6InRhYmxlcyI7YTo2OntzOjQwOiJlZmVjMTE5MGUxZDEzYTE4MTU2OTJmNzZkOGFjNTc0NV9jb2x1bW5zIjthOjY6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJ1c2VyLm5hbWUiO3M6NToibGFiZWwiO3M6ODoiQ3VzdG9tZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJkZXZpY2VfdHlwZSI7czo1OiJsYWJlbCI7czo5OiJQZXJhbmdrYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6InByaW9yaXR5IjtzOjU6ImxhYmVsIjtzOjg6IlByaW9yaXR5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoic3RhcnRfZGF0ZSI7czo1OiJsYWJlbCI7czoxMDoiU3RhcnQgZGF0ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InRvdGFsX2Nvc3QiO3M6NToibGFiZWwiO3M6MTE6IlRvdGFsIEJpYXlhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiJkZjk4MGViOTI4YWY0MzFlZGYyNjVhNGI2MTc2Y2Q5ZV9jb2x1bW5zIjthOjExOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToicG9fbnVtYmVyIjtzOjU6ImxhYmVsIjtzOjY6Ik5vLiBQTyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6Im9yZGVyX2RhdGUiO3M6NToibGFiZWwiO3M6OToiVGdsIE9yZGVyIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNDoic3BhcmVwYXJ0X25hbWUiO3M6NToibGFiZWwiO3M6OToiU3BhcmVwYXJ0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJxdWFudGl0eSI7czo1OiJsYWJlbCI7czo2OiJKdW1sYWgiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ0b3RhbF9jb3N0IjtzOjU6ImxhYmVsIjtzOjU6IlRvdGFsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJzdXBwbGllciI7czo1OiJsYWJlbCI7czo4OiJTdXBwbGllciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNDoicGF5bWVudF9tZXRob2QiO3M6NToibGFiZWwiO3M6MTA6IlBlbWJheWFyYW4iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjowO31pOjc7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTc6ImVzdGltYXRlZF9hcnJpdmFsIjtzOjU6ImxhYmVsIjtzOjk6IkVzdC4gVGliYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6ODthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6OTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoicmVjZWl2ZWRfZGF0ZSI7czo1OiJsYWJlbCI7czoxMDoiVGdsIFRlcmltYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6MTA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6NjoiRGlidWF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6IjQyM2JmN2UwNzcxZTVmNGVjYjIxOTc1ZDYwOTNlMWE3X2NvbHVtbnMiO2E6ODp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Mzoic2t1IjtzOjU6ImxhYmVsIjtzOjM6IlNLVSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6ODoicXVhbnRpdHkiO3M6NToibGFiZWwiO3M6ODoiUXVhbnRpdHkiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6Im1pbl9zdG9jayI7czo1OiJsYWJlbCI7czo5OiJNaW4gc3RvY2siO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6InByaWNlIjtzOjU6ImxhYmVsIjtzOjU6IlByaWNlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJsb2NhdGlvbiI7czo1OiJsYWJlbCI7czo4OiJMb2NhdGlvbiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjc7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiMTY0NmVlZjU1NDhkNTIyMGUzNzA4OGM5MGI3MjlkZDRfY29sdW1ucyI7YTo4OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NzoidGFuZ2dhbCI7czo1OiJsYWJlbCI7czo3OiJUYW5nZ2FsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJ0aXBlIjtzOjU6ImxhYmVsIjtzOjQ6IlRpcGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6ImthdGVnb3JpIjtzOjU6ImxhYmVsIjtzOjg6IkthdGVnb3JpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo3OiJub21pbmFsIjtzOjU6ImxhYmVsIjtzOjc6Ik5vbWluYWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE3OiJtZXRvZGVfcGVtYmF5YXJhbiI7czo1OiJsYWJlbCI7czoxNzoiTWV0b2RlIHBlbWJheWFyYW4iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6InJlZmVyZW5zaSI7czo1OiJsYWJlbCI7czo5OiJSZWZlcmVuc2kiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6ImU2NDQ4MzNmNGU0ZTA4NzEyMzE1ZGE3MWIzM2ZhY2QyX2NvbHVtbnMiO2E6NDp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToiZW1haWwiO3M6NToibGFiZWwiO3M6MTM6IkVtYWlsIGFkZHJlc3MiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6InBob25lIjtzOjU6ImxhYmVsIjtzOjU6IlBob25lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJyb2xlIjtzOjU6ImxhYmVsIjtzOjQ6IlJvbGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fXM6NDA6ImQyZTBlMTZiNWU1NzE4YjNjNzJkMmUwZWY3ZTgzNTgyX2NvbHVtbnMiO2E6ODp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6InBvX251bWJlciI7czo1OiJsYWJlbCI7czo2OiJOby4gUE8iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJvcmRlcl9kYXRlIjtzOjU6ImxhYmVsIjtzOjk6IlRnbCBPcmRlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6ODoicXVhbnRpdHkiO3M6NToibGFiZWwiO3M6NjoiSnVtbGFoIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY29zdF9wcmljZSI7czo1OiJsYWJlbCI7czoxMToiSGFyZ2EgTW9kYWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ0b3RhbF9jb3N0IjtzOjU6ImxhYmVsIjtzOjU6IlRvdGFsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJzdXBwbGllciI7czo1OiJsYWJlbCI7czo4OiJTdXBwbGllciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Njoic3RhdHVzIjtzOjU6ImxhYmVsIjtzOjY6IlN0YXR1cyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjc7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6InJlY2VpdmVkX2RhdGUiO3M6NToibGFiZWwiO3M6MTA6IlRnbCBUZXJpbWEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fX1zOjg6ImZpbGFtZW50IjthOjA6e319',1763621821),('qN0TT4NUxB7ETp2bRmhOSlqVRAlLdTCGgLcbN4xR',NULL,'3.91.12.232','Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR1UwNkZGd3FFMGpUQzd6RXA4Rm9PdmkxMkpZTjZIRFNHVk91ZXVCZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vcGVzYW5hbnMvOCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vcHdzY29tcC5wdHB3cy5pZC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763610633),('qRICwMGAX5g1LFO9ESs0R7K0TRdqQeVvne6l9Ynf',NULL,'34.201.160.47','Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzZLNUZGazZJNFp4MEVuQXE2TGpZVTczd3YwZTVqTGY3Um1WU3hMdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9wd3Njb21wLnB0cHdzLmlkL2FkbWluL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1763608169),('zzFv7enCJJlUNNsoHZ2tuztnm02enBulnmMKHtWK',NULL,'52.200.186.135','Mozilla/5.0 (compatible; proximic; +https://www.comscore.com/Web-Crawler)','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZEZZOWlubU1oS1ZFd3JHVGFSc2x4MGVNdWQwd1p2Z0NRQ2lrTHQ0cSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovL3B3c2NvbXAucHRwd3MuaWQvYWRtaW4vc3BhcmVwYXJ0cyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vcHdzY29tcC5wdHB3cy5pZC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1763611530);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sparepart_purchases`
--

DROP TABLE IF EXISTS `sparepart_purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sparepart_purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sparepart_purchases`
--

LOCK TABLES `sparepart_purchases` WRITE;
/*!40000 ALTER TABLE `sparepart_purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `sparepart_purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spareparts`
--

DROP TABLE IF EXISTS `spareparts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spareparts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL DEFAULT '0',
  `min_stock` int NOT NULL DEFAULT '0',
  `price` decimal(14,2) NOT NULL DEFAULT '0.00',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spareparts_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spareparts`
--

LOCK TABLES `spareparts` WRITE;
/*!40000 ALTER TABLE `spareparts` DISABLE KEYS */;
INSERT INTO `spareparts` VALUES (2,'SSD Venom RX 128GB','SP-SSD-VE-001','storage',0,0,250000.00,'Pws Comp','2025-11-17 01:40:31','2025-11-17 01:44:26'),(3,'keyboard  laptop hp pavilion','SP-KEYBOA-003',NULL,1,0,91300.00,'pws comp','2025-11-17 20:40:58','2025-11-17 20:45:00'),(5,'intel core i7 ','SP-INTEL--005',NULL,1,0,165760.00,'pws comp','2025-11-17 20:43:41','2025-11-17 20:44:34'),(7,'baterai redmi 8','SP-BATERA-007',NULL,1,0,138000.00,'pws comp','2025-11-17 20:49:29','2025-11-17 22:42:32'),(8,'kabel flexible','SP-KABEL--008',NULL,1,0,128500.00,'pws comp','2025-11-17 21:10:42','2025-11-19 21:08:38'),(9,'keyboard laptop asus x441m','SP-KEYBOA-009',NULL,0,0,140500.00,'pws comp','2025-11-18 19:37:36','2025-11-18 19:46:21'),(10,'baterai asus ','SP-BATERA-010',NULL,0,0,265000.00,'pws comp','2025-11-18 19:38:11','2025-11-18 19:46:22'),(11,'speaker asus gl','SP-SPEAKE-011',NULL,0,0,164000.00,'pws comp','2025-11-18 19:54:28','2025-11-18 20:14:44'),(12,'matherboard','SP-MATHER-012',NULL,1,0,504000.00,'pws comp','2025-11-19 20:56:08','2025-11-19 20:56:08');
/*!40000 ALTER TABLE `spareparts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `tipe` enum('pemasukan','pengeluaran') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('pemasukan','pengeluaran sparepart','pengeluaran operasional','marketing','sodaqoh','alat bahan','gaji karyawan','pengeluaran wajib') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `nominal` decimal(12,2) NOT NULL,
  `metode_pembayaran` enum('cash','paylater','visa','mastercard','tokped visa','gopay later','seabank','BCA','Mandiri') COLLATE utf8mb4_unicode_ci NOT NULL,
  `referensi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (2,'2025-11-08','pemasukan','pemasukan','Pembayaran Maliki Rembang',100000.00,'cash',NULL,'2025-11-07 22:52:44','2025-11-07 22:52:44'),(3,'2025-11-10','pemasukan','pemasukan','pembayaran laptop pak samsul',461000.00,'cash',NULL,'2025-11-10 18:08:39','2025-11-10 18:08:39'),(5,'2025-11-12','pemasukan','pemasukan','pembayaran (pt kim jing)',650000.00,'cash',NULL,'2025-11-11 23:37:27','2025-11-11 23:37:41'),(6,'2025-11-13','pengeluaran','pengeluaran operasional','SANTRI AMDK BOTOL 1500ML',5000.00,'cash',NULL,'2025-11-12 22:26:33','2025-11-12 22:26:33'),(7,'2025-11-14','pengeluaran','pengeluaran sparepart','IC power PT Kiji',550000.00,'cash',NULL,'2025-11-15 08:44:06','2025-11-15 08:44:06'),(8,'2025-11-16','pemasukan','pemasukan','service laptop hp farel',150000.00,'cash','service laptop','2025-11-15 22:20:13','2025-11-15 22:20:13'),(9,'2025-11-17','pemasukan','pemasukan','Pembayaran servis LENOVO G40-45 - PAK SAMSUL',461000.00,'cash','11','2025-11-16 21:14:21','2025-11-16 21:14:21'),(10,'2025-11-17','pemasukan','pemasukan','Pembayaran servis notebook hp - farel',150000.00,'cash','25','2025-11-16 21:16:12','2025-11-16 21:16:12'),(11,'2025-11-17','pengeluaran','pengeluaran operasional','teh pucuk harum botol 350m (3)',10500.00,'cash',NULL,'2025-11-17 00:53:54','2025-11-17 00:53:54'),(12,'2025-11-17','pengeluaran','pengeluaran operasional','akumudasi trasportasi',50000.00,'cash',NULL,'2025-11-17 00:56:44','2025-11-17 00:56:44'),(15,'2025-11-18','pemasukan','pemasukan','Pembayaran servis Lenovo Thinkpad L450 - Maliki',100000.00,'cash','5','2025-11-17 18:59:12','2025-11-17 18:59:12');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `address` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Utama','admin@admin.com','2025-10-31 21:31:20','$2y$12$7PO6GggdNPuyFB.XBjqDre/72OKCNi47Lf6p9vtGkHuSA5hNDZJ5C','082232469415','admin','Jl. Merdeka No. 123, Jakarta','Rv9M29AMdD','2025-10-31 21:31:20','2025-10-31 21:31:20'),(2,'Mukhammad Alfan Nurdin','alfanno8@gmail.com',NULL,'$2y$12$Tq1RgqlkRxPt/A5hYbX.hOSDvqMj.AhFjvLsvMM6J.diXoFB6ZIDK','082232469415','user','Rembang',NULL,'2025-11-01 03:27:14','2025-11-01 03:27:14'),(3,'PT King Jim','ptkingjim@ptkingjim.com',NULL,'$2y$12$zmUx1kY6jg97Y98fAHk.oORyc1cEzJsxcICXLUvxqLQmwWEsdypZa','08567891793','customer','Pier Pasuruan',NULL,'2025-11-05 00:56:32','2025-11-06 22:32:27'),(4,'smkn 2 pasuruan','smkn2pas@gmail.com',NULL,'$2y$12$D24Uxnf1EvTiXcK6Hf/3A.4UmoBm647a7wW4iKuV/Xv5Ul2t2j7WO','0822-3273-9959','customer','bugul kidul',NULL,'2025-11-05 01:17:22','2025-11-06 22:23:44'),(5,'Maliki','maliki@maliki.com',NULL,'$2y$12$6YdcD1kQyC24RxSIG.s5lueO1zsT5goTsMc6YncjLOJAUZ11Y18KC','085859971199','customer','Pajaran Rembang',NULL,'2025-11-05 01:35:16','2025-11-06 22:32:41'),(6,'ahmad saidi','saidi@saidi.com',NULL,'$2y$12$GmfQYb9EQG71hOc3QX/IvOWrolfGJzD1LXdHqosk2z.kQOAJIhzEW','081938515120','customer',NULL,NULL,'2025-11-05 02:03:53','2025-11-06 22:29:28'),(7,'febriyanti','febriyanti@gmail.com',NULL,'$2y$12$IUja0WohVHXZWVrDE7YTdemENoT0mAto.NLTiAy7fAbrgKGcQkwxC','085648172150','customer',NULL,NULL,'2025-11-05 02:11:21','2025-11-06 22:27:05'),(8,'gilang','gilang@gmail.com',NULL,'$2y$12$sk6q9jJILa4RyDIq6Tz2uOExvFL2TPgyGsYlq1b0qdeZl.OxdxrYK','085749515155','customer',NULL,NULL,'2025-11-05 22:30:27','2025-11-06 22:27:46'),(9,'Samsul   pepeng','samsulpepeng@gmail.com',NULL,'$2y$12$BlH2VPZg3jzgTCR0cbY9YuYWEX1F3fsceZG2K3vQbg2b0clNKS3/.','0852-3641-1161','customer',NULL,NULL,'2025-11-05 22:35:36','2025-11-06 22:30:21'),(10,'Novita','novita@novita.com',NULL,'$2y$12$v3KODhGzTPUupSTJKpd0hO0lNixRVmaBBxZRdfzwdYtPxwME02jp6','0852-3239-7986','customer',NULL,NULL,'2025-11-05 22:40:01','2025-11-06 22:30:49'),(11,'PAK SAMSUL','paksamsul@paksamsul.com',NULL,'$2y$12$1FVruE/6SrFO3EfAQ2QEj.Yeg8I4uKrK1Q0cAiv5B5Z0k5eEAvZsK','081357901292','customer',NULL,NULL,'2025-11-05 22:43:12','2025-11-06 22:32:54'),(13,'dimas alutfi','dimasalutfi@dimasalutfi.com',NULL,'$2y$12$b6DhbR4y0jIOibwNJPVXHeGznZsdEB0qz7ijZ8cO9qRqnmKkKxZge','0857-0661-9159','customer',NULL,NULL,'2025-11-06 01:34:34','2025-11-06 22:31:40'),(14,'elian','elian@gmail.com',NULL,'$2y$12$Nu.p/wMLr/oOu7S5L6f.PuTVf24Uz4OdrPFsPAgM21xpiOARwznZ2','085233035182','customer','Perum karya Bakti Pasuruan',NULL,'2025-11-06 02:16:39','2025-11-06 22:33:09'),(15,'bu mega pertanian','bumegapertanian@bumegapertanian.com',NULL,'$2y$12$2TqcNYytMQIBTHVZ8L.Z0.eUBJTgV5CCLZl12cRj2UFoyy8QGQD0y','081333660003','customer','dinas pertanian pasurauan',NULL,'2025-11-07 20:42:34','2025-11-07 20:50:06'),(17,'dinas ketahanan pangan dan pertanian','SIMBMD@gmail.com',NULL,'$2y$12$.TGYWkgJ4SXZ.VYW00X5neZjlV0VdIlldV52PRQNq1stgOLVSVT0O','-','user','-',NULL,'2025-11-09 18:30:30','2025-11-09 18:30:30'),(22,'bambang ','bambang@gmail.com',NULL,'$2y$12$3qIPbVkrmsnCVGJCxyCX6.wOx1jZTj9vPO2ejeN7GJcppTaqSwium','082140307578','user','puspo',NULL,'2025-11-14 00:38:36','2025-11-14 00:38:36'),(23,'farel','farel@gmail.com',NULL,'$2y$12$Z3zaks7vc6ay3mgUTI8AlucJXEmyHWEdzocPvhv4zcwnA/Xcgo2km','089516491138','user','tenggilis',NULL,'2025-11-14 19:35:02','2025-11-14 19:35:02'),(24,'pak imron','imron@gmail.com',NULL,'$2y$12$07T6gVDkClOGp8mJsxnO5OFsLtOWZQ.tTbVPBUvbg1b/qPdIWN9ga','081335068998','user','rebalas',NULL,'2025-11-16 20:15:12','2025-11-16 20:15:12'),(25,'davina','davina@gmail.com',NULL,'$2y$12$PpnZVJBBbjujig8nGH1eLuTwJ6KQwOwKzTb5UGOSWfWX10nT2MP2q','+6285706123944','customer','prigen',NULL,'2025-11-17 01:29:21','2025-11-17 01:31:23'),(26,'Arif','arif@arif.com',NULL,'$2y$12$gRvO3r0DCSXuoe2kT8zwEuR0XQIvYwJGjtDMFTnldFnnJD6VOEa7a','085856902863','user','Graha Candi Ad22 Pasuruan',NULL,'2025-11-17 23:29:59','2025-11-17 23:29:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-20 13:59:22
