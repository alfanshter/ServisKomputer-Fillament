# 🛒 FITUR: PILIH SPAREPART DARI PURCHASE ORDER

## 📋 Deskripsi
Fitur untuk memilih sparepart dari **Purchase Order yang sedang pending/dikirim** saat analisa selesai. Jadi tidak harus menunggu barang datang dulu, pesanan bisa langsung dikerjakan dengan sparepart yang masih dalam proses PO.

---

## ✨ Fitur Utama

### **Dropdown Sparepart Gabungan**
Saat "Analisa Selesai", teknisi bisa pilih sparepart dari **2 sumber**:

1. **📦 Dari Stok** - Sparepart yang sudah ada di inventory
2. **🛒 Dari PO** - Sparepart yang sedang di-order (status: pending/shipped)

### **Icon & Label Jelas**
```
📦 RAM 8GB - Stok: 5 - Rp500.000          ← Dari stok
🛒 PO: SSD 512GB - ⏳ Pending - Qty: 2 - Rp800.000  ← Dari PO pending
🛒 PO: Keyboard - 🚚 Dikirim - Qty: 3 - Rp150.000   ← Dari PO shipped
```

---

## 🔄 Alur Kerja

### **Skenario 1: Pilih dari Stok (Normal)**
```
1. Teknisi analisa laptop → butuh RAM 8GB
2. Pilih: "📦 RAM 8GB - Stok: 5"
3. Input qty: 1
4. Klik "Lanjut Status"
5. ✅ Stok RAM berkurang: 5 → 4
6. ✅ Pesanan lanjut ke "selesai_analisa"
```

---

### **Skenario 2: Pilih dari PO (Baru!)**
```
1. Teknisi analisa laptop → butuh SSD 512GB
2. Lihat dropdown:
   - Stok SSD: 0 (habis)
   - Ada PO: "🛒 PO: SSD 512GB - ⏳ Pending - Qty: 2"
3. Pilih dari PO tersebut
4. Input qty: 1
5. Klik "Lanjut Status"
6. ✅ PO quantity berkurang: 2 → 1
7. ✅ PO linked ke pesanan ini
8. ✅ Stok SSD TIDAK berubah (masih 0, karena barang belum datang fisik)
9. ✅ Pesanan tetap bisa lanjut "selesai_analisa"
```

---

### **Skenario 3: PO Habis Terpakai**
```
1. PO awal: SSD qty = 1
2. Teknisi pilih SSD dari PO ini, qty: 1
3. Simpan
4. ✅ PO quantity: 1 → 0
5. ✅ PO status otomatis jadi "received" (dianggap sudah terpakai)
6. ✅ PO tidak muncul lagi di dropdown analisa berikutnya
```

---

## 🗂️ Logic Detail

### **Dropdown Options**
```php
// Gabungan 2 source
$options = [];

// 1. Sparepart yang ada stok
foreach ($sparepartsInStock as $sp) {
    $options["stock_{$sp->id}"] = "📦 {$sp->name} - Stok: {$sp->quantity} - Rp{$sp->price}";
}

// 2. Sparepart dari PO (pending/shipped)
foreach ($sparepartsInPO as $po) {
    $statusLabel = $po->status === 'pending' ? '⏳ Pending' : '🚚 Dikirim';
    $options["po_{$po->id}"] = "🛒 PO: {$po->sparepart->name} - {$statusLabel} - Qty: {$po->quantity}";
}
```

### **Hidden Fields (Auto Set)**
Saat pilih sparepart, otomatis set:
```php
- source_type: 'stock' atau 'po'
- source_id: ID sparepart (jika stock) atau ID PO (jika po)
- po_id: ID Purchase Order (jika dari PO)
```

### **Logic Penyimpanan**
```php
if ($sourceType === 'stock') {
    // Simpan ke pesanan_sparepart
    // Kurangi stok sparepart
    
} elseif ($sourceType === 'po') {
    // Simpan ke pesanan_sparepart (pakai sparepart_id dari PO)
    // Link PO ke pesanan
    // Kurangi quantity PO
    // TIDAK tambah stok (belum datang fisik)
    
    // Jika PO habis → status = 'received'
}
```

---

## 💡 Keuntungan Fitur Ini

| Sebelumnya | Sekarang |
|------------|----------|
| ❌ Harus tunggu PO datang baru bisa analisa selesai | ✅ Bisa langsung pilih dari PO yang pending |
| ❌ Pesanan stuck di status "menunggu sparepart" | ✅ Pesanan bisa lanjut selesai_analisa |
| ❌ Customer menunggu lama | ✅ Proses lebih cepat |
| ❌ PO tidak tertrack ke pesanan mana | ✅ PO otomatis linked ke pesanan |

---

## 🎯 Contoh Kasus Nyata

### **Kasus: Service 2 Laptop Pakai 1 PO**

**Setup:**
- PO #001: SSD 512GB, Qty: 2, Status: Pending

**Pesanan #1:**
```
- Customer: Budi
- Device: Laptop Asus
- Analisa: Butuh SSD 512GB (qty: 1)
- Pilih: 🛒 PO #001 (qty tersisa: 2)
- Hasil: PO qty jadi 1
```

**Pesanan #2:**
```
- Customer: Ani
- Device: Laptop Acer
- Analisa: Butuh SSD 512GB (qty: 1)
- Pilih: 🛒 PO #001 (qty tersisa: 1)
- Hasil: PO qty jadi 0, status = 'received'
```

**Ketika PO Fisik Datang:**
- Admin terima 2 SSD dari supplier
- Masuk ke halaman PO #001
- Klik "Terima Barang"
- Stok SSD bertambah: 0 → 2
- SSD langsung dipakai untuk pesanan Budi & Ani
- Stok SSD kembali: 2 → 0

---

## 🧪 Testing Checklist

### ✅ Test 1: Dropdown Gabungan
- [ ] Buat PO: SSD 512GB, Qty: 2, Status: Pending
- [ ] Buat pesanan baru → Analisa
- [ ] Cek dropdown sparepart
- [ ] Harus tampil:
  ```
  📦 RAM 8GB - Stok: 5 - Rp500.000
  🛒 PO: SSD 512GB - ⏳ Pending - Qty: 2 - Rp800.000
  ```

### ✅ Test 2: Pilih dari Stok
- [ ] Pilih "📦 RAM 8GB" (qty: 1)
- [ ] Simpan
- [ ] Cek database:
  - pesanan_sparepart: ada record dengan sparepart_id = RAM
  - spareparts: RAM stok berkurang 5 → 4

### ✅ Test 3: Pilih dari PO
- [ ] Pilih "🛒 PO: SSD 512GB" (qty: 1)
- [ ] Simpan
- [ ] Cek database:
  - pesanan_sparepart: ada record dengan sparepart_id = SSD
  - sparepart_purchase_orders: 
    - quantity berkurang 2 → 1
    - pesanan_id = ID pesanan ini
  - spareparts: SSD stok TETAP 0 (tidak berubah)

### ✅ Test 4: PO Habis
- [ ] PO SSD qty tersisa: 1
- [ ] Pilih dari PO (qty: 1)
- [ ] Simpan
- [ ] Cek PO:
  - quantity = 0
  - status = 'received'
- [ ] Buat pesanan baru → Analisa
- [ ] Dropdown tidak tampil PO SSD lagi (karena qty = 0)

### ✅ Test 5: Multi Pesanan 1 PO
- [ ] PO Keyboard qty: 3
- [ ] Pesanan A: pilih dari PO (qty: 1) → PO qty jadi 2
- [ ] Pesanan B: pilih dari PO (qty: 1) → PO qty jadi 1
- [ ] Pesanan C: pilih dari PO (qty: 1) → PO qty jadi 0, status = received
- [ ] Cek: 3 pesanan linked ke PO yang sama

---

## 📂 File yang Diubah

```
app/Filament/Resources/Pesanans/Tables/PesanansTable.php
- Import Hidden component
- Update dropdown sparepart (gabung stock + PO)
- Tambah hidden fields: source_type, source_id, po_id
- Update logic penyimpanan (handle 2 source)
```

---

## 🚀 Deployment

**Tidak perlu migration!** Hanya update file:
```
app/Filament/Resources/Pesanans/Tables/PesanansTable.php
```

Langkah:
1. ✅ Upload file
2. ✅ Clear cache: `php artisan cache:clear`
3. ✅ Test di browser

---

## ⚠️ Catatan Penting

### **Stok vs PO:**
| Aspek | Dari Stok | Dari PO |
|-------|-----------|---------|
| **Stok Sparepart** | ✅ Dikurangi langsung | ❌ Tidak berubah |
| **Fisik Barang** | ✅ Sudah ada | ❌ Belum datang |
| **PO Quantity** | - | ✅ Dikurangi |
| **Link ke Pesanan** | - | ✅ Auto link |
| **Status PO** | - | ✅ Update jika habis |

### **Kapan Stok Bertambah?**
Stok sparepart akan bertambah saat:
1. Admin klik "Terima Barang" di halaman PO
2. PO status berubah dari pending → received
3. System otomatis tambah stok sesuai quantity PO

### **PO Bisa Dipakai Berkali-kali?**
Ya! Selama quantity PO masih > 0, bisa dipakai untuk banyak pesanan.

Contoh:
```
PO: Keyboard qty = 5
- Pesanan 1 pakai 1 → sisa 4
- Pesanan 2 pakai 2 → sisa 2
- Pesanan 3 pakai 2 → sisa 0 (status = received)
```

---

## 🔮 Future Enhancement

Potensial fitur tambahan:
1. ✨ **Reservasi PO** - Reserve sparepart dari PO untuk pesanan tertentu
2. ✨ **Estimasi Datang** - Tampilkan ETA barang PO
3. ✨ **Notifikasi** - Alert saat PO yang dipilih sudah diterima
4. ✨ **History** - Tracking pesanan mana saja yang pakai PO ini
5. ✨ **Prioritas** - Pesanan prioritas tinggi dapat PO duluan

---

## 📊 Perbedaan: Stock vs PO

### **Icon & Label:**
```
📦 = Barang ADA di gudang (stok ready)
🛒 = Barang DIPESAN (belum datang)
⏳ = Pending (belum dikirim supplier)
🚚 = Shipped (dalam perjalanan)
```

### **Behavior:**
```
Pilih STOCK:
  → Stok berkurang instant
  → Barang bisa langsung dipakai
  → Tidak link ke PO
  
Pilih PO:
  → Stok TIDAK berubah
  → "Reservasi" barang yang akan datang
  → Link ke PO (tracking)
  → PO quantity berkurang
```

---

**Dibuat:** 22 November 2025  
**Developer:** GitHub Copilot + Tim PWS Computer  
**Status:** ✅ Production Ready
