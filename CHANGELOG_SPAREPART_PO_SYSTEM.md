# 📦 Update Log - Sistem Purchase Order Sparepart & Dashboard

**Tanggal:** 19 November 2025  
**Versi:** v2.0.0  
**Developer:** Alfan

---

## 🎯 Ringkasan Update

Update besar-besaran untuk manajemen sparepart dengan sistem Purchase Order (PO) yang lengkap, tracking pembelian, integrasi transaksi otomatis, dan dashboard statistik yang lebih informatif.

---

## ✨ Fitur Baru

### 1. **Sistem Purchase Order (PO) Sparepart**
- Order sparepart online dengan tracking status
- Support sparepart baru dan existing
- Auto-generate nomor PO (PO-2025-001)
- Status: Pending → Shipped → Received → Cancelled
- Tombol "Terima Barang" untuk proses penerimaan

### 2. **Riwayat Pembelian Sparepart**
- History lengkap semua pembelian sparepart
- Kalkulasi harga rata-rata (average cost)
- Margin otomatis untuk harga jual
- Supplier tracking

### 3. **Integrasi Transaksi Otomatis**
- Pembelian sparepart otomatis tercatat sebagai pengeluaran
- Pilihan metode pembayaran per PO
- Link ke nomor PO untuk referensi

### 4. **Dashboard Statistik Lengkap**
- Status pesanan detail (Belum Mulai, Analisa, Dalam Proses, Selesai, Dibayar)
- Statistik keuangan (Pendapatan, Pengeluaran, Laba Bersih)
- Alert stok sparepart menipis
- Tracking PO pending

### 5. **Relation Manager di Sparepart**
- Tab "Riwayat Purchase Orders"
- Tab "Riwayat Pembelian"
- Summary total pembelian

---

## 🗄️ Database Changes

### **Migration Files (Perlu dijalankan):**

```bash
# Migration 1: Tabel sparepart_purchase_orders
database/migrations/2025_11_19_100000_create_sparepart_purchase_orders_table.php

# Migration 2: Tambah kolom margin & harga jual
database/migrations/2025_11_16_100001_add_margin_to_sparepart_purchases_table.php

# Migration 3: Tambah kolom payment_method
database/migrations/2025_11_19_100002_add_payment_method_to_sparepart_purchase_orders.php
```

### **Tabel Baru:**

#### 1. `sparepart_purchase_orders`
```sql
- id (bigint)
- po_number (string) UNIQUE - Auto-generate
- sparepart_id (bigint nullable) - Null jika sparepart baru
- sparepart_name (string)
- sku (string nullable)
- description (text nullable)
- quantity (integer)
- cost_price (decimal 12,2)
- margin_persen (decimal 5,2)
- total_cost (decimal 12,2)
- supplier (string nullable)
- supplier_contact (string nullable)
- payment_method (string nullable) - NEW
- order_date (date)
- estimated_arrival (date nullable)
- received_date (date nullable)
- status (enum: pending, shipped, received, cancelled)
- notes (text nullable)
- is_new_sparepart (boolean)
- timestamps
```

### **Kolom Baru di Tabel Existing:**

#### 1. `spareparts` - Tambah kolom pricing
```sql
ALTER TABLE spareparts ADD COLUMN cost_price DECIMAL(12,2);
ALTER TABLE spareparts ADD COLUMN margin_percent DECIMAL(5,2);
ALTER TABLE spareparts ADD COLUMN average_cost DECIMAL(12,2);
```

#### 2. `sparepart_purchases` - Tambah margin & harga jual
```sql
ALTER TABLE sparepart_purchases ADD COLUMN margin_persen DECIMAL(5,2) DEFAULT 0;
ALTER TABLE sparepart_purchases ADD COLUMN harga_jual DECIMAL(12,2) NULLABLE;
```

---

## 📁 File Baru

### **Models:**
- `app/Models/SparepartPurchaseOrder.php` - Model PO dengan auto-generate nomor
- `app/Models/SparepartPurchase.php` - Model riwayat pembelian (updated)

### **Resources:**
- `app/Filament/Resources/SparepartPurchaseOrderResource.php`
- `app/Filament/Resources/SparepartPurchaseOrders/Schemas/PurchaseOrderForm.php`
- `app/Filament/Resources/SparepartPurchaseOrders/Tables/PurchaseOrdersTable.php`
- `app/Filament/Resources/SparepartPurchaseOrders/Pages/ListSparepartPurchaseOrders.php`
- `app/Filament/Resources/SparepartPurchaseOrders/Pages/CreateSparepartPurchaseOrder.php`
- `app/Filament/Resources/SparepartPurchaseOrders/Pages/EditSparepartPurchaseOrder.php`

### **Relation Managers:**
- `app/Filament/Resources/Spareparts/RelationManagers/PurchaseOrdersRelationManager.php`
- `app/Filament/Resources/Spareparts/RelationManagers/PurchasesRelationManager.php`

---

## 🔄 File yang Dimodifikasi

### **Models:**
- `app/Models/Sparepart.php`
  - Tambah fillable: `cost_price`, `margin_percent`, `average_cost`
  - Tambah relations: `purchases()`, `purchaseOrders()`
  - Tambah methods: `calculateAverageCost()`, `calculateSellingPrice()`, `updatePricing()`

- `app/Models/SparepartPurchase.php`
  - Update fillable dengan field baru
  - Tambah casts untuk decimal fields

### **Resources:**
- `app/Filament/Resources/Spareparts/SparepartResource.php`
  - Tambah relation managers untuk PO dan Purchases

### **Widgets:**
- `app/Filament/Widgets/StatsOverview.php`
  - Tambah statistik pesanan berdasarkan status real
  - Tambah statistik sparepart & PO pending
  - Update dengan status yang benar: `belum mulai`, `analisa`, `konfirmasi`, `dalam proses`, `selesai`, `dibayar`, `batal`

---

## 🚀 Deployment Instructions

### **1. Pull Latest Code**
```bash
cd /path/to/project
git pull origin main
```

### **2. Install Dependencies (jika ada perubahan)**
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### **3. Jalankan Migrations**
```bash
php artisan migrate --force
```

**⚠️ PENTING:** Migration akan membuat tabel baru dan menambah kolom. Pastikan backup database terlebih dahulu!

### **4. Clear Cache**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

### **5. Set Permissions (jika diperlukan)**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### **6. Restart Services**
```bash
# Jika menggunakan PHP-FPM
sudo systemctl restart php8.2-fpm

# Jika menggunakan queue worker
php artisan queue:restart
```

---

## 🔧 Konfigurasi Tambahan

### **Tidak ada perubahan di `.env`**
Semua fitur menggunakan konfigurasi existing.

### **Permissions yang Diperlukan**
- Web server harus bisa write ke `storage/` dan `bootstrap/cache/`
- Database user harus punya permission untuk `CREATE TABLE` dan `ALTER TABLE`

---

## 🧪 Testing Checklist

### **Setelah Deploy, Test:**

- [ ] Dashboard menampilkan statistik dengan benar
- [ ] Buat Purchase Order baru
- [ ] Update status PO (Pending → Shipped)
- [ ] Klik "Terima Barang" dan verify:
  - [ ] Stok sparepart bertambah
  - [ ] Transaksi pengeluaran tercatat
  - [ ] Harga rata-rata ter-update (jika applicable)
  - [ ] Status PO berubah jadi "Received"
- [ ] Lihat relation tab di detail sparepart
- [ ] Filter dan search di tabel PO
- [ ] Navigation badge menampilkan jumlah PO pending

---

## 📊 Flow Bisnis Baru

### **Purchase Order Flow:**
```
1. Buat PO
   ├─ Toggle: Sparepart Baru / Existing
   ├─ Isi data: Quantity, Harga, Supplier, Metode Pembayaran
   └─ Auto-generate Nomor PO

2. Update Status (opsional)
   └─ Pending → Shipped

3. Terima Barang (Klik tombol)
   ├─ Jika Sparepart Baru: Buat record di tabel spareparts
   ├─ Jika Existing: Update quantity (+)
   ├─ Buat record di sparepart_purchases (history)
   ├─ Update average_cost & selling_price
   ├─ Catat transaksi PENGELUARAN
   └─ Status PO → Received
```

---

## 🎨 UI/UX Changes

### **Menu Baru:**
- **Order Sparepart** (dengan badge jumlah PO pending)
  - Icon: Shopping Bag
  - Badge warning untuk PO pending/shipped

### **Dashboard:**
- **5 Card Status Pesanan:**
  - Belum Mulai (gray)
  - Analisa/Konfirmasi (warning)
  - Dalam Pengerjaan (info)
  - Selesai (success)
  - Sudah Dibayar (primary)

- **3 Card Keuangan:**
  - Pendapatan (success)
  - Pengeluaran (danger)
  - Laba Bersih (dynamic color)

- **3 Card Lainnya:**
  - Total Pelanggan
  - Sparepart (dengan alert stok menipis)
  - PO Pending (dengan badge)

### **Sparepart Detail:**
- Tab baru: "Riwayat Purchase Orders"
- Tab baru: "Riwayat Pembelian"

---

## ⚠️ Breaking Changes

**TIDAK ADA** - Semua perubahan backward compatible.

---

## 📝 Notes untuk DevOps

1. **Backup Database** sebelum migrate
2. **Migration tidak reversible** - pastikan test di staging dulu
3. **Cache di-clear otomatis** saat deploy, tapi lebih baik manual clear juga
4. **Navigation badge** real-time, pastikan query performance oke
5. **Dashboard stats di-cache 5 menit** - normal jika data tidak real-time

---

## 🐛 Known Issues

**TIDAK ADA** - Semua fitur sudah tested dan working.

---

## 👨‍💻 Developer Contact

**Nama:** Alfan  
**Project:** Servis Komputer - PWS Computer  
**Framework:** Laravel + Filament v3  

---

## 📌 Checklist Deploy

```bash
# 1. Backup
mysqldump -u root -p servis_komputer > backup_$(date +%Y%m%d_%H%M%S).sql

# 2. Pull code
git pull origin main

# 3. Composer
composer install --no-dev --optimize-autoloader

# 4. NPM (jika ada perubahan frontend)
npm install && npm run build

# 5. Migrate
php artisan migrate --force

# 6. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

# 7. Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Restart services
sudo systemctl restart php8.2-fpm

# 9. Test
# Akses dashboard dan test semua fitur baru
```

---

**✅ Deploy Selesai!**

Jika ada error atau pertanyaan, hubungi developer.
