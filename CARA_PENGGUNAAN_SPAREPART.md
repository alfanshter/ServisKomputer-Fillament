# 📋 Cara Penggunaan Fitur Sparepart pada Analisa Selesai

## 🎯 Tujuan
Fitur ini memudahkan teknisi untuk mencatat sparepart yang digunakan saat melakukan servis, sehingga:
- **Stok otomatis berkurang** saat sparepart digunakan
- **Harga tersimpan permanen** untuk keperluan laporan
- **Data terorganisir** untuk analisa bisnis

---

## 🔧 Langkah-Langkah Penggunaan

### 1️⃣ Buka Halaman Pesanan
- Masuk ke menu **Pesanans** di Filament
- Cari pesanan dengan status **"analisa"**

### 2️⃣ Klik Tombol "Analisa Selesai"
- Pada kolom aksi, klik tombol **"Analisa Selesai"** (ikon panah kanan)
- Modal form akan muncul

### 3️⃣ Isi Form Analisa
**Field yang wajib diisi:**
- ✅ **Catatan hasil analisa**: Jelaskan hasil pemeriksaan
- ✅ **Catatan Solusi**: Tuliskan solusi yang diberikan
- ✅ **Foto Analisa**: Upload minimal 1 foto

**Field opsional:**
- 💰 **Biaya Servis**: Input biaya jasa servis (tanpa sparepart)
- 🔩 **Sparepart yang Digunakan**: (dijelaskan di bawah)

---

## 🔩 Cara Menambahkan Sparepart

### Jika TIDAK ada pergantian sparepart:
- **Lewati saja** bagian "Sparepart yang Digunakan"
- Langsung isi field lain dan submit

### Jika ADA pergantian sparepart:

#### Step 1: Klik "Tambah Sparepart"
- Di bagian **"Sparepart yang Digunakan"**, klik tombol **"+ Tambah Sparepart"**
- Form baru akan muncul

#### Step 2: Pilih Sparepart
- Klik dropdown **"Sparepart"**
- Pilih sparepart yang digunakan
- Format dropdown: `Nama Sparepart - Stok: X - Rp XXX.XXX`
- Anda bisa **mengetik untuk mencari** (searchable)

#### Step 3: Harga Otomatis Terisi
- Setelah memilih sparepart, field **"Harga Satuan"** akan **otomatis terisi**
- Anda bisa mengubah harga jika ada diskon/harga khusus

#### Step 4: Input Jumlah
- Isi field **"Jumlah"** sesuai kebutuhan
- Default: 1
- Minimal: 1

#### Step 5: Subtotal Otomatis Terhitung
- Field **"Subtotal"** akan **otomatis terhitung**: `Jumlah × Harga Satuan`
- Field ini tidak bisa diedit manual (read-only)

#### Step 6: Tambah Sparepart Lain (Opsional)
- Jika ada lebih dari 1 sparepart, klik lagi **"+ Tambah Sparepart"**
- Ulangi step 2-5
- Bisa menambahkan **banyak sparepart** sekaligus

---

## ✅ Submit Form

Setelah semua terisi:
1. Review semua data
2. Klik tombol **Submit/Simpan**
3. Sistem akan:
   - ✅ Mengecek stok sparepart
   - ✅ Menyimpan data ke database
   - ✅ Mengurangi stok sparepart otomatis
   - ✅ Mengubah status pesanan ke **"selesai_analisa"**
   - ✅ Menyimpan history perubahan status

---

## ⚠️ Validasi dan Error

### ❌ Stok Tidak Mencukupi
**Kondisi:** Jumlah yang diinput > stok tersedia

**Pesan error:** 
```
Stok [Nama Sparepart] tidak mencukupi!
```

**Solusi:** 
- Kurangi jumlah yang diinput
- Atau tambah stok sparepart terlebih dahulu
- Atau pilih sparepart lain

### ❌ Foto Wajib Diupload
**Kondisi:** Tidak ada foto yang diupload

**Pesan error:**
```
Foto Analisa wajib diunggah!
```

**Solusi:** Upload minimal 1 foto

---

## 📊 Contoh Kasus Nyata

### Kasus 1: Ganti SSD Laptop
**Masalah:** Laptop tidak bisa booting, SSD rusak

**Pengisian Form:**
```
📝 Catatan hasil analisa:
"SSD tidak terdeteksi oleh BIOS, sudah dicoba di laptop lain juga tidak terbaca. SSD rusak total."

📝 Catatan Solusi:
"Ganti SSD 256GB baru + install ulang Windows 10"

💰 Biaya Servis: Rp 100.000

🔩 Sparepart yang Digunakan:
- Sparepart: "SSD 256GB SATA Kingston - Stok: 5 - Rp350.000"
- Jumlah: 1
- Harga Satuan: Rp 350.000 (auto-fill)
- Subtotal: Rp 350.000 (auto-calculate)

📸 Foto Analisa: [upload foto SSD rusak]
```

**Hasil:**
- Total biaya: Rp 100.000 (servis) + Rp 350.000 (sparepart) = **Rp 450.000**
- Stok SSD 256GB: 5 → **4**
- Status: analisa → **selesai_analisa**

---

### Kasus 2: Ganti RAM dan Fan Laptop
**Masalah:** Laptop lemot dan panas

**Pengisian Form:**
```
📝 Catatan hasil analisa:
"RAM hanya 4GB, sering penuh. Fan berdebu dan berisik."

📝 Catatan Solusi:
"Upgrade RAM 8GB + bersihkan fan + ganti thermal paste"

💰 Biaya Servis: Rp 150.000

🔩 Sparepart yang Digunakan:

[Sparepart 1]
- Sparepart: "RAM DDR4 8GB Corsair - Stok: 3 - Rp450.000"
- Jumlah: 1
- Harga Satuan: Rp 450.000
- Subtotal: Rp 450.000

[Sparepart 2]
- Sparepart: "Thermal Paste Arctic MX-4 - Stok: 10 - Rp50.000"
- Jumlah: 1
- Harga Satuan: Rp 50.000
- Subtotal: Rp 50.000

📸 Foto Analisa: [upload foto RAM lama dan fan kotor]
```

**Hasil:**
- Total biaya: Rp 150.000 + Rp 450.000 + Rp 50.000 = **Rp 650.000**
- Stok RAM DDR4 8GB: 3 → **2**
- Stok Thermal Paste: 10 → **9**
- Status: analisa → **selesai_analisa**

---

### Kasus 3: Hanya Cleaning (Tanpa Sparepart)
**Masalah:** Laptop lambat, penuh debu

**Pengisian Form:**
```
📝 Catatan hasil analisa:
"Laptop penuh debu di heatsink dan fan. Tidak ada kerusakan hardware."

📝 Catatan Solusi:
"Deep cleaning + optimasi software"

💰 Biaya Servis: Rp 75.000

🔩 Sparepart yang Digunakan:
[KOSONG - tidak perlu tambah sparepart]

📸 Foto Analisa: [upload foto sebelum cleaning]
```

**Hasil:**
- Total biaya: **Rp 75.000**
- Tidak ada perubahan stok
- Status: analisa → **selesai_analisa**

---

## 💡 Tips dan Trik

### ✨ Searchable Dropdown
- Ketik nama sparepart untuk mencari lebih cepat
- Contoh: ketik "ssd" untuk filter semua SSD
- Contoh: ketik "ram" untuk filter semua RAM

### 🔢 Harga Custom
- Harga bisa diubah manual jika:
  - Ada diskon khusus
  - Harga promo
  - Harga nego dengan customer
- Harga yang tersimpan adalah harga **snapshot** saat transaksi

### 📦 Cek Stok Sebelum Input
- Lihat stok di dropdown: `Nama - Stok: X - Harga`
- Jika stok 0, sparepart tidak muncul di dropdown
- Harus isi stok dulu di menu Spareparts

### 🗑️ Hapus Sparepart yang Salah Input
- Sebelum submit: klik tombol **"X"** di samping item sparepart
- Setelah submit: **tidak bisa dihapus** (perlu development tambahan)

---

## 🚨 Catatan Penting

⚠️ **Stok akan langsung berkurang setelah submit!**
- Pastikan input jumlah dengan benar
- Tidak bisa undo/cancel setelah submit
- Jika salah input, harus manual edit database atau buat fitur edit

⚠️ **Harga tersimpan permanen**
- Harga yang tersimpan adalah harga saat transaksi
- Jika harga master sparepart berubah, tidak mempengaruhi data lama
- Berguna untuk laporan historis yang akurat

⚠️ **Data sparepart tidak bisa diedit (untuk saat ini)**
- Setelah submit, data tidak bisa diubah
- Perlu development tambahan jika ingin fitur edit
- Alternatif: hapus dan input ulang (perlu development)

---

## 📞 Bantuan

Jika mengalami masalah:
1. **Stok tidak muncul** → Cek di menu Spareparts, pastikan stok > 0
2. **Error saat submit** → Cek notifikasi error, biasanya stok tidak cukup
3. **Harga tidak otomatis** → Refresh halaman atau pilih ulang sparepart
4. **Subtotal tidak update** → Input ulang jumlah atau harga

---

## 🎓 Kesimpulan

Fitur ini memudahkan:
- ✅ Pencatatan sparepart yang akurat
- ✅ Pengurangan stok otomatis
- ✅ Tracking biaya per pesanan
- ✅ Laporan penggunaan sparepart
- ✅ Data historis yang terjaga

**Selamat menggunakan! 🚀**
