# Deployment: Update Kolom Cost di Tabel Spareparts

## Masalah
Error saat terima barang di Purchase Order:
```
Column not found: 1054 Unknown column 'cost_price' in 'field list'
```

## Solusi
Menambahkan kolom-kolom yang diperlukan ke tabel `spareparts`:
- `cost_price` - Harga modal terakhir
- `average_cost` - Harga modal rata-rata
- `margin_percent` - Margin keuntungan (%)

## Cara Deploy ke Server

### Step 1: Backup Database
```bash
mysqldump -u root -p database_name > backup_cost_columns_$(date +%Y%m%d).sql
```

### Step 2: Pull Code Terbaru
```bash
git pull origin main
```

### Step 3: Jalankan Migration
```bash
php artisan migrate --path=database/migrations/2025_11_20_080000_add_cost_columns_to_spareparts_table.php --force
```

### Step 4: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
```

## Verifikasi

1. Cek struktur tabel:
```sql
DESCRIBE spareparts;
```

2. Pastikan kolom berikut ada:
   - cost_price (decimal 12,2)
   - average_cost (decimal 12,2)
   - margin_percent (decimal 5,2)

3. Test terima barang di Purchase Order

## Rollback (jika diperlukan)

```bash
php artisan migrate:rollback --path=database/migrations/2025_11_20_080000_add_cost_columns_to_spareparts_table.php
```

## SQL Manual (jika migration gagal)

```sql
ALTER TABLE spareparts 
ADD COLUMN cost_price DECIMAL(12,2) NULL COMMENT 'Harga modal terakhir' AFTER price,
ADD COLUMN average_cost DECIMAL(12,2) NULL COMMENT 'Harga modal rata-rata' AFTER cost_price,
ADD COLUMN margin_percent DECIMAL(5,2) NULL COMMENT 'Margin keuntungan (%)' AFTER average_cost;
```
