# Role & Permission - Manajemen Pesanan Servis

## Update Terbaru (7 Desember 2025)

✅ **Status Baru Ditambahkan: `siap_diambil`**
✅ **Supervisor Role: Full Access (Teknisi + Admin)**
✅ **Transaksi: Hanya dibuat saat status `dibayar`**
✅ **Menu Access Control: Pembatasan berdasarkan role**

Flow sekarang lebih jelas antara tugas teknisi dan admin:
- Teknisi → Tandai pekerjaan selesai (upload foto after + notes)
- Admin → Kabari customer barang siap diambil (template WA)
- Admin → Tandai dibayar (auto create transaksi pemasukan)

---

## Akses Menu Berdasarkan Role

| Menu | Teknisi | Admin | Supervisor |
|------|---------|-------|------------|
| **Dashboard** | ✅ Lihat | ✅ Lihat | ✅ Lihat |
| **Pekerjaan Saya** | ✅ Khusus Teknisi | ❌ Hidden | ❌ Hidden |
| **Pesanan** | ✅ Full CRUD | ✅ Full CRUD | ✅ Full CRUD |
| **Pelanggan** | 👁️ View Only | ✅ Full CRUD | ✅ Full CRUD |
| **Tim Internal** | 👁️ View Only | 👁️ View Only | ✅ Full CRUD |
| **Master Jasa** | 👁️ View Only | ✅ Full CRUD | ✅ Full CRUD |
| **Sparepart** | 👁️ View Only | ✅ Full CRUD | ✅ Full CRUD |
| **Order Sparepart (PO)** | ❌ Hidden | ✅ Full CRUD | ✅ Full CRUD |
| **Transaksi Keuangan** | ❌ Hidden | ✅ Full CRUD | ✅ Full CRUD |

**Keterangan:**
- ✅ **Full CRUD** = Bisa Create, Read, Update, Delete
- 👁️ **View Only** = Bisa lihat data saja, tombol Create/Edit/Delete **disabled** dengan icon kunci
- ❌ **Hidden** = Menu tidak muncul di sidebar

### 🆕 Menu "Pekerjaan Saya" (Khusus Teknisi)

**Tujuan:**
- Fokus teknisi hanya ke pesanan yang perlu dikerjakan
- Tidak kebanyakan data (pesanan yang sudah dibayar/batal tidak muncul)
- Dashboard khusus dengan statistik pekerjaan teknisi

**Filter Pesanan yang Muncul:**
- ✅ `belum mulai` - Pesanan baru yang perlu mulai analisa
- ✅ `analisa` - Sedang proses diagnosa
- ✅ `dalam proses` - Sedang dikerjakan
- ✅ `menunggu sparepart` - Tunggu sparepart datang
- ✅ `on hold` - Ditunda sementara
- ✅ `revisi` - Customer minta perubahan
- ✅ `selesai` - Baru selesai dikerjakan (belum diambil customer)

**Filter Pesanan yang TIDAK Muncul:**
- ❌ `selesai_analisa` - Sudah selesai analisa, menunggu admin konfirmasi
- ❌ `konfirmasi` - Admin sedang konfirmasi ke customer
- ❌ `siap_diambil` - Admin sudah kabari customer
- ❌ `dibayar` - Sudah lunas (pekerjaan selesai)
- ❌ `batal` - Dibatalkan

**Fitur Spesial:**
- 🔔 **Badge Notifikasi**: Jumlah pekerjaan aktif di icon menu
- 📊 **Widget Statistik**: 
  - Perlu Dikerjakan (total aktif)
  - Belum Mulai (dengan animasi pulse jika ada)
  - Sedang Analisa
  - Dalam Pengerjaan
  - Menunggu Sparepart
  - Perlu Revisi
  - On Hold
  - Selesai Dikerjakan
- 🔄 **Auto Refresh**: Data ter-update otomatis setiap 30 detik

**Menu "Semua Pesanan" tetap ada:**
- Teknisi tetap bisa lihat semua pesanan untuk referensi
- Tapi fokus utama di "Pekerjaan Saya"

### Detail Pembatasan:

#### 1. **Teknisi - View Only Access:**
Teknisi bisa lihat data untuk referensi, tapi tidak bisa mengubah:
- **Pelanggan**: Bisa lihat info customer (nama, HP, alamat) tapi tidak bisa tambah/edit/hapus
- **Tim Internal**: Bisa lihat siapa saja teamnya tapi tidak bisa tambah/edit/hapus
- **Master Jasa**: Bisa lihat daftar jasa & harga tapi tidak bisa tambah/edit/hapus
- **Sparepart**: Bisa lihat stok & harga tapi tidak bisa tambah/edit/hapus

**Yang Muncul di UI Teknisi:**
- Tombol "New" / "Create" → **DISABLED** atau **HIDDEN**
- Tombol "Edit" → **DISABLED** dengan icon 🔒
- Tombol "Delete" → **DISABLED** dengan icon 🔒
- Hanya bisa klik "View" untuk lihat detail

#### 2. **Teknisi - Hidden Menu:**
Menu ini **tidak muncul** di sidebar untuk teknisi:
- **Order Sparepart (PO)**: Teknisi tidak perlu tahu urusan pembelian
- **Transaksi Keuangan**: Teknisi tidak perlu lihat keuangan perusahaan

#### 3. **Admin - Full Access:**
Admin bisa semua kecuali:
- **Tim Internal**: Admin tidak bisa tambah/edit/delete akun staff (hanya supervisor)

#### 4. **Supervisor - Full Access:**
Supervisor bisa **SEMUA** tanpa batasan, termasuk:
- Tambah/edit/delete akun tim internal
- Akses semua menu
- Semua action pesanan

---

## Daftar Role

### 1. **Customer** 
- Pelanggan yang menggunakan jasa servis
- Tidak bisa akses admin panel
- Hanya terima notifikasi WhatsApp

### 2. **Teknisi** 
**Fokus:** Pekerjaan teknis (diagnosa, perbaikan, dokumentasi)

✅ **BISA:**
- Mulai analisa (upload foto before)
- Selesai analisa (input hasil + pilih jasa/sparepart)
- Tandai selesai (upload foto after + catatan)
- Lanjut dari menunggu sparepart / on hold / revisi

❌ **TIDAK BISA:**
- Edit diskon
- Konfirmasi ke customer (kirim WA)
- Kabari customer
- Tandai dibayar
- Cancel pesanan
- Rollback status

### 3. **Admin / Customer Service**
**Fokus:** Komunikasi customer & pembayaran

✅ **BISA:**
- Konfirmasi hasil analisa ke customer (kirim WA template)
- Approve/reject dari customer (dalam proses / batal)
- Kabari customer barang siap diambil
- Tandai dibayar (auto create transaksi)
- Edit diskon
- Cancel pesanan
- Rollback status

❌ **TIDAK BISA:**
- *(semua bisa, kecuali idealnya tidak ikut analisa teknis - biar teknisi yang handle)*

### 4. **Supervisor**
**Fokus:** Monitoring, quality control, full access

✅ **BISA SEMUA** yang Teknisi + Admin bisa:
- Semua pekerjaan teknis (analisa, selesai, dll)
- Semua komunikasi customer (konfirmasi, kabari, dll)
- Edit diskon
- Tandai dibayar
- Cancel pesanan
- Rollback status
- Monitoring semua pesanan
- Handle edge case & eskalasi

**Keuntungan Full Access:**
- Fleksibel untuk backup teknisi/admin
- Bisa intervensi langsung saat ada masalah urgent
- Quality control di setiap tahap
- Handle kasus kompleks/khusus

### 5. **Marketing** 
- (untuk pengembangan future: analisis data, campaign, dll)

---

## Pembagian Tugas per Status

### 1. Status: `belum_mulai`
**Siapa yang bisa akses:**
- ✅ Admin
- ✅ Supervisor  
- ✅ Teknisi

**Action yang tersedia:**
- **Mulai Analisa** → Upload foto before, lanjut ke status `analisa`

**Tugas:**
- Admin: Input data pesanan baru, assign teknisi (future)
- Teknisi: Terima pesanan dan mulai analisa

---

### 2. Status: `analisa`
**Siapa yang bisa akses:**
- ✅ Teknisi (utama)
- ✅ Admin
- ✅ Supervisor

**Action yang tersedia:**
- **Analisa Selesai** → Input hasil analisa, estimasi biaya, pilih sparepart

**Tugas:**
- Teknisi: 
  - Diagnosa kerusakan
  - Tulis catatan hasil analisa
  - Tulis solusi yang disarankan
  - Pilih sparepart yang dibutuhkan
  - Estimasi biaya jasa
- Admin/Supervisor: 
  - Bisa edit diskon (field khusus)

---

### 3. Status: `selesai_analisa`
**Siapa yang bisa akses:**
- ✅ Admin (utama)
- ✅ Supervisor

**Action yang tersedia:**
- **Konfirmasi** → Kirim hasil analisa ke customer via WhatsApp

**Tugas:**
- Admin:
  - Review hasil analisa dari teknisi
  - Konfirmasi harga ke customer
  - Kirim template WA dengan rincian biaya
  - Tunggu approval customer

---

### 4. Status: `konfirmasi`
**Siapa yang bisa akses:**
- ✅ Admin (utama)
- ✅ Supervisor

**Action yang tersedia:**
- **Next Step** → Pilih tindakan setelah konfirmasi customer:
  - `dalam proses` - Customer setuju, lanjut pengerjaan
  - `batal` - Customer tidak setuju
  - `revisi` - Customer minta perubahan

**Tugas:**
- Admin: Input hasil konfirmasi customer

---

### 5. Status: `dalam_proses`
**Siapa yang bisa akses:**
- ✅ Teknisi (utama)
- ✅ Admin
- ✅ Supervisor

**Action yang tersedia:**
- **Selesai** → Tandai pekerjaan selesai, upload foto after

**Tugas:**
- Teknisi:
  - Kerjakan servis sesuai analisa
  - Update progress jika perlu
  - Upload foto after
  - Tandai selesai

---

### 6. Status: `menunggu_sparepart`
**Siapa yang bisa akses:**
- ✅ Teknisi
- ✅ Admin
- ✅ Supervisor

**Action yang tersedia:**
- **Lanjut Proses** → Lanjutkan ke `dalam proses` setelah sparepart tersedia

**Tugas:**
- Teknisi: Menandai butuh sparepart
- Admin: Buat Purchase Order sparepart
- Teknisi: Lanjutkan setelah sparepart datang

---

### 7. Status: `on_hold`
**Siapa yang bisa akses:**
- ✅ Teknisi
- ✅ Admin
- ✅ Supervisor

**Action yang tersedia:**
- **Lanjutkan** → Kembali ke status yang sesuai

**Tugas:**
- Admin/Teknisi: Tandai pesanan tertunda sementara

---

### 8. Status: `revisi`
**Siapa yang bisa akses:**
- ✅ Teknisi
- ✅ Admin
- ✅ Supervisor

**Action yang tersedia:**
- **Revisi Selesai** → Update dan lanjutkan

**Tugas:**
- Teknisi: Edit analisa/solusi sesuai permintaan customer
- Admin: Komunikasikan perubahan ke customer

---

### 9. Status: `selesai`
**Siapa yang bisa akses:**
- ✅ Admin (utama)
- ✅ Supervisor

**Action yang tersedia:**
- **Kabari Customer** → Kirim WA "barang sudah selesai, siap diambil"

**Tugas:**
- Admin/Supervisor:
  - Review hasil pekerjaan teknisi
  - Kirim template WA ke customer dengan rincian:
    * Device yang diselesaikan
    * Jasa yang dikerjakan
    * Sparepart yang diganti
    * Total biaya (sudah termasuk diskon)
    * Pilihan pickup/antar
  - Lanjut ke status `siap_diambil`
  
**Catatan:** 
- 🚫 **TIDAK create transaksi** (belum dibayar)
- ✅ Hanya notifikasi ke customer

---

### 10. Status: `siap_diambil`
**Siapa yang bisa akses:**
- ✅ Admin (utama)
- ✅ Supervisor

**Action yang tersedia:**
- **Tandai Dibayar** → Input metode pembayaran, auto create transaksi

**Tugas:**
- Admin/Supervisor:
  - Tunggu customer ambil barang
  - Terima pembayaran
  - Pilih metode pembayaran (cash/transfer/QRIS/dll)
  - Tandai status `dibayar`
  - **Otomatis create transaksi pemasukan** ke tabel `transactions`
  - Kirim template WA terima kasih + minta review Google Maps

**Catatan:**
- 💰 **CREATE TRANSAKSI** saat status ini → `dibayar`
- Auto-generate invoice
- Update catatan keuangan

---

### 11. Status: `dibayar`
**Final status** - Pesanan selesai dan lunas

**Yang tercatat:**
- ✅ Status pesanan = dibayar
- ✅ Transaksi pemasukan tercatat di keuangan
- ✅ Metode pembayaran tersimpan
- ✅ Stok sparepart sudah terkurangi
- ✅ History status lengkap

---

### 12. Status: `batal`
**Siapa yang bisa akses:**
- ✅ Admin (utama)
- ✅ Supervisor

**Action yang tersedia:**
- **Cancel** → Batalkan pesanan (bisa dari status manapun kecuali `dibayar`)

**Tugas:**
- Admin/Supervisor: 
  - Cancel pesanan dengan alasan
  - Otomatis kembalikan stok sparepart (jika ada)
  - Simpan notes pembatalan

---

## Ringkasan Akses per Role

| Status / Action | Teknisi | Admin | Supervisor |
|----------------|---------|-------|------------|
| **Mulai Analisa** (belum_mulai → analisa) | ✅ | ✅ | ✅ |
| **Analisa Selesai** (analisa → selesai_analisa) | ✅ | ✅ | ✅ |
| **Konfirmasi** (selesai_analisa → konfirmasi) | ❌ | ✅ | ✅ |
| **Next Step** (konfirmasi → dalam_proses/batal/revisi) | ❌ | ✅ | ✅ |
| **Selesai** (dalam_proses → selesai) | ✅ | ✅ | ✅ |
| **Kabari Customer** (selesai → siap_diambil) | ❌ | ✅ | ✅ |
| **Tandai Dibayar** (siap_diambil → dibayar) | ❌ | ✅ | ✅ |
| **Lanjut dari Menunggu Sparepart** | ✅ | ✅ | ✅ |
| **Lanjut dari On Hold** | ✅ | ✅ | ✅ |
| **Lanjut dari Revisi** | ✅ | ✅ | ✅ |
| **Edit Diskon** | ❌ | ✅ | ✅ |
| **Cancel Pesanan** | ❌ | ✅ | ✅ |
| **Rollback Status** | ❌ | ✅ | ✅ |

**Kesimpulan:**
- **Teknisi** = Fokus pekerjaan teknis (analisa, perbaikan, dokumentasi)
- **Admin** = Fokus komunikasi customer & pembayaran
- **Supervisor** = **Full Access** (bisa semua yang teknisi + admin bisa)

---

## Dashboard Widget per Role

### Semua Role (Teknisi, Admin, Supervisor):
- ✅ Belum Mulai (pesanan baru)
- ✅ Analisa/Konfirmasi (sedang diproses)
- ✅ Dalam Pengerjaan (proses/menunggu/hold/revisi)
- ✅ Selesai (menunggu diambil)
- ✅ Sudah Dibayar (transaksi selesai)
- ✅ Total Pelanggan
- ✅ Sparepart (total & low stock warning)

### Hanya Admin & Supervisor:
- 💰 **Pendapatan** (total pemasukan)
- 💰 **Pengeluaran** (total pengeluaran)
- 💰 **Laba Bersih** (pendapatan - pengeluaran)
- 📦 **PO Pending** (purchase order menunggu/dalam pengiriman)

**Teknisi TIDAK melihat:**
- Statistik keuangan (pendapatan, pengeluaran, laba)
- Purchase Order pending

---

## Fitur Khusus dengan Pembatasan Role

### 🔒 Action: **Cancel Pesanan**
**Akses:** Admin & Supervisor saja
- Tidak bisa cancel pesanan yang sudah `dibayar`
- Otomatis kembalikan stok sparepart

### 🔒 Action: **Status Sebelumnya (Rollback)**
**Akses:** Admin & Supervisor saja
- Untuk mengembalikan ke status sebelumnya jika ada kesalahan
- Wajib isi alasan rollback

### 🔒 Field: **Diskon**
**Edit:** Admin & Supervisor saja
- Teknisi bisa lihat tapi tidak bisa edit
- Untuk kontrol approval diskon

---

## Rekomendasi Future Enhancement

1. **Assign Teknisi ke Pesanan**
   - Tambah field `technician_id` di tabel pesanan
   - Teknisi hanya bisa lihat/edit pesanan yang di-assign ke dia
   - Laporan kinerja per teknisi

2. **Notifikasi Push/Email**
   - Auto notif ke teknisi saat ada pesanan baru
   - Auto notif ke admin saat teknisi selesai analisa
   - Reminder untuk pesanan on hold > 3 hari

3. **Approval Diskon**
   - Jika teknisi mau kasih diskon > Rp 100.000, harus minta approval supervisor
   - Workflow approval di sistem

4. **Status Tambahan: `siap_diambil`**
   - Setelah `selesai`, sebelum `dibayar`
   - Untuk bedakan yang masih di meja vs sudah di rak

5. **Catatan Internal**
   - Field khusus untuk komunikasi antar tim (tidak tampil ke customer)

---

## Testing Checklist

- [ ] Login sebagai Teknisi → coba akses action konfirmasi (harusnya tidak muncul)
- [ ] Login sebagai Admin → coba akses semua action (harusnya bisa)
- [ ] Login sebagai Teknisi → coba edit diskon (harusnya disabled)
- [ ] Login sebagai Admin → coba cancel pesanan (harusnya bisa)
- [ ] Login sebagai Supervisor → coba rollback status (harusnya bisa)

---

**Dibuat:** 7 Desember 2025  
**Versi:** 1.0
