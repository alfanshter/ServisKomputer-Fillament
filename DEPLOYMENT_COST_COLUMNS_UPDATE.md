# Deployment: Update Kolom Cost di Tabel Spareparts & Sparepart Purchases

## Masalah
Error saat terima barang di Purchase Order:
```
1. Column not found: 1054 Unknown column 'cost_price' in 'field list' (spareparts)
2. Column not found: 1054 Unknown column 'sparepart_id' in 'field list' (sparepart_purchases)
```

## Solusi
Menambahkan kolom-kolom yang diperlukan:

### Tabel `spareparts`:
- `cost_price` - Harga modal terakhir
- `average_cost` - Harga modal rata-rata
- `margin_percent` - Margin keuntungan (%)

### Tabel `sparepart_purchases`:
- `sparepart_id` - Foreign key ke tabel spareparts
- `quantity` - Jumlah pembelian
- `cost_price` - Harga modal per unit
- `total_cost` - Total biaya pembelian
- `purchase_date` - Tanggal pembelian
- `supplier` - Nama supplier
- `notes` - Catatan pembelian
- `margin_persen` - Margin keuntungan (%)
- `harga_jual` - Harga jual yang dihitung

## Cara Deploy ke Server

### Step 1: Backup Database
```bash
mysqldump -u root -p database_name > backup_cost_columns_$(date +%Y%m%d).sql
```

### Step 2: Pull Code Terbaru
```bash
git pull origin main
```

### Step 3: Jalankan Migration (Berurutan!)
```bash
# Migration 1: Tambah kolom di tabel spareparts
php artisan migrate --path=database/migrations/2025_11_20_080000_add_cost_columns_to_spareparts_table.php --force

# Migration 2: Tambah kolom di tabel sparepart_purchases
php artisan migrate --path=database/migrations/2025_11_20_080100_add_columns_to_sparepart_purchases_table.php --force
```

### Step 4: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
```

## Verifikasi

1. Cek struktur tabel spareparts:
```sql
DESCRIBE spareparts;
```

2. Cek struktur tabel sparepart_purchases:
```sql
DESCRIBE sparepart_purchases;
```

3. Pastikan kolom-kolom di atas sudah ada

4. Test terima barang di Purchase Order

## Rollback (jika diperlukan)

```bash
php artisan migrate:rollback --path=database/migrations/2025_11_20_080100_add_columns_to_sparepart_purchases_table.php
php artisan migrate:rollback --path=database/migrations/2025_11_20_080000_add_cost_columns_to_spareparts_table.php
```

## SQL Manual (jika migration gagal)

### Untuk tabel spareparts:
```sql
ALTER TABLE spareparts 
ADD COLUMN cost_price DECIMAL(12,2) NULL COMMENT 'Harga modal terakhir' AFTER price,
ADD COLUMN average_cost DECIMAL(12,2) NULL COMMENT 'Harga modal rata-rata' AFTER cost_price,
ADD COLUMN margin_percent DECIMAL(5,2) NULL COMMENT 'Margin keuntungan (%)' AFTER average_cost;
```

### Untuk tabel sparepart_purchases:
```sql
ALTER TABLE sparepart_purchases
ADD COLUMN sparepart_id BIGINT UNSIGNED NULL AFTER id,
ADD COLUMN quantity INT DEFAULT 0,
ADD COLUMN cost_price DECIMAL(12,2) DEFAULT 0,
ADD COLUMN total_cost DECIMAL(12,2) DEFAULT 0,
ADD COLUMN purchase_date DATE NULL,
ADD COLUMN supplier VARCHAR(255) NULL,
ADD COLUMN notes TEXT NULL,
ADD COLUMN margin_persen DECIMAL(5,2) DEFAULT 0,
ADD COLUMN harga_jual DECIMAL(12,2) NULL,
ADD CONSTRAINT sparepart_purchases_sparepart_id_foreign 
    FOREIGN KEY (sparepart_id) REFERENCES spareparts(id) ON DELETE CASCADE;
```
