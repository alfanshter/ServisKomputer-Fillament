# 📋 Fitur Modal Pemilihan Pelanggan Lama

## 📝 Deskripsi
Fitur ini memungkinkan Anda untuk memilih pelanggan lama melalui modal/halaman khusus dengan search yang mudah, bukan langsung di dropdown form seperti sebelumnya.

## 🎯 Cara Kerja

### Alur Pembuatan Pesanan Baru:

1. **Dari List Pesanan**
   - Klik tombol "Tambah Pesanan" di halaman daftar pesanan
   - Anda akan diarahkan ke halaman **Pilih Pelanggan Lama**

2. **Halaman Pilih Pelanggan Lama**
   - Menampilkan list semua pelanggan yang terdaftar
   - Ada fitur search untuk mencari berdasarkan:
     - ✅ Nama pelanggan
     - ✅ Nomor HP
     - ✅ Email
   - Setiap pelanggan ditampilkan dengan informasi lengkap:
     - Nama
     - Nomor HP
     - Email
     - Alamat (jika ada)

3. **Pilih Pelanggan**
   - Klik tombol salah satu pelanggan
   - Anda akan diarahkan ke form Create Pesanan dengan pelanggan sudah terpilih otomatis
   - Field `customer_type` akan otomatis set ke `existing`
   - Field `user_id` akan terisi dengan ID pelanggan yang dipilih

4. **Alternatif: Pelanggan Baru**
   - Di halaman "Pilih Pelanggan Lama" ada tombol "➕ Pelanggan Baru"
   - Klik untuk membuat pesanan dengan pelanggan baru
   - Form akan menampilkan field untuk input data pelanggan baru

## 📂 File yang Dimodifikasi/Dibuat

### File Baru:
```
app/Filament/Resources/Pesanans/Pages/SelectCustomer.php
resources/views/filament/pages/select-customer.blade.php
```

### File yang Dimodifikasi:
```
app/Filament/Resources/Pesanans/PesananResource.php
app/Filament/Resources/Pesanans/Pages/CreatePesanan.php
app/Filament/Resources/Pesanans/Pages/ListPesanans.php
app/Filament/Resources/Pesanans/Schemas/PesananForm.php
```

## 🔧 Teknis

### SelectCustomer Page (`app/Filament/Resources/Pesanans/Pages/SelectCustomer.php`)
- Menggunakan Livewire untuk real-time search
- Pagination untuk menampilkan 10 pelanggan per halaman
- Method `selectCustomer()` untuk redirect ke Create page dengan parameter `user_id`

### CreatePesanan Page (`app/Filament/Resources/Pesanans/Pages/CreatePesanan.php`)
- Method `mount()` menangani parameter URL:
  - `user_id`: ID pelanggan yang dipilih (auto-fill ke form)
  - `skip_selection`: Skip ke form pelanggan baru langsung
- Otomatis mengisi field `customer_type` dan `user_id` berdasarkan parameter

### PesananForm Schema (`app/Filament/Resources/Pesanans/Schemas/PesananForm.php`)
- Select dropdown untuk pelanggan lama dengan:
  - `searchable()`: Bisa mencari di dropdown
  - `preload()`: Semua pelanggan dimuat awal
  - Format tampilan: "Nama (No HP)"

## 🎨 UI/UX Improvements

✅ Modal/halaman pemilihan dengan interface yang jelas
✅ Search real-time dengan Livewire
✅ Informasi pelanggan lengkap dalam list
✅ Pagination untuk efisiensi
✅ Tombol alternatif untuk pelanggan baru
✅ Breadcrumb navigation (back/kembali)

## 🚀 Cara Menggunakan

### Dari Pengguna:
1. Buka daftar Pesanan → klik "Tambah Pesanan"
2. Cari pelanggan menggunakan search
3. Pilih pelanggan dari list
4. Form Create Pesanan otomatis terbuka dengan pelanggan terpilih
5. Isi data pesanan lainnya → Simpan

### Dari Developer:
Jika ingin memodifikasi:
- Edit `SelectCustomer.php` untuk mengubah logika pencarian
- Edit view `select-customer.blade.php` untuk UI customization
- Edit `CreatePesanan.php` untuk logic pemrosesan data

## 📌 Notes
- Pelanggan harus memiliki `role = 'user'` untuk muncul di list
- Default sorting berdasarkan nama
- Pagination 10 pelanggan per halaman (bisa diubah di `SelectCustomer.php` line 32)
