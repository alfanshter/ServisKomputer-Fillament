# ⚡ Quick Deployment Checklist - Update 22 Nov 2025

## 🎯 Pre-Deployment
- [ ] Backup database: `mysqldump -u user -p db > backup_$(date +%Y%m%d).sql`
- [ ] Backup files: Copy folder `app/`, `database/migrations/`, `resources/`
- [ ] Test di local: Semua fitur jalan
- [ ] Commit & push to Git

---

## 📦 Deployment Steps

### 1. Upload Files
```bash
# Via Git
git pull origin main

# Atau upload manual via FTP:
- app/Models/Service.php (NEW)
- app/Models/Pesanan.php (MODIFIED)
- app/Models/PesananPurchaseOrderItem.php (MODIFIED)
- app/Filament/Resources/Services/* (NEW FOLDER)
- app/Filament/Resources/Pesanans/Tables/PesanansTable.php (MODIFIED)
- app/Filament/Resources/Pesanans/Pages/EditPesanan.php (MODIFIED)
- app/Http/Controllers/PrintController.php (MODIFIED)
- resources/views/print/invoice.blade.php (MODIFIED)
- database/migrations/2025_11_22_042429_create_services_table.php (NEW)
- database/migrations/2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items.php (NEW)
```

### 2. Run Migration
```bash
php artisan migrate
```

**Expected output:**
```
✓ 2025_11_22_042429_create_services_table
✓ 2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items
```

### 3. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

### 4. Restart Services
```bash
sudo systemctl restart php8.2-fpm
php artisan queue:restart  # jika pakai queue
```

---

## ✅ Quick Testing (5 Menit)

### Test 1: Master Jasa
- [ ] Buka menu "Master Jasa"
- [ ] Buat jasa baru: "Test Service - Rp50.000"
- [ ] Berhasil tersimpan ✓

### Test 2: Analisa Selesai dengan Jasa & Sparepart
- [ ] Buat pesanan baru
- [ ] Analisa → Analisa Selesai
- [ ] Tambah jasa dari master
- [ ] Tambah sparepart (dari stok ATAU dari PO)
- [ ] Simpan → Berhasil ✓

### Test 3: Invoice
- [ ] Print Invoice pesanan tadi
- [ ] Jasa muncul ✓
- [ ] Sparepart muncul ✓
- [ ] Total benar ✓

### Test 4: Edit & Diskon
- [ ] Edit pesanan → Tambah diskon Rp10.000
- [ ] Simpan
- [ ] Print Invoice → Total = (Subtotal - Diskon) ✓

---

## 🚨 Rollback (Jika Error)

```bash
# 1. Restore database
mysql -u user -p db < backup_YYYYMMDD.sql

# 2. Restore code
git reset --hard HEAD~1  # atau commit hash sebelumnya

# 3. Clear cache
php artisan optimize:clear
```

---

## 📝 What's New?

### ✨ Fitur Baru:
1. **Master Data Jasa** - Catalog service dengan kategori & harga
2. **Multi-Source Sparepart** - Pilih dari stok ATAU dari PO
3. **Invoice Update** - Tampilan jasa & sparepart lebih detail

### 🐛 Bug Fixed:
1. Total cost salah saat edit pesanan
2. Sparepart dari PO tidak muncul
3. Diskon tidak ter-calculate

---

## 🆘 Emergency Contact

**Jika ada masalah:**
1. Cek file `DEPLOYMENT_UPDATE_22_NOV_2025.md` untuk detail lengkap
2. Screenshot error & kirim via WA
3. Jangan panic, ada backup! 😊

---

**Status:** Ready to Deploy 🚀  
**Estimated Time:** 10-15 menit  
**Risk Level:** Low (sudah ada backup)

**Good luck!** 🎉
