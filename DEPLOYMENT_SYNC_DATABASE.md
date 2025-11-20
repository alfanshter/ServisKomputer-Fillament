# 🚀 Deployment Instructions - Sync Database Server

## Masalah yang Ditemukan:
1. ❌ Tabel `spareparts` tidak punya kolom: `cost_price`, `average_cost`, `margin_percent`
2. ❌ Tabel `sparepart_purchases` hampir kosong (hanya id, timestamps)
3. ❌ Tabel `sparepart_purchase_orders` tidak ada sama sekali

---

## ✅ Solusi - Jalankan Migration di Server

### **Step 1: Upload Code ke Server**
```bash
# Di lokal, commit semua perubahan
git add .
git commit -m "Add missing migrations for spareparts cost tracking"
git push origin main
```

### **Step 2: Pull di Server**
```bash
# SSH ke server
ssh user@your-server.com

# Masuk ke folder project
cd /path/to/project

# Pull latest code
git pull origin main
```

### **Step 3: Backup Database (PENTING!)**
```bash
# Backup dulu sebelum migrate
mysqldump -u username -p database_name > backup_before_migration_$(date +%Y%m%d_%H%M%S).sql
```

### **Step 4: Jalankan Migration**
```bash
# Install dependencies jika ada yang baru
composer install --no-dev --optimize-autoloader

# Jalankan migration (AMAN - hanya menambah kolom, tidak menghapus data)
php artisan migrate --force

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### **Step 5: Verify**
```bash
# Cek apakah migration berhasil
php artisan migrate:status

# Test create sparepart baru
# Akses aplikasi dan coba buat sparepart baru dengan harga modal
```

---

## 📋 Migration Files yang Ditambahkan:

### 1. **2025_11_20_080000_add_cost_columns_to_spareparts_table.php**
Menambah kolom di tabel `spareparts`:
- `cost_price` - Harga modal terakhir
- `average_cost` - Harga modal rata-rata
- `margin_percent` - Margin keuntungan (%)

### 2. **2025_11_20_080100_add_columns_to_sparepart_purchases_table.php**
Menambah kolom di tabel `sparepart_purchases`:
- `sparepart_id` - Foreign key ke tabel spareparts
- `quantity` - Jumlah pembelian
- `cost_price` - Harga modal per unit
- `total_cost` - Total biaya
- `purchase_date` - Tanggal pembelian
- `supplier` - Nama supplier
- `notes` - Catatan
- `margin_persen` - Margin
- `harga_jual` - Harga jual

### 3. **2025_11_19_100000_create_sparepart_purchase_orders_table.php** (sudah ada)
Membuat tabel `sparepart_purchase_orders` untuk sistem PO

### 4. **2025_11_19_100002_add_payment_method_to_sparepart_purchase_orders.php** (sudah ada)
Menambah kolom `payment_method` ke tabel PO

---

## ⚠️ Catatan Penting:

### **Migration ini AMAN karena:**
- ✅ Hanya **menambah** kolom baru (tidak menghapus)
- ✅ Kolom nullable/default value (tidak akan error untuk data existing)
- ✅ Ada pengecekan `Schema::hasColumn()` untuk menghindari duplikasi
- ✅ **Data existing tetap aman**

### **Setelah Migration:**
- Sparepart existing akan punya kolom baru dengan nilai NULL/0
- Bisa di-update manual atau otomatis saat terima PO baru
- Harga jual (price) tetap ada dan tidak berubah

---

## 🧪 Testing Checklist:

Setelah migration, test:
- [ ] Bisa buat sparepart baru dengan harga modal
- [ ] Bisa buat Purchase Order baru
- [ ] Bisa terima barang dari PO
- [ ] Sparepart stock bertambah
- [ ] Average cost ter-update
- [ ] Transaksi tercatat
- [ ] Sparepart existing masih bisa di-edit

---

## 🆘 Troubleshooting:

### Jika masih error "Column not found":
```bash
# Cek struktur tabel
php artisan db:table spareparts
php artisan db:table sparepart_purchases
php artisan db:table sparepart_purchase_orders

# Atau via MySQL
mysql -u username -p database_name
SHOW COLUMNS FROM spareparts;
SHOW COLUMNS FROM sparepart_purchases;
exit;
```

### Jika migration gagal:
```bash
# Rollback migration terakhir
php artisan migrate:rollback --step=1

# Cek error message
# Fix migration file jika perlu
# Jalankan lagi
php artisan migrate --force
```

---

## 📞 Contact:

Jika ada error saat deployment, kirim:
1. Error message lengkap
2. Output dari `php artisan migrate:status`
3. Output dari `SHOW COLUMNS FROM spareparts;`
