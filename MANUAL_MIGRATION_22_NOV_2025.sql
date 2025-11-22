-- =====================================================
-- SQL Migration Manual - Update 22 November 2025
-- Gunakan ini jika `php artisan migrate` error
-- =====================================================

-- =====================================================
-- 1. CREATE TABLE: services
-- =====================================================
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama jasa service',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kategori: Hardware Repair, Software, dll',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Deskripsi jasa',
  `price` decimal(12,2) NOT NULL COMMENT 'Harga standar',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status aktif/non-aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master data jasa service';

-- =====================================================
-- 2. CREATE TABLE: pesanan_service (Pivot)
-- =====================================================
CREATE TABLE IF NOT EXISTS `pesanan_service` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint unsigned NOT NULL COMMENT 'ID pesanan',
  `service_id` bigint unsigned NOT NULL COMMENT 'ID service dari master',
  `quantity` int NOT NULL DEFAULT '1' COMMENT 'Jumlah',
  `price` decimal(12,2) NOT NULL COMMENT 'Harga saat transaksi',
  `subtotal` decimal(12,2) NOT NULL COMMENT 'Quantity x Price',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_service_pesanan_id_foreign` (`pesanan_id`),
  KEY `pesanan_service_service_id_foreign` (`service_id`),
  CONSTRAINT `pesanan_service_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pivot table: pesanan <-> services';

-- =====================================================
-- 3. ALTER TABLE: pesanan_purchase_order_items
-- Make sparepart_id NULLABLE
-- =====================================================
ALTER TABLE `pesanan_purchase_order_items`
MODIFY COLUMN `sparepart_id` bigint unsigned NULL COMMENT 'ID sparepart (nullable untuk PO sparepart baru)';

-- =====================================================
-- 4. INSERT SAMPLE DATA: services (Optional)
-- =====================================================
INSERT INTO `services` (`name`, `category`, `description`, `price`, `is_active`) VALUES
('Install Windows 10/11', 'Software Installation', 'Install OS Windows lengkap dengan driver', 100000.00, 1),
('Install Microsoft Office', 'Software Installation', 'Install MS Office + aktivasi', 50000.00, 1),
('Ganti Keyboard Laptop', 'Hardware Repair', 'Pasang/ganti keyboard laptop semua merk', 150000.00, 1),
('Ganti LCD Laptop', 'Hardware Repair', 'Pasang/ganti LCD laptop semua merk', 200000.00, 1),
('Cleaning Laptop', 'Cleaning & Maintenance', 'Bersihkan debu, ganti thermal paste', 75000.00, 1),
('Upgrade RAM', 'Upgrade', 'Install/upgrade RAM laptop/PC', 50000.00, 1),
('Upgrade SSD', 'Upgrade', 'Install SSD + migrasi data', 100000.00, 1),
('Pasang/Ganti Headprint', 'Hardware Repair', 'Ganti headprint printer Epson/Canon', 125000.00, 1),
('Pasang/Ganti Roller', 'Hardware Repair', 'Ganti roller printer Epson/Canon', 100000.00, 1),
('Recovery Data', 'Data Recovery', 'Recovery data dari HDD/SSD rusak', 300000.00, 1),
('Konsultasi IT', 'Consultation', 'Konsultasi masalah IT & solusi', 50000.00, 1);

-- =====================================================
-- 5. UPDATE EXISTING DATA (Optional)
-- Recalculate total_cost untuk data yang sudah ada
-- =====================================================
-- SKIP jika data production masih sedikit
-- Jalankan manual via tinker jika perlu:
-- php artisan tinker
-- >>> \App\Models\Pesanan::chunk(100, function($pesanans) { ... });

-- =====================================================
-- 6. VERIFY TABLES
-- =====================================================
-- Check if tables created successfully
SELECT COUNT(*) as services_count FROM services;
SELECT COUNT(*) as pesanan_service_count FROM pesanan_service;
DESCRIBE pesanan_purchase_order_items; -- Check sparepart_id is NULL

-- =====================================================
-- 7. INSERT INTO MIGRATIONS TABLE
-- (Agar Laravel tahu migration sudah jalan)
-- =====================================================
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2025_11_22_042429_create_services_table', 999),
('2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items', 999);

-- =====================================================
-- ROLLBACK SCRIPT (Jika perlu undo)
-- =====================================================
/*
-- Hapus tables
DROP TABLE IF EXISTS `pesanan_service`;
DROP TABLE IF EXISTS `services`;

-- Kembalikan sparepart_id ke NOT NULL
ALTER TABLE `pesanan_purchase_order_items`
MODIFY COLUMN `sparepart_id` bigint unsigned NOT NULL;

-- Hapus dari migrations table
DELETE FROM `migrations` WHERE `migration` IN (
    '2025_11_22_042429_create_services_table',
    '2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items'
);
*/

-- =====================================================
-- DONE! ✅
-- =====================================================
-- Setelah run script ini, lanjut:
-- 1. Clear cache: php artisan optimize:clear
-- 2. Test fitur Master Jasa di admin panel
-- 3. Test Analisa Selesai dengan jasa & sparepart
-- =====================================================
