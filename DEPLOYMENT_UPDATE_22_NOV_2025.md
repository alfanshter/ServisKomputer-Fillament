# 🚀 Deployment Update - 22 November 2025

## 📋 Ringkasan Update

Update besar-besaran pada sistem management servis komputer dengan fokus pada:
1. ✅ Master Data Jasa Service
2. ✅ Multi-source Sparepart Selection (Stok Gudang + Purchase Order)
3. ✅ Invoice & WhatsApp Template Update
4. ✅ Bug Fixes untuk Calculation & Display

---

## 🆕 FITUR BARU

### 1. Master Data Jasa Service
**Deskripsi:** Sistem catalog jasa service dengan kategori dan harga standar.

**Files yang Diubah/Dibuat:**
- ✅ `database/migrations/2025_11_22_042429_create_services_table.php` (NEW)
- ✅ `app/Models/Service.php` (NEW)
- ✅ `app/Filament/Resources/Services/ServiceResource.php` (NEW)
- ✅ `app/Filament/Resources/Services/Schemas/ServiceForm.php` (NEW)
- ✅ `app/Filament/Resources/Services/Tables/ServicesTable.php` (NEW)
- ✅ `app/Models/Pesanan.php` (MODIFIED - tambah relasi services)

**Kategori Jasa:**
- Hardware Repair
- Software Installation
- Cleaning & Maintenance
- Upgrade
- Data Recovery
- Consultation
- Other

**Migration SQL:**
```sql
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(12,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `pesanan_service` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_id` (`pesanan_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `pesanan_service_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
);
```

---

### 2. Multi-Source Sparepart Selection (FITUR UTAMA!)
**Deskripsi:** Teknisi bisa pilih sparepart dari 2 sumber saat "Analisa Selesai":
- 📦 **Stok Gudang** (spareparts.quantity > 0)
- 🛒 **Purchase Order** (status: pending/shipped)

**Files yang Diubah:**
- ✅ `app/Filament/Resources/Pesanans/Tables/PesanansTable.php` (MODIFIED)
- ✅ `app/Models/PesananPurchaseOrderItem.php` (MODIFIED)
- ✅ `database/migrations/2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items.php` (NEW)

**Migration SQL:**
```sql
-- Buat kolom sparepart_id nullable
ALTER TABLE `pesanan_purchase_order_items` 
MODIFY COLUMN `sparepart_id` bigint unsigned NULL;
```

**Flow Logic:**

**A. Pilih dari Stok Gudang:**
```
1. User pilih: "📦 RAM 8GB - Stok: 5 - Rp500.000"
2. System:
   - Attach ke pesanan_sparepart
   - Kurangi spareparts.quantity
   - Hitung total_cost
```

**B. Pilih dari Purchase Order:**
```
1. User pilih: "🛒 PO: Keyboard Macbook M1 - ⏳ Pending - Qty: 1"
2. System:
   - Jika PO punya sparepart_id → Attach ke pesanan_sparepart
   - Jika PO untuk sparepart baru → Buat master sparepart (qty=0), lalu attach
   - Buat link di pesanan_purchase_order_items
   - Kurangi PO.quantity
   - TIDAK kurangi spareparts.quantity (barang belum datang)
```

**Dropdown Options Generation:**
```php
// File: app/Filament/Resources/Pesanans/Tables/PesanansTable.php
// Line ~293-320

Select::make('sparepart_id')
    ->options(function () {
        $options = [];
        
        // 1️⃣ Sparepart dari stok
        $sparepartsInStock = \App\Models\Sparepart::query()
            ->where('quantity', '>', 0)
            ->get();
        
        foreach ($sparepartsInStock as $sp) {
            $options["stock_{$sp->id}"] = "📦 {$sp->name} - Stok: {$sp->quantity} - Rp" . number_format($sp->price, 0, ',', '.');
        }
        
        // 2️⃣ Sparepart dari PO
        $sparepartsInPO = \App\Models\SparepartPurchaseOrder::query()
            ->whereIn('status', ['pending', 'shipped'])
            ->where('quantity', '>', 0)
            ->with('sparepart')
            ->get();
        
        foreach ($sparepartsInPO as $po) {
            $name = $po->sparepart?->name ?? $po->sparepart_name ?? 'Unknown';
            $price = $po->sparepart?->price ?? $po->cost_price ?? 0;
            $statusLabel = $po->status === 'pending' ? '⏳ Pending' : '🚚 Dikirim';
            $options["po_{$po->id}"] = "🛒 PO: {$name} - {$statusLabel} - Qty: {$po->quantity} - Rp" . number_format($price, 0, ',', '.');
        }
        
        return $options;
    })
```

**Hidden Fields untuk Tracking:**
```php
Hidden::make('source_type'), // 'stock' atau 'po'
Hidden::make('source_id'),   // ID sparepart atau PO
Hidden::make('po_id'),        // ID PO (jika dari PO)
```

---

### 3. Invoice & Template Updates
**Deskripsi:** Update invoice dan WhatsApp template untuk menampilkan jasa dari master data.

**Files yang Diubah:**
- ✅ `app/Http/Controllers/PrintController.php` (MODIFIED)
- ✅ `resources/views/print/invoice.blade.php` (MODIFIED)

**Changes:**
```php
// PrintController.php - Tambah eager loading services
$data = Pesanan::with(['user', 'services', 'spareparts'])->findOrFail($id);
```

**Invoice Template - Loop Jasa:**
```blade
{{-- Loop jasa dari master data --}}
@foreach($data->services as $service)
<tr>
    <td>{{ $no++ }}</td>
    <td><strong>{{ $service->name }}</strong><br><small>Jasa Service</small></td>
    <td>{{ $service->pivot->quantity }}</td>
    <td>Rp {{ number_format($service->pivot->price, 0, ',', '.') }}</td>
    <td><strong>Rp {{ number_format($service->pivot->subtotal, 0, ',', '.') }}</strong></td>
</tr>
@php $totalBiaya += $service->pivot->subtotal; @endphp
@endforeach

{{-- Loop sparepart --}}
@foreach($data->spareparts as $sparepart)
<tr>
    <td>{{ $no++ }}</td>
    <td><strong>{{ $sparepart->name }}</strong><br><small>Sparepart</small></td>
    <td>{{ $sparepart->pivot->quantity }}</td>
    <td>Rp {{ number_format($sparepart->pivot->price, 0, ',', '.') }}</td>
    <td><strong>Rp {{ number_format($sparepart->pivot->subtotal, 0, ',', '.') }}</strong></td>
</tr>
@php $totalBiaya += $sparepart->pivot->subtotal; @endphp
@endforeach
```

**Diskon Display:**
```blade
@if($data->discount > 0)
<tr class="summary-row">
    <td class="text-right">Diskon:</td>
    <td class="text-right">- Rp {{ number_format($data->discount, 0, ',', '.') }}</td>
</tr>
@endif
```

---

### 4. WhatsApp Template Updates
**Files yang Diubah:**
- ✅ `app/Filament/Resources/Pesanans/Tables/PesanansTable.php` (2 template locations)

**Template "Selesai Analisa" (Line ~490-520):**
```php
$message .= "💰 *RINCIAN BIAYA:*\n";

// Jasa Service dari master data
$totalJasaCost = 0;
if ($record->services && $record->services->count() > 0) {
    foreach ($record->services as $service) {
        $qty = $service->pivot->quantity;
        $price = $service->pivot->price;
        $subtotal = $service->pivot->subtotal;
        $totalJasaCost += $subtotal;
        
        $priceFormat = 'Rp' . number_format($price, 0, ',', '.');
        $subtotalFormat = 'Rp' . number_format($subtotal, 0, ',', '.');
        
        $message .= "• {$service->name} ({$qty}x {$priceFormat}): {$subtotalFormat}\n";
    }
}

// Sparepart
$totalSparepart = 0;
if ($record->spareparts && $record->spareparts->count() > 0) {
    foreach ($record->spareparts as $sparepart) {
        $qty = $sparepart->pivot->quantity;
        $price = $sparepart->pivot->price;
        $subtotal = $sparepart->pivot->subtotal;
        $totalSparepart += $subtotal;
        
        $priceFormat = 'Rp' . number_format($price, 0, ',', '.');
        $subtotalFormat = 'Rp' . number_format($subtotal, 0, ',', '.');
        
        $message .= "• {$sparepart->name} ({$qty}x {$priceFormat}): {$subtotalFormat}\n";
    }
}

// Total
$subtotalAll = $totalJasaCost + $totalSparepart;
$diskon = $record->discount ?? 0;
$totalBiaya = $subtotalAll - $diskon;
```

---

## 🐛 BUG FIXES

### 1. Total Cost Calculation Error (CRITICAL FIX!)
**Problem:** Saat edit pesanan dan tambah diskon, total_cost salah karena masih pakai `service_cost` field lama.

**File:** `app/Filament/Resources/Pesanans/Pages/EditPesanan.php`

**Before (SALAH):**
```php
$serviceCost = $record->service_cost ?? 0; // ❌ Field lama, selalu 0
$sparepartCost = $record->spareparts->sum('pivot.subtotal') ?? 0;
$totalCost = $serviceCost + $sparepartCost - $discount;
// Contoh: 0 + 700.000 - 20.000 = 680.000 (SALAH!)
```

**After (BENAR):**
```php
$serviceCost = $record->services->sum('pivot.subtotal') ?? 0; // ✅ Dari master jasa
$sparepartCost = $record->spareparts->sum('pivot.subtotal') ?? 0;
$totalCost = $serviceCost + $sparepartCost - $discount;
// Contoh: 225.000 + 700.000 - 20.000 = 905.000 (BENAR!)
```

**Line:** 170

---

### 2. Sparepart Tidak Muncul di Invoice
**Problem:** Ketika pilih sparepart dari PO, tidak tersimpan ke `pesanan_sparepart`, jadi tidak muncul di invoice.

**Solution:** Simpan ke KEDUA tabel:
- `pesanan_purchase_order_items` (untuk tracking PO)
- `pesanan_sparepart` (untuk display di invoice/WhatsApp)

**File:** `app/Filament/Resources/Pesanans/Tables/PesanansTable.php`
**Line:** ~945-990

---

### 3. PO Status 'used' Error
**Problem:** Enum 'used' tidak valid di tabel `sparepart_purchase_orders`.

**Valid enum:** `pending`, `shipped`, `received`, `cancelled`

**Solution:** Tidak update status PO otomatis, biarkan manual via "Terima Barang".

---

### 4. Sparepart_id Cannot be NULL Error
**Problem:** PO untuk sparepart baru tidak punya `sparepart_id`, tapi kolom required.

**Solution:** 
- Buat migration untuk set kolom nullable
- Auto-create sparepart master saat dipilih dari PO

**Migration:** `2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items.php`

---

## 📁 FILES YANG BERUBAH/BARU

### New Files (6 files)
```
database/migrations/2025_11_22_042429_create_services_table.php
database/migrations/2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items.php
app/Models/Service.php
app/Filament/Resources/Services/ServiceResource.php
app/Filament/Resources/Services/Schemas/ServiceForm.php
app/Filament/Resources/Services/Tables/ServicesTable.php
```

### Modified Files (6 files)
```
app/Models/Pesanan.php
app/Models/PesananPurchaseOrderItem.php
app/Filament/Resources/Pesanans/Tables/PesanansTable.php
app/Filament/Resources/Pesanans/Pages/EditPesanan.php
app/Http/Controllers/PrintController.php
resources/views/print/invoice.blade.php
```

---

## 🔧 LANGKAH DEPLOYMENT KE SERVER

### 1. Backup Database
```bash
# Di server
mysqldump -u username -p database_name > backup_before_update_$(date +%Y%m%d_%H%M%S).sql
```

### 2. Pull/Upload Code
```bash
# Jika pakai Git
git pull origin main

# Atau upload manual via FTP/SFTP:
# - Semua file di folder: app/
# - Semua file di folder: database/migrations/
# - File: resources/views/print/invoice.blade.php
```

### 3. Install Dependencies (jika ada yang baru)
```bash
composer install --no-dev --optimize-autoloader
```

### 4. Run Migrations
```bash
php artisan migrate
```

**Expected migrations:**
- ✅ `2025_11_22_042429_create_services_table`
- ✅ `2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items`

### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

### 6. Set Permissions (jika perlu)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Restart Services
```bash
# Jika pakai PHP-FPM
sudo systemctl restart php8.2-fpm

# Jika pakai Queue Worker
php artisan queue:restart
```

---

## ✅ TESTING CHECKLIST SETELAH DEPLOYMENT

### A. Master Data Jasa
- [ ] Buka menu "Master Jasa"
- [ ] Buat jasa baru (contoh: "Ganti Keyboard - Rp100.000")
- [ ] Edit jasa
- [ ] Hapus jasa
- [ ] Filter by category
- [ ] Toggle is_active

### B. Multi-Source Sparepart Selection
- [ ] Buat pesanan baru
- [ ] Status: Analisa → Analisa Selesai
- [ ] Tambah jasa dari master
- [ ] Pilih sparepart dari stok gudang (📦)
- [ ] Pilih sparepart dari PO (🛒)
- [ ] Simpan
- [ ] Cek apakah jasa & sparepart muncul

### C. Invoice
- [ ] Buka pesanan yang sudah ada jasa + sparepart
- [ ] Klik "Print Invoice"
- [ ] Verify: Jasa muncul dengan detail
- [ ] Verify: Sparepart muncul dengan detail
- [ ] Verify: Diskon muncul (jika ada)
- [ ] Verify: Total benar (subtotal - diskon)

### D. WhatsApp Template
- [ ] Pesanan status "Selesai Analisa" → Klik tombol
- [ ] Cek template WhatsApp
- [ ] Verify: Jasa service listed
- [ ] Verify: Sparepart listed
- [ ] Verify: Total calculation benar

### E. Edit Pesanan
- [ ] Buka pesanan existing
- [ ] Edit → Tambah diskon 20.000
- [ ] Simpan
- [ ] Verify: total_cost ter-update dengan benar
- [ ] Cek invoice → Total harus benar (subtotal - diskon)

### F. Purchase Order Integration
- [ ] Buat PO baru untuk sparepart baru
- [ ] Status: Pending
- [ ] Buat pesanan → Analisa Selesai
- [ ] Pilih sparepart dari PO tersebut
- [ ] Verify: PO quantity berkurang
- [ ] Verify: Sparepart muncul di invoice
- [ ] Verify: Link tersimpan di pesanan_purchase_order_items

---

## 📊 DATABASE CHANGES SUMMARY

### New Tables
- `services` - Master data jasa service
- `pesanan_service` - Pivot table pesanan ↔ services

### Modified Tables
- `pesanan_purchase_order_items`:
  - Column `sparepart_id` → NULLABLE

### New Relationships
- `Pesanan` ↔ `Service` (belongsToMany via pesanan_service)
- `PesananPurchaseOrderItem` ↔ `Sparepart` (belongsTo)

---

## 🔍 ROLLBACK PLAN (Jika Ada Masalah)

### 1. Restore Database
```bash
mysql -u username -p database_name < backup_before_update_YYYYMMDD_HHMMSS.sql
```

### 2. Revert Code
```bash
git revert HEAD
# atau
git reset --hard commit_hash_sebelum_update
```

### 3. Clear Cache Again
```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize
```

---

## 📞 SUPPORT & TROUBLESHOOTING

### Common Issues:

**1. Migration Error: Table already exists**
```bash
# Skip migration yang error, run yang baru saja
php artisan migrate --path=database/migrations/2025_11_22_042429_create_services_table.php
php artisan migrate --path=database/migrations/2025_11_22_053308_make_sparepart_id_nullable_in_pesanan_purchase_order_items.php
```

**2. Invoice kosong / tidak muncul data**
- Cek apakah `PrintController.php` sudah ter-upload
- Verify eager loading: `->with(['user', 'services', 'spareparts'])`

**3. Total cost salah saat edit**
- Cek `EditPesanan.php` line 170
- Pastikan pakai `$record->services->sum('pivot.subtotal')`

**4. Sparepart dari PO tidak muncul**
- Cek `PesanansTable.php` line ~945-990
- Pastikan logic simpan ke `pesanan_sparepart` jalan

**5. Dropdown PO kosong**
- Verify ada PO dengan status 'pending' atau 'shipped'
- Verify PO quantity > 0

---

## 📝 NOTES

- Field `service_cost` di tabel `pesanans` sekarang tidak dipakai lagi, diganti dengan relasi `services`
- Untuk backward compatibility, field tidak dihapus, hanya di-set ke 0
- Sparepart dari PO akan auto-create master sparepart dengan qty=0 jika belum ada
- PO status tidak auto-update ke 'used', admin harus manual "Terima Barang"

---

## 🎉 CHANGELOG SUMMARY

**Version:** 2.5.0  
**Date:** 22 November 2025  
**Type:** Major Update

**Added:**
- ✅ Master Data Jasa Service dengan 7 kategori
- ✅ Multi-source sparepart selection (Stok + PO)
- ✅ Auto-create sparepart dari PO
- ✅ Enhanced invoice dengan detail jasa & sparepart
- ✅ Updated WhatsApp templates

**Fixed:**
- ✅ Total cost calculation saat edit pesanan
- ✅ Sparepart tidak muncul di invoice
- ✅ PO status enum error
- ✅ Sparepart_id nullable error

**Changed:**
- ✅ Invoice template dari service_cost ke services relation
- ✅ WhatsApp template dari service_cost ke services relation
- ✅ Edit pesanan calculation logic

**Deprecated:**
- ⚠️ Field `service_cost` di tabel `pesanans` (masih ada tapi tidak dipakai)

---

**Prepared by:** GitHub Copilot  
**Review by:** Developer Team  
**Approved for deployment:** 22 November 2025  

🚀 **Happy Deploying!**
