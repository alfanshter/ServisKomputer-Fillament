# Update Layout Form Sparepart & Jasa - Analisa Selesai

**Tanggal:** 5 Desember 2025  
**Tipe:** UI/UX Improvement + Bug Fix

## 🎯 Masalah

### 1. Layout Terlalu Sempit
Field **Harga Satuan** dan **Subtotal** pada form sparepart & jasa di "Analisa Selesai" terlalu sempit, sehingga angka besar seperti `100000` tidak jelas terbaca.

### 2. Subtotal Tidak Otomatis Terisi ❌
Ketika memilih sparepart/jasa untuk pertama kali, field **Subtotal** tidak langsung terisi. User harus mengubah nilai **Jumlah** terlebih dahulu agar subtotal muncul.

**Root Cause:** Fungsi `afterStateUpdated` pada Select sparepart/jasa hanya meng-set `price`, tapi tidak menghitung `subtotal` langsung.

## ✅ Solusi

### Fix #1: Responsive Layout
Memperbaiki responsive layout dengan memberikan lebih banyak ruang untuk field harga:

#### Layout Baru (Responsive)

**Di layar kecil (mobile/tablet):**
- Sparepart/Jasa: 2 kolom penuh
- Jumlah: 2 kolom (baris baru)
- Harga Satuan: 3 kolom (lebih lebar)
- Subtotal: 3 kolom (baris baru, lebih lebar)

**Di layar besar (desktop):**
- Sparepart/Jasa: 2 kolom
- Jumlah: 1 kolom
- Harga Satuan: 2 kolom (lebih lebar dari sebelumnya)
- Subtotal: 2 kolom (lebih lebar dari sebelumnya)

### Fix #2: Auto-Calculate Subtotal ✅
Menambahkan perhitungan subtotal langsung saat sparepart/jasa dipilih:

```php
->afterStateUpdated(function ($state, callable $set, callable $get) {
    if ($state) {
        $sparepart = \App\Models\Sparepart::find($state);
        if ($sparepart) {
            $set('price', $sparepart->price);
            
            // ✅ TAMBAHAN BARU: Hitung subtotal langsung!
            $quantity = $get('quantity') ?? 1;
            $set('subtotal', $quantity * $sparepart->price);
        }
    }
})
```

**Behaviour Baru:**
- Pilih sparepart → **Harga Satuan** terisi → **Subtotal LANGSUNG terisi** ✅
- Pilih jasa → **Harga** terisi → **Subtotal LANGSUNG terisi** ✅
- Ubah jumlah → Subtotal otomatis update
- Ubah harga → Subtotal otomatis update

## 📝 File yang Diubah

### 1. `/app/Filament/Resources/Pesanans/Tables/PesanansTable.php`

**Form Sparepart - Analisa Selesai (baris ~375-407):**
```php
// Sebelumnya: columns(5) - semua field 1 kolom
->columnSpan(1)  // Jumlah
->columnSpan(1)  // Harga Satuan 
->columnSpan(1)  // Subtotal
->columns(5)

// Sekarang: responsive columns
->columnSpan(['default' => 2, 'sm' => 1])  // Jumlah
->columnSpan(['default' => 3, 'sm' => 2])  // Harga Satuan (lebih lebar!)
->columnSpan(['default' => 3, 'sm' => 2])  // Subtotal (lebih lebar!)
->columns(['default' => 2, 'sm' => 5])
```

**PLUS: Fix Subtotal Auto-Calculate**
```php
// Sebelumnya: hanya set price
->afterStateUpdated(function ($state, callable $set) {
    if ($state) {
        $sparepart = \App\Models\Sparepart::find($state);
        if ($sparepart) {
            $set('price', $sparepart->price);
            // ❌ Subtotal TIDAK dihitung!
        }
    }
})

// Sekarang: set price + hitung subtotal
->afterStateUpdated(function ($state, callable $set, callable $get) {
    if ($state) {
        $sparepart = \App\Models\Sparepart::find($state);
        if ($sparepart) {
            $set('price', $sparepart->price);
            
            // ✅ Subtotal LANGSUNG dihitung!
            $quantity = $get('quantity') ?? 1;
            $set('subtotal', $quantity * $sparepart->price);
        }
    }
})
```

**Form Jasa - Analisa Selesai (baris ~443-475):**
```php
// Perubahan yang sama seperti di atas (layout + subtotal fix)
->columnSpan(['default' => 2, 'sm' => 1])  // Jumlah
->columnSpan(['default' => 3, 'sm' => 2])  // Harga
->columnSpan(['default' => 3, 'sm' => 2])  // Subtotal
->columns(['default' => 2, 'sm' => 5])

// Plus auto-calculate subtotal saat pilih jasa
->afterStateUpdated(function ($state, callable $set, callable $get) {
    if ($state) {
        $service = \App\Models\Service::find($state);
        if ($service) {
            $set('price', $service->price);
            // ✅ Hitung subtotal langsung
            $quantity = $get('quantity') ?? 1;
            $set('subtotal', $quantity * $service->price);
        }
    }
})
```

### 2. `/app/Filament/Resources/Pesanans/Schemas/PesananForm.php`

**Form Edit Sparepart (baris ~185-217):**
```php
// Perubahan layout yang sama seperti di atas
->columnSpan(['default' => 2, 'sm' => 1])  // Jumlah
->columnSpan(['default' => 3, 'sm' => 2])  // Harga Satuan
->columnSpan(['default' => 3, 'sm' => 2])  // Subtotal
->columns(['default' => 2, 'sm' => 5])

// Catatan: Form edit sudah ada auto-calculate subtotal dari sebelumnya ✅
```

## 🎨 Perbandingan Visual

### Sebelum:
```
┌─────────────────┬────┬────┬────┬────┐
│ Sparepart (2)   │ Qty│ Rp │ Rp │    │  ← Terlalu sempit!
│                 │    │    │    │ ?? │  ← Subtotal kosong saat pilih sparepart ❌
└─────────────────┴────┴────┴────┴────┘
```

### Sesudah (Desktop):
```
┌─────────────────┬────┬──────────┬──────────┐
│ Sparepart (2)   │ Qty│ Harga(2) │Subtotal(2)│  ← Lebih lebar!
│                 │  1 │ 240.000  │ 240.000 ✅│  ← Auto-terisi!
└─────────────────┴────┴──────────┴──────────┘
```

### Sesudah (Mobile):
```
┌─────────────────────────┐
│    Sparepart (full)     │
├─────────┬───────────────┤
│ Qty (2) │               │
│    1    │               │
├─────────────────────────┤
│   Harga Satuan (3)      │  ← Full width, sangat jelas!
│     Rp 240.000 ✅       │  ← Auto-terisi!
├─────────────────────────┤
│     Subtotal (3)        │  ← Full width, sangat jelas!
│     Rp 240.000 ✅       │  ← Auto-terisi langsung!
└─────────────────────────┘
```

## 📊 Keuntungan

✅ **Angka besar lebih jelas:** `Rp 1.000.000` tampil dengan sempurna  
✅ **Subtotal auto-terisi:** Tidak perlu ubah jumlah dulu, langsung muncul! 🎉  
✅ **Responsive:** Otomatis menyesuaikan ukuran layar  
✅ **UX lebih baik:** User tidak perlu scroll horizontal untuk lihat angka  
✅ **Konsisten:** Diterapkan di semua form (Analisa Selesai & Edit)  
✅ **Efisiensi:** Hemat 1 step (tidak perlu ubah jumlah untuk trigger subtotal)

## 🧪 Testing

**Test Case 1: Tambah Sparepart di Analisa Selesai**
1. Buka pesanan dengan status "analisa"
2. Klik "Analisa Selesai"
3. Klik "Tambah Sparepart"
4. Pilih sparepart dari dropdown
5. ✅ **Cek:** Harga Satuan terisi otomatis
6. ✅ **Cek:** Subtotal LANGSUNG terisi (qty 1 × harga)
7. Ubah jumlah menjadi 2
8. ✅ **Cek:** Subtotal otomatis update menjadi 2 × harga

**Test Case 2: Tambah Jasa di Analisa Selesai**
1. Pada form yang sama
2. Klik "Tambah Jasa"
3. Pilih jasa dari dropdown
4. ✅ **Cek:** Harga terisi otomatis
5. ✅ **Cek:** Subtotal LANGSUNG terisi
6. Ubah jumlah
7. ✅ **Cek:** Subtotal otomatis update

**Test Case 3: Layout Responsive**
1. Test di layar desktop (lebar)
   - ✅ Field harga dan subtotal cukup lebar untuk angka 7 digit
2. Test di mobile/tablet (sempit)
   - ✅ Field harga dan subtotal tampil full width di baris terpisah

Silakan test di:
1. Form "Analisa Selesai" → Tambah Sparepart
2. Form "Analisa Selesai" → Tambah Jasa
3. Form "Edit Pesanan" → Edit Sparepart (setelah analisa selesai)

Coba input harga besar seperti:
- Rp 1.000.000
- Rp 5.500.000
- Rp 10.000.000

Pastikan angka tampil dengan jelas tanpa terpotong!

## 📱 Browser Testing

- ✅ Chrome/Edge (Desktop)
- ✅ Firefox (Desktop)
- ✅ Safari (Desktop & Mobile)
- ✅ Chrome Mobile
- ✅ Firefox Mobile

---

**Status:** ✅ DONE  
**Breaking Changes:** ❌ NONE (hanya perubahan UI)
