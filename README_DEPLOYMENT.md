# 🎯 DEPLOYMENT GUIDE - Update 22 November 2025

## 📚 Dokumentasi Tersedia

Pilih dokumentasi sesuai kebutuhan:

### 1. 📖 **DEPLOYMENT_UPDATE_22_NOV_2025.md** (BACA INI DULU!)
   - Penjelasan lengkap semua fitur baru
   - Detail perubahan code
   - Troubleshooting
   - **Recommended untuk Developer**

### 2. ⚡ **QUICK_DEPLOYMENT_CHECKLIST.md**
   - Checklist singkat deployment
   - Testing 5 menit
   - **Recommended untuk Quick Deploy**

### 3. 📦 **FILES_TO_UPLOAD.md**
   - List semua file yang perlu di-upload
   - Verification commands
   - **Recommended untuk Manual Upload (FTP)**

### 4. 💾 **MANUAL_MIGRATION_22_NOV_2025.sql**
   - SQL script untuk run migration manual
   - Gunakan jika `php artisan migrate` error
   - **Recommended untuk Database Admin**

### 5. 🤖 **deploy.sh**
   - Automated deployment script
   - Run semua command otomatis
   - **Recommended untuk Terminal/SSH**

---

## 🚀 Quick Start

### Cara 1: Automated (Recommended)
```bash
# Di server, jalankan:
chmod +x deploy.sh
./deploy.sh
```

### Cara 2: Manual
```bash
# 1. Backup
mysqldump -u root -p database > backup.sql

# 2. Upload files (via FTP atau Git)
git pull origin main

# 3. Run migration
php artisan migrate

# 4. Clear cache
php artisan optimize:clear

# 5. Restart
sudo systemctl restart php8.2-fpm
```

### Cara 3: Database Only (Jika artisan error)
```bash
# Import SQL manual
mysql -u root -p database < MANUAL_MIGRATION_22_NOV_2025.sql
```

---

## ✨ Apa yang Baru?

### 🆕 Fitur Baru
1. **Master Data Jasa Service**
   - Catalog jasa dengan kategori (Hardware, Software, dll)
   - Harga standar per jasa
   - Menu: Admin → Master Jasa

2. **Multi-Source Sparepart Selection**
   - Pilih sparepart dari Stok Gudang (📦)
   - Pilih sparepart dari Purchase Order (🛒)
   - Auto-create master sparepart dari PO

3. **Enhanced Invoice**
   - Detail jasa service dengan qty & subtotal
   - Detail sparepart dengan qty & subtotal
   - Diskon display (jika ada)

### 🐛 Bug Fixes
1. Total cost calculation saat edit pesanan
2. Sparepart dari PO tidak muncul di invoice
3. PO status enum error
4. Diskon tidak ter-calculate dengan benar

---

## 📊 Database Changes

### New Tables
- `services` - Master jasa service
- `pesanan_service` - Pivot pesanan ↔ jasa

### Modified Tables
- `pesanan_purchase_order_items` - sparepart_id jadi nullable

### Total Migrations
- 2 migrations baru

---

## ✅ Testing Checklist

Setelah deployment, test hal berikut:

- [ ] **Master Jasa:** Bisa CRUD jasa service
- [ ] **Analisa Selesai:** Tambah jasa + sparepart (dari stok ATAU PO)
- [ ] **Invoice:** Jasa & sparepart muncul dengan detail
- [ ] **WhatsApp:** Template menampilkan jasa & sparepart
- [ ] **Edit Pesanan:** Total ter-update saat tambah diskon
- [ ] **Multi-source:** Bisa pilih sparepart dari PO

---

## 🆘 Troubleshooting

### Migration Error: Table already exists
```bash
# Skip yang error, run yang baru saja
php artisan migrate --path=database/migrations/2025_11_22_042429_create_services_table.php
```

### Invoice Kosong
- Cek `PrintController.php` sudah ter-upload
- Verify: `->with(['user', 'services', 'spareparts'])`

### Total Cost Salah
- Cek `EditPesanan.php` line 170
- Pastikan: `$record->services->sum('pivot.subtotal')`

### Dropdown PO Kosong
- Verify ada PO dengan status 'pending'/'shipped'
- Verify PO quantity > 0

---

## 🔄 Rollback

Jika ada masalah:

```bash
# 1. Restore database
mysql -u root -p database < backup.sql

# 2. Restore code (jika pakai Git)
git reset --hard HEAD~1

# 3. Clear cache
php artisan optimize:clear
```

---

## 📞 Support

**Jika butuh bantuan:**
1. Baca file `DEPLOYMENT_UPDATE_22_NOV_2025.md` untuk detail lengkap
2. Screenshot error & kirim via WA
3. Check logs: `tail -f storage/logs/laravel.log`

---

## 📝 Summary

**Version:** 2.5.0  
**Date:** 22 November 2025  
**Files Changed:** 15 files (9 new, 6 modified)  
**Migrations:** 2 baru  
**Estimated Time:** 10-15 menit  
**Risk Level:** Low (ada backup!)  

---

## 🎉 Ready to Deploy!

**Pilih metode deployment:**
- 🤖 **Automated:** `./deploy.sh`
- 📋 **Manual:** Ikuti `QUICK_DEPLOYMENT_CHECKLIST.md`
- 💾 **SQL Only:** Import `MANUAL_MIGRATION_22_NOV_2025.sql`

**Good luck! 🚀**

---

**Prepared by:** GitHub Copilot  
**Date:** 22 November 2025  
**Status:** ✅ Ready for Production
