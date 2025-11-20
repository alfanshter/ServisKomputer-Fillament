# Deployment: Update Fitur Diskon

## Perubahan
- Menambahkan kolom `discount` ke tabel `pesanans`
- Fitur diskon bisa diinput saat analisa
- Total cost otomatis menghitung dengan diskon: `(Service + Sparepart) - Diskon`
- Template WhatsApp menampilkan detail diskon

## Cara Deploy ke Server

### Opsi 1: Manual Step by Step (RECOMMENDED)

```bash
# 1. SSH ke server
ssh user@your-server

# 2. Masuk ke direktori project
cd /path/to/your/project

# 3. Backup database dulu (penting!)
php artisan backup:run
# atau manual:
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# 4. Pull code terbaru
git pull origin main

# 5. Jalankan HANYA migration discount
php artisan migrate --path=database/migrations/2025_11_20_144457_add_discount_to_pesanans_table.php --force

# 6. Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 7. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Opsi 2: Menggunakan Script Deployment

```bash
# Di server, jalankan:
./deploy-server.sh
```

## Troubleshooting

### Jika ada error migration "table already exists"

Migration lain mungkin konflik. Jalankan hanya migration discount:

```bash
php artisan migrate --path=database/migrations/2025_11_20_144457_add_discount_to_pesanans_table.php --force
```

### Jika migration sudah pernah dijalankan

```bash
# Cek status migration
php artisan migrate:status

# Jika sudah ada, skip saja
```

### Rollback jika ada masalah

```bash
# Rollback hanya migration discount
php artisan migrate:rollback --path=database/migrations/2025_11_20_144457_add_discount_to_pesanans_table.php
```

## Verifikasi Setelah Deploy

1. Login ke aplikasi
2. Buat pesanan baru
3. Saat analisa, pastikan ada field "Diskon"
4. Isi diskon (misal: 50000)
5. Cek template WhatsApp, pastikan diskon muncul
6. Cek total cost otomatis dikurangi diskon

## Files yang Berubah

- `database/migrations/2025_11_20_144457_add_discount_to_pesanans_table.php` (NEW)
- `app/Models/Pesanan.php` - tambah 'discount' di fillable
- `app/Filament/Resources/Pesanans/Tables/PesanansTable.php` - tambah field discount & update template WA
- `app/Filament/Resources/Pesanans/Schemas/PesananForm.php` - tambah field discount di form edit
- `app/Filament/Resources/Pesanans/Pages/EditPesanan.php` - update perhitungan total_cost

## Struktur Database

```sql
ALTER TABLE pesanans 
ADD COLUMN discount DECIMAL(15,2) NULL AFTER service_cost;
```

## Formula Perhitungan

```
Subtotal = Service Cost + Total Sparepart
Total Cost = Subtotal - Discount
```
