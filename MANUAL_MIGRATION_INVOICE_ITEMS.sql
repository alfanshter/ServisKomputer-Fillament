-- =====================================================
-- DEPLOYMENT: Invoice Items - Immutable Snapshot
-- Tanggal: 5 Desember 2025
-- Tujuan: Membuat tabel invoice items untuk data historis
-- =====================================================

-- 1. Create Table pesanan_invoice_items
CREATE TABLE IF NOT EXISTS `pesanan_invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` varchar(255) NOT NULL COMMENT 'service atau sparepart',
  `item_name` varchar(255) NOT NULL COMMENT 'Nama jasa/sparepart (snapshot)',
  `item_description` varchar(255) DEFAULT NULL COMMENT 'SKU/Kategori',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(12,2) NOT NULL COMMENT 'Harga satuan (snapshot)',
  `subtotal` decimal(12,2) NOT NULL COMMENT 'Quantity × Price',
  `source` varchar(255) DEFAULT NULL COMMENT 'stock, po, manual',
  `source_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID sumber (tidak strict FK)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_invoice_items_pesanan_id_index` (`pesanan_id`),
  KEY `pesanan_invoice_items_item_type_index` (`item_type`),
  CONSTRAINT `pesanan_invoice_items_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Migrate Data Lama ke Invoice Items
-- Jalankan untuk pesanan yang sudah selesai analisa tapi belum punya invoice items

-- Migrate Services
INSERT INTO pesanan_invoice_items (
    pesanan_id,
    item_type,
    item_name,
    item_description,
    quantity,
    price,
    subtotal,
    source,
    source_id,
    created_at,
    updated_at
)
SELECT
    ps.pesanan_id,
    'service' as item_type,
    s.name as item_name,
    s.category as item_description,
    ps.quantity,
    ps.price,
    ps.subtotal,
    'master' as source,
    ps.service_id as source_id,
    NOW() as created_at,
    NOW() as updated_at
FROM pesanan_service ps
INNER JOIN services s ON ps.service_id = s.id
INNER JOIN pesanans p ON ps.pesanan_id = p.id
WHERE p.status IN ('selesai_analisa', 'konfirmasi', 'dalam proses', 'menunggu sparepart', 'on hold', 'revisi', 'selesai', 'dibayar')
AND NOT EXISTS (
    SELECT 1 FROM pesanan_invoice_items pii
    WHERE pii.pesanan_id = ps.pesanan_id
    AND pii.item_type = 'service'
    AND pii.source_id = ps.service_id
);

-- Migrate Spareparts
INSERT INTO pesanan_invoice_items (
    pesanan_id,
    item_type,
    item_name,
    item_description,
    quantity,
    price,
    subtotal,
    source,
    source_id,
    created_at,
    updated_at
)
SELECT
    ps.pesanan_id,
    'sparepart' as item_type,
    sp.name as item_name,
    sp.sku as item_description,
    ps.quantity,
    ps.price,
    ps.subtotal,
    'stock' as source,
    ps.sparepart_id as source_id,
    NOW() as created_at,
    NOW() as updated_at
FROM pesanan_sparepart ps
INNER JOIN spareparts sp ON ps.sparepart_id = sp.id
INNER JOIN pesanans p ON ps.pesanan_id = p.id
WHERE p.status IN ('selesai_analisa', 'konfirmasi', 'dalam proses', 'menunggu sparepart', 'on hold', 'revisi', 'selesai', 'dibayar')
AND NOT EXISTS (
    SELECT 1 FROM pesanan_invoice_items pii
    WHERE pii.pesanan_id = ps.pesanan_id
    AND pii.item_type = 'sparepart'
    AND pii.source_id = ps.sparepart_id
);

-- 3. Verify Data
SELECT
    COUNT(*) as total_invoice_items,
    SUM(CASE WHEN item_type = 'service' THEN 1 ELSE 0 END) as total_services,
    SUM(CASE WHEN item_type = 'sparepart' THEN 1 ELSE 0 END) as total_spareparts
FROM pesanan_invoice_items;

-- 4. Check Sample Data
SELECT
    p.id as pesanan_id,
    p.status,
    pii.item_type,
    pii.item_name,
    pii.quantity,
    pii.price,
    pii.subtotal,
    pii.source,
    pii.created_at
FROM pesanans p
INNER JOIN pesanan_invoice_items pii ON p.id = pii.pesanan_id
ORDER BY p.id DESC, pii.item_type
LIMIT 20;

-- =====================================================
-- ROLLBACK (Jika Diperlukan)
-- =====================================================

-- Hapus tabel invoice items
-- DROP TABLE IF EXISTS pesanan_invoice_items;

-- =====================================================
-- NOTES:
-- =====================================================
-- 1. Tabel lama (pesanan_sparepart, pesanan_service) TIDAK DIHAPUS
-- 2. Invoice sekarang menggunakan pesanan_invoice_items
-- 3. Edit sparepart tidak akan pengaruhi invoice
-- 4. Data historis terjaga dengan baik
-- =====================================================
