# ✅ QUICK DEPLOYMENT CHECKLIST - Invoice Items

**Tanggal:** 5 Desember 2025  
**Fitur:** Immutable Invoice Items (Snapshot Data)

---

## 📋 Pre-Deployment

### Local Testing
- [x] Migration berhasil
- [x] Data lama ter-migrate (23 pesanan)
- [x] No errors di code
- [ ] Test print invoice pesanan lama
- [ ] Test analisa selesai baru + print invoice
- [ ] Test edit sparepart + cek invoice tetap sama

---

## 🚀 Deployment Steps

### 1. Backup Database ⚠️
```bash
# Di server
php artisan backup:run --only-db
# atau manual mysqldump
mysqldump -u root -p database_name > backup_before_invoice_items.sql
```

### 2. Pull Code
```bash
cd /path/to/project
git pull origin main
```

### 3. Install Dependencies (jika ada)
```bash
composer install --no-dev --optimize-autoloader
```

### 4. Run Migration
```bash
php artisan migrate --path=database/migrations/2025_12_05_create_pesanan_invoice_items_table.php
```

**Expected Output:**
```
INFO  Running migrations.
2025_12_05_create_pesanan_invoice_items_table ......... DONE
```

### 5. Migrate Data Lama
```bash
php artisan db:seed --class=MigrateExistingDataToInvoiceItems
```

**Expected Output:**
```
INFO  Seeding database.
🔄 Migrating existing data to invoice items...
✅ Migration completed!
   - Migrated: XX pesanan
   - Skipped: XX pesanan (already has invoice items)
```

### 6. Clear Cache
```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🧪 Post-Deployment Testing

### Test 1: Invoice Pesanan Lama
- [ ] Buka menu Pesanans
- [ ] Pilih pesanan dengan status "dibayar" (yang sudah selesai)
- [ ] Klik "Invoice" → Print
- [ ] ✅ **Cek:** Semua data sparepart & jasa tampil lengkap
- [ ] ✅ **Cek:** No error, PDF generate dengan baik

### Test 2: Analisa Selesai Baru
- [ ] Buat pesanan baru
- [ ] Status: Belum Mulai → Analisa
- [ ] Klik "Analisa Selesai"
- [ ] Tambah sparepart (dari stok) + jasa
- [ ] Submit
- [ ] Klik "Invoice" → Print
- [ ] ✅ **Cek:** Data sparepart & jasa tampil di invoice
- [ ] ✅ **Cek:** Ada label "Dari Stok" di sparepart

### Test 3: Edit Tidak Pengaruhi Invoice ⭐
- [ ] Buka pesanan dari Test 2
- [ ] Edit pesanan
- [ ] Hapus 1 sparepart yang tadi ditambahkan
- [ ] Save
- [ ] Klik "Invoice" → Print
- [ ] ✅ **PENTING:** Sparepart yang dihapus **MASIH TAMPIL** di invoice!
- [ ] ✅ **Cek:** Data invoice tidak berubah meskipun data pesanan berubah

### Test 4: Sparepart dari PO
- [ ] Buat PO baru untuk sparepart
- [ ] Buat pesanan baru
- [ ] Analisa Selesai → pilih sparepart dari PO
- [ ] Submit
- [ ] Print invoice
- [ ] ✅ **Cek:** Ada label "Dari PO" di sparepart

### Test 5: Database Check
```sql
-- Cek jumlah invoice items
SELECT COUNT(*) FROM pesanan_invoice_items;

-- Cek data sample
SELECT * FROM pesanan_invoice_items LIMIT 10;

-- Cek pesanan yang punya invoice items
SELECT 
    p.id, 
    p.status, 
    COUNT(pii.id) as total_items
FROM pesanans p
LEFT JOIN pesanan_invoice_items pii ON p.id = pii.pesanan_id
GROUP BY p.id, p.status
HAVING total_items > 0
ORDER BY p.id DESC
LIMIT 20;
```

---

## 📊 Verification Checklist

### Database
- [ ] Tabel `pesanan_invoice_items` exists
- [ ] Index `pesanan_id` exists
- [ ] Index `item_type` exists
- [ ] Foreign key constraint exists
- [ ] Data ter-migrate (cek COUNT > 0)

### Code
- [ ] Model `PesananInvoiceItem` exists
- [ ] Relasi di `Pesanan` model ada
- [ ] PrintController load `invoiceItems`
- [ ] View invoice loop `invoiceItems`

### Functionality
- [ ] Print invoice pesanan lama: ✅ Works
- [ ] Print invoice pesanan baru: ✅ Works
- [ ] Edit sparepart tidak pengaruhi invoice: ✅ Works
- [ ] No PHP errors
- [ ] No SQL errors

---

## 🐛 Troubleshooting

### Problem 1: Invoice Kosong (Tidak Ada Item)
**Cause:** Data belum di-migrate atau pesanan belum punya invoice items

**Fix:**
```bash
# Re-run seeder
php artisan db:seed --class=MigrateExistingDataToInvoiceItems
```

### Problem 2: Migration Error "Table Already Exists"
**Cause:** Table sudah dibuat sebelumnya

**Fix:**
Skip migration atau drop table dulu:
```sql
DROP TABLE IF EXISTS pesanan_invoice_items;
```
Then re-run migration.

### Problem 3: Invoice Masih Tampil Data Lama yang Diedit
**Cause:** Ini sebenarnya **BUKAN BUG** - ini adalah **expected behaviour**!

Invoice **SEHARUSNYA** tampil data lama (snapshot saat transaksi), meskipun data pesanan sudah diedit.

Jika butuh update invoice dengan data baru:
1. Edit status pesanan kembali ke "analisa"
2. Submit ulang "Analisa Selesai"
3. Invoice akan punya snapshot data baru

---

## 🔄 Rollback Plan (Emergency)

Jika ada masalah kritis:

### Step 1: Revert PrintController
```php
public function invoice($id)
{
    // Kembali ke cara lama
    $data = Pesanan::with(['user', 'services', 'spareparts'])->findOrFail($id);
    
    $pdf = Pdf::loadView('print.invoice', compact('data'))
        ->setPaper('a4', 'portrait');
    return $pdf->stream("invoice-{$data->id}.pdf");
}
```

### Step 2: Revert View Invoice
Gunakan loop `services` dan `spareparts` seperti sebelumnya (lihat git history)

### Step 3: Clear Cache
```bash
php artisan optimize:clear
```

### Step 4: Test
Print invoice → Should work dengan data dari relasi lama

### Step 5: Drop Table (Optional)
```bash
php artisan migrate:rollback --step=1
# atau manual:
# DROP TABLE pesanan_invoice_items;
```

---

## 📁 Files Changed

### New Files:
- ✅ `database/migrations/2025_12_05_create_pesanan_invoice_items_table.php`
- ✅ `app/Models/PesananInvoiceItem.php`
- ✅ `database/seeders/MigrateExistingDataToInvoiceItems.php`
- ✅ `FITUR_INVOICE_ITEMS_IMMUTABLE.md`
- ✅ `MANUAL_MIGRATION_INVOICE_ITEMS.sql`
- ✅ `QUICK_DEPLOYMENT_INVOICE_ITEMS.md` (this file)

### Modified Files:
- ✅ `app/Models/Pesanan.php` (tambah relasi)
- ✅ `app/Filament/Resources/Pesanans/Tables/PesanansTable.php` (snapshot logic)
- ✅ `app/Http/Controllers/PrintController.php` (load invoiceItems)
- ✅ `resources/views/print/invoice.blade.php` (loop invoiceItems)

---

## ✅ Sign-off

### Developer
- [ ] Code tested locally
- [ ] No errors
- [ ] Documentation complete

### Deployment
- [ ] Backup created
- [ ] Migration success
- [ ] Data migrated
- [ ] Post-deployment tests passed

### Approval
- [ ] Client tested
- [ ] Client approved
- [ ] Production ready

---

**Status:** 🟢 READY FOR DEPLOYMENT  
**Breaking Changes:** ❌ NONE  
**Rollback Available:** ✅ YES  
**Data Loss Risk:** ⚠️ LOW (dengan backup)
