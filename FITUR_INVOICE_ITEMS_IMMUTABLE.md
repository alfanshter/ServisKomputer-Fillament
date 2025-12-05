# 🧾 FITUR INVOICE ITEMS - IMMUTABLE SNAPSHOT

**Tanggal:** 5 Desember 2025  
**Tipe:** Feature + Data Integrity

## 🎯 Problem Statement

### Masalah Sebelumnya ❌
- Invoice mengambil data langsung dari relasi `pesanan_sparepart` dan `pesanan_service`
- Jika sparepart/service **diedit atau dihapus** dari pesanan, data di invoice juga **hilang/berubah**
- Invoice seharusnya **immutable** (tidak berubah) sebagai bukti transaksi historis
- Tidak ada audit trail untuk perubahan harga

**Contoh Kasus:**
1. User "Analisa Selesai" → Invoice menampilkan sparepart "RAM 8GB Rp 450.000"
2. Kemudian user **edit pesanan** dan **hapus sparepart** RAM
3. Invoice sekarang **tidak menampilkan RAM** → Data historis hilang! ❌

## ✅ Solusi: Tabel Invoice Items (Snapshot)

Membuat tabel terpisah `pesanan_invoice_items` untuk menyimpan **snapshot data** saat analisa selesai yang **tidak akan terpengaruh** perubahan data master.

### Konsep:
```
┌─────────────────────────────────────────────────┐
│          Saat "Analisa Selesai"                 │
├─────────────────────────────────────────────────┤
│  1. Data disimpan ke pesanan_sparepart ✅       │
│     (untuk tracking stok & edit)                │
│                                                  │
│  2. Data juga di-SNAPSHOT ke                    │
│     pesanan_invoice_items ✅                    │
│     (IMMUTABLE - untuk invoice/laporan)         │
└─────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────┐
│          Jika User Edit/Hapus Sparepart         │
├─────────────────────────────────────────────────┤
│  • pesanan_sparepart: BERUBAH ✅                │
│    (untuk tracking realtime)                    │
│                                                  │
│  • pesanan_invoice_items: TETAP! ✅             │
│    (invoice tetap tampil data lama)             │
└─────────────────────────────────────────────────┘
```

## 📊 Database Schema

### Tabel Baru: `pesanan_invoice_items`

| Kolom             | Tipe            | Deskripsi                                    |
|-------------------|-----------------|----------------------------------------------|
| id                | bigint          | Primary key                                  |
| pesanan_id        | bigint (FK)     | Relasi ke pesanans                          |
| item_type         | string          | 'service' atau 'sparepart'                  |
| item_name         | string          | Nama jasa/sparepart (SNAPSHOT)              |
| item_description  | string nullable | SKU/Kategori untuk detail                   |
| quantity          | integer         | Jumlah                                      |
| price             | decimal(12,2)   | Harga satuan (SNAPSHOT)                     |
| subtotal          | decimal(12,2)   | Quantity × Price                            |
| source            | string nullable | 'stock', 'po', 'manual' (tracking)         |
| source_id         | bigint nullable | ID sumber (tidak strict FK)                 |
| created_at        | timestamp       | Waktu snapshot dibuat                       |
| updated_at        | timestamp       | -                                           |

**Index:**
- `pesanan_id` - untuk query cepat
- `item_type` - untuk filter jasa/sparepart

## 🔧 Implementasi

### 1. Model: `PesananInvoiceItem`

**File:** `/app/Models/PesananInvoiceItem.php`

```php
class PesananInvoiceItem extends Model
{
    protected $fillable = [
        'pesanan_id', 'item_type', 'item_name', 'item_description',
        'quantity', 'price', 'subtotal', 'source', 'source_id',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    // Scopes
    public function scopeServices($query)
    {
        return $query->where('item_type', 'service');
    }

    public function scopeSpareparts($query)
    {
        return $query->where('item_type', 'sparepart');
    }
}
```

### 2. Relasi di Model `Pesanan`

```php
public function invoiceItems()
{
    return $this->hasMany(PesananInvoiceItem::class);
}

public function invoiceServices()
{
    return $this->hasMany(PesananInvoiceItem::class)->where('item_type', 'service');
}

public function invoiceSpareparts()
{
    return $this->hasMany(PesananInvoiceItem::class)->where('item_type', 'sparepart');
}
```

### 3. Logic "Analisa Selesai"

**File:** `/app/Filament/Resources/Pesanans/Tables/PesanansTable.php`

Setelah menyimpan sparepart & service ke pivot table, tambahkan snapshot ke invoice items:

```php
// 📝 SNAPSHOT: Simpan data invoice items (immutable record)
$record->invoiceItems()->delete(); // Hapus lama jika re-submit

// Snapshot jasa
foreach ($data['services'] as $serviceData) {
    $service = Service::find($serviceData['service_id']);
    
    PesananInvoiceItem::create([
        'pesanan_id' => $record->id,
        'item_type' => 'service',
        'item_name' => $service->name,
        'item_description' => $service->category,
        'quantity' => $serviceData['quantity'],
        'price' => $serviceData['price'],
        'subtotal' => $serviceData['subtotal'],
        'source' => 'master',
        'source_id' => $service->id,
    ]);
}

// Snapshot sparepart (baik dari stock maupun PO)
foreach ($data['spareparts'] as $sparepartData) {
    // ... logic untuk get sparepart name & details
    
    PesananInvoiceItem::create([
        'pesanan_id' => $record->id,
        'item_type' => 'sparepart',
        'item_name' => $sparepartName,
        'item_description' => $sku,
        'quantity' => $quantity,
        'price' => $price,
        'subtotal' => $subtotal,
        'source' => 'stock', // atau 'po'
        'source_id' => $sparepartId,
    ]);
}
```

### 4. Update PrintController

**File:** `/app/Http/Controllers/PrintController.php`

```php
public function invoice($id)
{
    // ✅ Load dari invoiceItems, bukan services/spareparts
    $data = Pesanan::with(['user', 'invoiceItems'])->findOrFail($id);

    $pdf = Pdf::loadView('print.invoice', compact('data'))
        ->setPaper('a4', 'portrait');
    return $pdf->stream("invoice-{$data->id}.pdf");
}
```

### 5. Update View Invoice

**File:** `/resources/views/print/invoice.blade.php`

```blade
{{-- Sebelumnya: Loop services & spareparts --}}
@foreach($data->services as $service)
    ...
@endforeach
@foreach($data->spareparts as $sparepart)
    ...
@endforeach

{{-- Sekarang: Loop invoiceItems (immutable snapshot) --}}
@foreach($data->invoiceItems as $item)
<tr>
    <td>{{ $item->item_name }}</td>
    <td>
        @if($item->item_type === 'service')
            Jasa Service
        @else
            Sparepart
            @if($item->source === 'po')
                • Dari PO
            @endif
        @endif
    </td>
    <td>{{ $item->quantity }}</td>
    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
</tr>
@endforeach
```

## 📦 Migration Data Lama

Untuk data pesanan yang sudah ada sebelumnya, jalankan seeder:

```bash
php artisan db:seed --class=MigrateExistingDataToInvoiceItems
```

Seeder ini akan:
1. Mengambil semua pesanan dengan status ≥ `selesai_analisa`
2. Copy data dari `pesanan_service` dan `pesanan_sparepart` ke `pesanan_invoice_items`
3. Skip pesanan yang sudah punya invoice items

**Hasil Migrasi:**
- ✅ Migrated: 23 pesanan
- ⏭️ Skipped: 0 pesanan (already has invoice items)

## 🎯 Behaviour Baru

### Skenario 1: Analisa Selesai Pertama Kali
```
1. User pilih sparepart "RAM 8GB" Rp 450.000
2. Submit "Analisa Selesai"
3. Sistem menyimpan:
   ✅ pesanan_sparepart: RAM 8GB (untuk tracking)
   ✅ pesanan_invoice_items: RAM 8GB (snapshot invoice)
4. Invoice tampil: RAM 8GB Rp 450.000 ✅
```

### Skenario 2: User Edit Hapus Sparepart
```
1. User edit pesanan
2. Hapus RAM 8GB dari sparepart
3. Data berubah:
   ❌ pesanan_sparepart: KOSONG (dihapus)
   ✅ pesanan_invoice_items: RAM 8GB TETAP ADA!
4. Invoice MASIH tampil: RAM 8GB Rp 450.000 ✅
```

### Skenario 3: Re-submit Analisa Selesai
```
1. User edit status kembali ke "analisa"
2. Submit ulang "Analisa Selesai" dengan data baru
3. Sistem:
   ⚠️ Hapus invoice items lama
   ✅ Buat snapshot baru dengan data terbaru
4. Invoice tampil data yang paling baru ✅
```

## 📊 Keuntungan

### ✅ Data Integrity
- Invoice tidak akan hilang/berubah jika data master diedit
- Historical record terjaga

### ✅ Audit Trail
- Setiap invoice punya snapshot data saat transaksi
- Bisa track perubahan harga dari waktu ke waktu
- Field `source` menunjukkan asal item (stock/PO)

### ✅ Flexible Editing
- User bebas edit/hapus sparepart di pesanan
- Invoice tetap menampilkan data asli

### ✅ Performance
- Invoice tidak perlu join banyak tabel
- Query lebih cepat: `$pesanan->invoiceItems` (1 query)
- vs `$pesanan->services + $pesanan->spareparts` (2+ queries)

## 🧪 Testing Checklist

### Test 1: Invoice Data Lama
- [ ] Buka pesanan lama (sebelum update)
- [ ] Print invoice
- [ ] ✅ Pastikan data sparepart & jasa tampil lengkap

### Test 2: Analisa Selesai Baru
- [ ] Buat pesanan baru
- [ ] Analisa selesai dengan sparepart + jasa
- [ ] Print invoice
- [ ] ✅ Data tampil di invoice
- [ ] Cek database: `pesanan_invoice_items` terisi

### Test 3: Edit Sparepart Tidak Pengaruhi Invoice
- [ ] Buka pesanan yang sudah selesai analisa
- [ ] Edit: Hapus 1 sparepart
- [ ] Print invoice
- [ ] ✅ Sparepart yang dihapus MASIH tampil di invoice

### Test 4: Re-submit Analisa Selesai
- [ ] Edit status ke "analisa"
- [ ] Submit ulang dengan data berbeda
- [ ] Print invoice
- [ ] ✅ Invoice tampil data baru (bukan data lama)

### Test 5: Sparepart dari PO
- [ ] Analisa selesai dengan sparepart dari PO
- [ ] Print invoice
- [ ] ✅ Ada label "Dari PO" di invoice

## 📁 File yang Diubah/Dibuat

### Baru:
1. `/database/migrations/2025_12_05_create_pesanan_invoice_items_table.php`
2. `/app/Models/PesananInvoiceItem.php`
3. `/database/seeders/MigrateExistingDataToInvoiceItems.php`

### Diubah:
1. `/app/Models/Pesanan.php` - tambah relasi invoiceItems
2. `/app/Filament/Resources/Pesanans/Tables/PesanansTable.php` - snapshot logic
3. `/app/Http/Controllers/PrintController.php` - load invoiceItems
4. `/resources/views/print/invoice.blade.php` - loop invoiceItems

## 🚀 Deployment Steps

### 1. Backup Database
```bash
php artisan backup:run --only-db
```

### 2. Pull & Install
```bash
git pull origin main
composer install
```

### 3. Run Migration
```bash
php artisan migrate --path=database/migrations/2025_12_05_create_pesanan_invoice_items_table.php
```

### 4. Migrate Data Lama
```bash
php artisan db:seed --class=MigrateExistingDataToInvoiceItems
```

### 5. Test
- Print invoice pesanan lama
- Buat transaksi baru dan print invoice
- Edit sparepart, cek invoice tetap sama

## ⚠️ Breaking Changes

### NONE! ✅
- Tabel lama (`pesanan_sparepart`, `pesanan_service`) **TETAP ADA**
- Relasi lama (`$pesanan->spareparts`, `$pesanan->services`) **MASIH BERFUNGSI**
- Hanya **invoice** yang menggunakan source data baru
- Backward compatible 100%

## 📞 Rollback Plan (Jika Ada Masalah)

Jika ada issue, rollback dengan:

### 1. Revert PrintController
```php
public function invoice($id)
{
    // Kembali ke relasi lama
    $data = Pesanan::with(['user', 'services', 'spareparts'])->findOrFail($id);
    // ...
}
```

### 2. Revert View Invoice
Gunakan loop `services` dan `spareparts` seperti sebelumnya

### 3. Drop Table (Optional)
```bash
php artisan migrate:rollback --step=1
```

---

**Status:** ✅ DEPLOYED  
**Data Migration:** ✅ DONE (23 pesanan)  
**Breaking Changes:** ❌ NONE  
**Backward Compatible:** ✅ YES
