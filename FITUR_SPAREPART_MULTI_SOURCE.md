# Fitur Sparepart Multi-Source (Stok + Purchase Order)

## 📋 Overview
Fitur ini memungkinkan teknisi untuk memilih sparepart dari **2 sumber** saat melakukan "Analisa Selesai":
1. **📦 Stok Gudang** - Sparepart yang tersedia di warehouse (quantity > 0)
2. **🛒 Purchase Order** - Sparepart yang sedang dalam proses pemesanan (status: pending/shipped)

## 🎯 Tujuan
- Memberikan visibility penuh ke teknisi tentang availability sparepart
- Memungkinkan konfirmasi ke client bahwa sparepart sedang dalam perjalanan (dari PO)
- Mencegah order baru untuk sparepart yang sudah ada di PO
- Fleksibilitas dalam menggunakan stok yang ada vs yang akan datang

## 🔧 Cara Kerja

### 1. Dropdown Sparepart
Saat klik "Analisa Selesai" dan pilih sparepart, dropdown akan menampilkan:

```
📦 RAM 8GB - Stok: 5 - Rp500.000           (dari spareparts table)
📦 SSD 512GB - Stok: 2 - Rp1.200.000       (dari spareparts table)
🛒 PO: HDD 1TB - ⏳ Pending - Qty: 3 - Rp800.000    (dari purchase_orders)
🛒 PO: Keyboard - 🚚 Dikirim - Qty: 1 - Rp150.000   (dari purchase_orders)
```

**Icon Legend:**
- 📦 = Tersedia di gudang
- 🛒 = Dari Purchase Order
- ⏳ = Status Pending
- 🚚 = Status Shipped/Dikirim

### 2. Data Structure
Setiap item sparepart di repeater memiliki hidden fields:
- `source_type`: 'stock' atau 'po'
- `source_id`: ID dari sparepart (jika stock) atau PO (jika po)
- `po_id`: ID Purchase Order (hanya jika source_type = 'po')

### 3. Save Logic

#### A. Jika pilih dari STOK (📦):
```php
1. Attach ke pivot table: pesanan_sparepart
2. Kurangi quantity di tabel spareparts
3. Update total_cost pesanan
```

#### B. Jika pilih dari PO (🛒):
```php
1. Kurangi quantity di tabel sparepart_purchase_orders
2. Attach ke pivot table: pesanan_purchase_order_items
3. Jika PO quantity = 0, update status PO menjadi 'used'
4. TIDAK kurangi sparepart.quantity (karena barang belum masuk gudang)
```

## 📊 Database Schema

### Tabel yang Terlibat:
1. **spareparts** - Master sparepart dengan quantity stok
2. **sparepart_purchase_orders** - PO sparepart dengan status
3. **pesanan_sparepart** - Pivot: pesanan ↔ sparepart (untuk stok gudang)
4. **pesanan_purchase_order_items** - Pivot: pesanan ↔ PO items

## 💡 Use Case

### Scenario 1: Stok Tersedia
```
Teknisi: "Ganti RAM 8GB"
Sistem: Menampilkan "📦 RAM 8GB - Stok: 5"
Teknisi: Pilih dari stok → langsung dikurangi dari gudang
```

### Scenario 2: Stok Habis, Ada di PO
```
Teknisi: "Butuh SSD 512GB"
Sistem: Tidak ada stok, tapi ada "🛒 PO: SSD 512GB - ⏳ Pending - Qty: 2"
Teknisi: Pilih dari PO → konfirmasi ke client bahwa barang sedang dipesan
Client: OK, tunggu barang datang
```

### Scenario 3: Stok Habis, Tidak Ada di PO
```
Teknisi: "Butuh motherboard"
Sistem: Tidak muncul di dropdown (karena qty = 0 dan tidak ada PO)
Teknisi: Buat PO baru dulu, baru bisa pilih dari PO
```

## 🔐 Business Rules

1. **Dropdown hanya menampilkan:**
   - Sparepart dengan qty > 0 (dari stok), ATAU
   - PO dengan status 'pending' atau 'shipped'

2. **Validasi stok:**
   - Jika pilih dari stok: quantity harus tersedia
   - Jika pilih dari PO: quantity PO harus cukup

3. **Status PO:**
   - 'pending' → masih bisa dipilih
   - 'shipped' → masih bisa dipilih
   - 'received' → tidak muncul di dropdown (sudah masuk gudang)
   - 'used' → tidak muncul (sudah dipakai semua)

4. **Inventory tracking:**
   - Stok gudang: real-time decrement
   - PO: tracking via pivot table, status auto-update

## 📝 Code Files Modified

### 1. PesanansTable.php
**Lokasi:** `app/Filament/Resources/Pesanans/Tables/PesanansTable.php`

**Changes:**
- Line ~300-370: Dropdown options generation (stock + PO)
- Line ~370-390: afterStateUpdated logic (detect source type)
- Line ~918-964: Save logic (handle both sources)

**Key Functions:**
```php
Select::make('sparepart_id')
    ->options(function () {
        // Combine stock + PO options
    })
    ->afterStateUpdated(function ($state, callable $set) {
        // Set hidden fields based on source
    })
```

### 2. Pesanan.php (Model)
**Existing Relationships:**
- `spareparts()` - for stock items
- `purchaseOrderItems()` - for PO items

## 🧪 Testing Checklist

- [ ] Dropdown menampilkan stok dengan icon 📦
- [ ] Dropdown menampilkan PO dengan icon 🛒 dan status
- [ ] Select dari stok → quantity berkurang di spareparts table
- [ ] Select dari PO → quantity berkurang di PO, pivot table terisi
- [ ] PO dengan quantity 0 → status berubah menjadi 'used'
- [ ] Total cost calculation tetap benar
- [ ] WhatsApp template menampilkan sparepart yang dipilih

## 🚀 Future Improvements

1. **Notification saat PO received:**
   - Ketika PO status = 'received', notifikasi ke teknisi yang terkait

2. **Auto-transfer PO to stock:**
   - Saat PO received, otomatis masukkan ke spareparts.quantity

3. **Reservasi PO:**
   - Lock PO quantity yang sudah di-assign ke pesanan tertentu

4. **Dashboard visibility:**
   - Grafik: stok vs PO vs reserved

## 👤 User Story

**Sebagai teknisi**, saya ingin:
- Melihat sparepart yang tersedia di gudang
- Melihat sparepart yang sedang dalam pemesanan
- Bisa konfirmasi ke client bahwa "sparepart sedang dipesan, estimasi X hari"
- Tidak perlu order ulang jika sudah ada di PO

**Agar:**
- Lebih transparan ke customer
- Inventory management lebih akurat
- Tidak ada duplikasi order

---

**Created:** 2025-01-22  
**Last Updated:** 2025-01-22  
**Status:** ✅ Active  
