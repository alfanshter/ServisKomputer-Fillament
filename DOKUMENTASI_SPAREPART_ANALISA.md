# Dokumentasi Fitur Sparepart pada Analisa Selesai

## Deskripsi
Fitur ini memungkinkan teknisi untuk mencatat sparepart yang digunakan saat melakukan analisa pesanan servis. Ketika status pesanan diubah dari "analisa" ke "selesai_analisa", teknisi dapat menginput:
- Catatan hasil analisa
- Solusi yang diberikan
- Biaya servis
- **Sparepart yang digunakan** (baru)
- Foto hasil analisa

## Struktur Database

### Tabel: `pesanan_sparepart` (Pivot Table)
Tabel ini menghubungkan pesanan dengan sparepart yang digunakan.

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | bigint | Primary key |
| pesanan_id | bigint | Foreign key ke tabel pesanans |
| sparepart_id | bigint | Foreign key ke tabel spareparts |
| quantity | integer | Jumlah sparepart yang digunakan |
| price | decimal(12,2) | Harga sparepart saat transaksi |
| subtotal | decimal(12,2) | Total harga (quantity × price) |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

## Relasi Model

### Model Pesanan
```php
public function spareparts()
{
    return $this->belongsToMany(Sparepart::class, 'pesanan_sparepart')
        ->withPivot('quantity', 'price', 'subtotal')
        ->withTimestamps();
}
```

### Model Sparepart
```php
public function pesanans()
{
    return $this->belongsToMany(Pesanan::class, 'pesanan_sparepart')
        ->withPivot('quantity', 'price', 'subtotal')
        ->withTimestamps();
}
```

## Cara Menggunakan

1. **Buka halaman daftar pesanan** di Filament
2. **Klik tombol "Analisa Selesai"** pada pesanan dengan status "analisa"
3. **Isi form yang muncul:**
   - Catatan hasil analisa (required)
   - Catatan Solusi (required)
   - Biaya Servis (optional)
   - **Sparepart yang Digunakan** (optional):
     - Klik "Tambah Sparepart"
     - Pilih sparepart dari dropdown (menampilkan nama, stok, dan harga)
     - Input jumlah yang digunakan
     - Harga satuan akan otomatis terisi
     - Subtotal akan otomatis terhitung
     - Bisa menambah lebih dari 1 sparepart
   - Upload foto hasil analisa (required)
4. **Klik tombol untuk menyimpan**

## Fitur Otomatis

### 1. **Validasi Stok**
Sistem akan mengecek apakah stok sparepart mencukupi sebelum menyimpan. Jika stok tidak cukup, akan muncul notifikasi error dan data tidak akan disimpan.

### 2. **Pengurangan Stok Otomatis**
Ketika sparepart disimpan, stok di tabel `spareparts` akan otomatis berkurang sesuai jumlah yang digunakan.

### 3. **Harga Snapshot**
Harga sparepart yang tersimpan adalah harga pada saat transaksi, bukan harga real-time. Ini untuk mencegah perubahan harga di kemudian hari mempengaruhi data historis.

### 4. **Perhitungan Otomatis**
- Subtotal dihitung otomatis: `quantity × price`
- Field subtotal bersifat reactive, akan update saat quantity atau price berubah

## Filter Sparepart
Dropdown sparepart hanya menampilkan sparepart yang:
- Stok > 0 (tersedia)
- Format: `[Nama Sparepart] - Stok: [Jumlah] - Rp[Harga]`
- Searchable (bisa dicari dengan mengetik)

## Contoh Penggunaan

**Kasus:** Laptop masuk dengan masalah SSD rusak

1. Status: "analisa" → klik "Analisa Selesai"
2. Isi form:
   - Catatan: "SSD tidak terdeteksi, perlu penggantian"
   - Solusi: "Ganti SSD 256GB"
   - Biaya Servis: 100000
   - Tambah Sparepart:
     - Sparepart: "SSD 256GB SATA - Stok: 5 - Rp350000"
     - Jumlah: 1
     - Harga: 350000 (auto-fill)
     - Subtotal: 350000 (auto-calculate)
3. Upload foto
4. Submit

**Hasil:**
- Status berubah ke "selesai_analisa"
- Data sparepart tersimpan di `pesanan_sparepart`
- Stok SSD 256GB berkurang dari 5 menjadi 4
- Foto tersimpan

## Mengakses Data Sparepart

### Di Controller/Resource
```php
// Ambil pesanan dengan sparepart
$pesanan = Pesanan::with('spareparts')->find($id);

// Loop sparepart yang digunakan
foreach ($pesanan->spareparts as $sparepart) {
    echo $sparepart->name;
    echo $sparepart->pivot->quantity;
    echo $sparepart->pivot->price;
    echo $sparepart->pivot->subtotal;
}

// Total biaya sparepart
$totalSparepart = $pesanan->spareparts->sum('pivot.subtotal');
```

## Catatan Penting

1. **Sparepart bersifat optional** - teknisi tidak wajib menambahkan sparepart jika tidak ada pergantian
2. **Stok akan langsung berkurang** - pastikan input jumlah dengan benar
3. **Data tidak bisa diedit** - setelah tersimpan, data sparepart tidak bisa diubah (perlu development tambahan jika ingin fitur edit)
4. **Harga tersimpan permanen** - harga yang tersimpan adalah snapshot, tidak terpengaruh perubahan harga master sparepart

## Pengembangan Selanjutnya (Opsional)

Beberapa fitur yang bisa ditambahkan:
1. Edit sparepart yang sudah tersimpan
2. Hapus sparepart dari pesanan (dengan kembalikan stok)
3. Laporan penggunaan sparepart per periode
4. Notifikasi low stock setelah penggunaan
5. History perubahan harga sparepart
