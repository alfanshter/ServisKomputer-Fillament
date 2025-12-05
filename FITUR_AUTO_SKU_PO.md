# 🏷️ AUTO-GENERATE SKU di Purchase Order

**Tanggal:** 5 Desember 2025  
**Tipe:** Feature Enhancement

---

## 🎯 Fitur

SKU (Stock Keeping Unit) sekarang **otomatis dibuat** saat menambah Purchase Order untuk **sparepart baru**.

### Behaviour:

#### 1️⃣ Sparepart Existing (dari inventory)
- User pilih sparepart dari dropdown
- SKU **otomatis terisi** dari master sparepart
- Field SKU **disabled** (tidak bisa diedit)

#### 2️⃣ Sparepart Baru ✨
- User centang "Sparepart Baru"
- SKU **otomatis dibuat** dengan format: `SPR-YYYY-XXXX`
- Field SKU **enabled** (bisa diedit jika perlu)
- Contoh: `SPR-2025-0001`, `SPR-2025-0002`, dst.

---

## 📐 Format SKU

```
SPR-YYYY-XXXX
│   │    │
│   │    └─ Nomor urut (4 digit, zero-padded)
│   └────── Tahun
└────────── Prefix (Sparepart)
```

**Contoh:**
- `SPR-2025-0001` → Sparepart pertama tahun 2025
- `SPR-2025-0002` → Sparepart kedua tahun 2025
- `SPR-2025-0099` → Sparepart ke-99 tahun 2025
- `SPR-2025-0100` → Sparepart ke-100 tahun 2025
- `SPR-2026-0001` → Sparepart pertama tahun 2026 (reset counter)

---

## 🔧 Cara Kerja

### Logic:
1. User centang "Sparepart Baru (belum ada di inventory)"
2. Sistem cari SKU terakhir untuk tahun ini: `SPR-2025-%`
3. Extract nomor urut dari SKU terakhir
4. Increment nomor urut + 1
5. Generate SKU baru dengan format: `SPR-{tahun}-{nomor urut}`

### Code:
```php
protected static function generateSKU(): string
{
    $year = now()->format('Y');
    $prefix = "SPR-{$year}-";

    // Cari SKU terakhir untuk tahun ini
    $lastSKU = \App\Models\Sparepart::where('sku', 'like', $prefix . '%')
        ->orderBy('sku', 'desc')
        ->value('sku');

    // Jika tidak ada, mulai dari 0001
    if (!$lastSKU) {
        return $prefix . '0001';
    }

    // Extract nomor urut & increment
    $lastNumber = (int) substr($lastSKU, -4);
    $newNumber = $lastNumber + 1;

    // Format dengan 4 digit zero-padding
    return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}
```

---

## 🎨 UI/UX

### Helper Text:
- **Sparepart Baru:** "✅ SKU otomatis dibuat. Bisa diedit jika perlu."
- **Sparepart Existing:** "SKU dari sparepart yang dipilih"

### Field State:
| Toggle Sparepart Baru | SKU Field State | SKU Value           |
|-----------------------|-----------------|---------------------|
| ❌ OFF (existing)     | 🔒 Disabled     | Dari master         |
| ✅ ON (baru)          | ✏️ Enabled      | Auto-generated      |

---

## 📊 Contoh Penggunaan

### Skenario 1: PO untuk Sparepart Baru
```
1. Buka form "Create Purchase Order"
2. Centang ✅ "Sparepart Baru (belum ada di inventory)"
3. Field SKU otomatis terisi: SPR-2025-0015
4. Isi nama sparepart: "SSD Samsung 1TB"
5. Isi detail lainnya
6. Submit
```

**Hasil:**
- PO dibuat dengan SKU: `SPR-2025-0015`
- Saat barang diterima, sparepart baru dibuat dengan SKU yang sama

### Skenario 2: PO untuk Sparepart Existing
```
1. Buka form "Create Purchase Order"
2. Toggle OFF (sparepart existing)
3. Pilih sparepart: "RAM DDR4 8GB Kingston"
4. Field SKU auto-terisi: SPR-2024-0025 (dari master)
5. Field SKU disabled (tidak bisa diedit)
6. Submit
```

**Hasil:**
- PO dibuat dengan SKU dari master sparepart

### Skenario 3: Edit SKU Manual
```
1. Buka form "Create Purchase Order"
2. Centang ✅ "Sparepart Baru"
3. SKU otomatis: SPR-2025-0015
4. User edit manual jadi: CUSTOM-SSD-001
5. Submit
```

**Hasil:**
- PO dibuat dengan SKU custom: `CUSTOM-SSD-001`
- Sistem tetap terima SKU custom

---

## ✅ Keuntungan

1. **Konsistensi Format** ✅
   - Semua SKU mengikuti format standar
   - Mudah diidentifikasi dan dicari

2. **Hemat Waktu** ✅
   - User tidak perlu mikir format SKU
   - Tidak perlu cek SKU terakhir manual

3. **Mencegah Duplikasi** ✅
   - Sistem otomatis cari SKU terakhir
   - Increment nomor urut otomatis

4. **Fleksibel** ✅
   - User tetap bisa edit SKU jika perlu
   - Support SKU custom

5. **Tracking per Tahun** ✅
   - Counter reset setiap tahun
   - Mudah lihat jumlah sparepart per tahun

---

## 🧪 Testing

### Test 1: SKU Auto-Generate Pertama Kali
- [ ] Buat PO baru dengan "Sparepart Baru"
- [ ] ✅ Cek: SKU terisi otomatis `SPR-2025-0001` (jika belum ada)
- [ ] Submit
- [ ] Buat PO baru lagi
- [ ] ✅ Cek: SKU otomatis `SPR-2025-0002` (increment)

### Test 2: SKU dari Master Sparepart
- [ ] Buat PO baru
- [ ] Toggle OFF "Sparepart Baru"
- [ ] Pilih sparepart existing
- [ ] ✅ Cek: SKU terisi dari master
- [ ] ✅ Cek: Field SKU disabled

### Test 3: Edit SKU Manual
- [ ] Buat PO baru dengan "Sparepart Baru"
- [ ] SKU otomatis: `SPR-2025-0003`
- [ ] Edit manual jadi: `CUSTOM-001`
- [ ] Submit
- [ ] ✅ Cek: PO tersimpan dengan SKU `CUSTOM-001`

### Test 4: Toggle Sparepart Baru
- [ ] Buat PO baru
- [ ] Centang "Sparepart Baru" → ✅ SKU auto-generate
- [ ] Un-centang → ✅ SKU jadi kosong
- [ ] Centang lagi → ✅ SKU auto-generate lagi

---

## 📁 File yang Diubah

### Modified:
- ✅ `/app/Filament/Resources/SparepartPurchaseOrders/Schemas/PurchaseOrderForm.php`
  - Tambah `afterStateUpdated` di toggle `is_new_sparepart`
  - Tambah method `generateSKU()`
  - Tambah helper text di field SKU

---

## 🚀 Deployment

### No Migration Required ✅
- Hanya perubahan logic di form
- Tidak ada perubahan database schema

### Steps:
1. Pull code
2. Clear cache: `php artisan optimize:clear`
3. Test form PO

---

## 📝 Notes

### Catatan Penting:
1. **SKU tidak wajib unique** di level PO - hanya reference
2. **SKU akan unique** saat sparepart baru dibuat di master
3. **Counter per tahun** - reset otomatis setiap tahun baru
4. **User bisa override** - field enabled untuk custom SKU

### Future Enhancement:
- [ ] Tambah prefix custom per kategori (RAM-2025-0001, SSD-2025-0001)
- [ ] Validation duplikasi SKU
- [ ] Bulk generate SKU untuk import

---

**Status:** ✅ DONE  
**Breaking Changes:** ❌ NONE  
**Backward Compatible:** ✅ YES
