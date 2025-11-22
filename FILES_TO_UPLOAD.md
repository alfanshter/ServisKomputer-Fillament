# 📦 Files to Upload - Update 22 Nov 2025

## 🆕 NEW FILES (Upload Semua)

### Database Migrations
```
database/migrations/2025_11_22_042429_create_services_table.php
database/migrations/2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items.php
```

### Models
```
app/Models/Service.php
```

### Filament Resources (Folder Baru)
```
app/Filament/Resources/Services/
├── ServiceResource.php
├── Schemas/
│   └── ServiceForm.php
└── Tables/
    └── ServicesTable.php
```

### Documentation
```
DEPLOYMENT_UPDATE_22_NOV_2025.md
QUICK_DEPLOYMENT_CHECKLIST.md
MANUAL_MIGRATION_22_NOV_2025.sql
FILES_TO_UPLOAD.md (file ini)
FITUR_MASTER_DATA_JASA.md
FITUR_SPAREPART_MULTI_SOURCE.md
```

---

## ✏️ MODIFIED FILES (Upload - Ganti yang Lama)

### Models
```
app/Models/Pesanan.php
app/Models/PesananPurchaseOrderItem.php
```

### Filament Resources
```
app/Filament/Resources/Pesanans/Tables/PesanansTable.php
app/Filament/Resources/Pesanans/Pages/EditPesanan.php
```

### Controllers
```
app/Http/Controllers/PrintController.php
```

### Views
```
resources/views/print/invoice.blade.php
```

---

## 📋 Upload Checklist

### Via FTP/SFTP:
- [ ] Upload semua file NEW FILES
- [ ] Upload (replace) semua MODIFIED FILES
- [ ] Verify struktur folder `app/Filament/Resources/Services/` benar

### Via Git:
```bash
# Di local
git add .
git commit -m "Update: Master Jasa + Multi-Source Sparepart + Bug Fixes"
git push origin main

# Di server
git pull origin main
```

### After Upload:
```bash
# 1. Run migration
php artisan migrate

# 2. Clear cache
php artisan optimize:clear

# 3. Restart PHP
sudo systemctl restart php8.2-fpm
```

---

## 🔍 Verification

Setelah upload, cek file-file ini sudah ada di server:

```bash
# Cek migrations
ls -la database/migrations/2025_11_22_*

# Cek Service model
ls -la app/Models/Service.php

# Cek Filament Services folder
ls -la app/Filament/Resources/Services/

# Cek modified files timestamp
ls -la app/Models/Pesanan.php
ls -la app/Filament/Resources/Pesanans/Tables/PesanansTable.php
ls -la app/Filament/Resources/Pesanans/Pages/EditPesanan.php
ls -la app/Http/Controllers/PrintController.php
ls -la resources/views/print/invoice.blade.php
```

---

## ⚠️ IMPORTANT NOTES

1. **Jangan lupa backup database** sebelum run migration
2. **Jangan lupa clear cache** setelah upload
3. File `app/Filament/Resources/Services/` adalah **FOLDER BARU**, pastikan struktur foldernya benar
4. Jika error saat migrate, pakai script SQL manual: `MANUAL_MIGRATION_22_NOV_2025.sql`

---

## 📊 Total Files

- **New Files:** 9 files (3 migrations + 3 models/resources + 3 documentation)
- **Modified Files:** 6 files
- **Total:** 15 files

**Estimated Upload Time:** 2-3 menit (tergantung koneksi)

---

## ✅ Quick Test After Upload

```bash
# Test 1: Cek route Master Jasa
php artisan route:list | grep services

# Test 2: Cek model
php artisan tinker
>>> \App\Models\Service::count();

# Test 3: Akses admin panel
# Buka: https://yoursite.com/admin/services
```

---

**Ready to Upload!** 🚀
