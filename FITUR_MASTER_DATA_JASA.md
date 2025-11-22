# 🛠️ FITUR MASTER DATA JASA SERVICE

## 📋 Deskripsi
Fitur untuk mengelola **master data jasa service** yang memudahkan pemilihan jasa dengan harga standar saat proses analisa. Teknisi tidak perlu input manual berulang kali untuk jasa yang sama.

---

## ✨ Fitur Utama

### 1️⃣ **Halaman Master Jasa Service**
Menu baru di admin panel untuk:
- ✅ Tambah jasa baru (Nama, Kategori, Harga, Deskripsi)
- ✅ Edit harga jasa
- ✅ Aktifkan/Non-aktifkan jasa
- ✅ Filter berdasarkan kategori
- ✅ Search jasa

### 2️⃣ **Kategori Jasa**
7 kategori jasa tersedia:
- 🔧 **Hardware** - Ganti keyboard, LCD, dll
- 💻 **Software** - Install OS, aplikasi, dll
- 🧹 **Cleaning** - Pembersihan laptop, PC
- ⬆️ **Upgrade** - Upgrade RAM, SSD, dll
- 🔨 **Repair** - Perbaikan motherboard, dll
- 📦 **Installation** - Instalasi hardware/software
- 📝 **Other** - Lainnya

### 3️⃣ **Repeater Jasa di Form Analisa**
Saat analisa selesai, teknisi bisa:
- Pilih jasa dari dropdown (auto load harga)
- Input quantity (jika perlu)
- Harga bisa diedit (jika berbeda dari standar)
- Auto calculate subtotal

### 4️⃣ **WhatsApp Template**
Template otomatis include:
- Biaya jasa servis (manual input)
- **Jasa dari master data** (baru!)
- Sparepart yang digunakan
- Subtotal, Diskon, Total

---

## 🗂️ Database Schema

### **Tabel: `services`**
```sql
id                  - Primary Key
name                - VARCHAR (Nama jasa)
category            - VARCHAR (Kategori: Hardware, Software, dll)
description         - TEXT (Deskripsi detail)
price               - DECIMAL(15,2) (Harga standar)
is_active           - BOOLEAN (Status aktif/nonaktif)
created_at          - TIMESTAMP
updated_at          - TIMESTAMP
```

### **Tabel: `pesanan_service` (Pivot)**
```sql
id                  - Primary Key
pesanan_id          - FK → pesanans
service_id          - FK → services
quantity            - INTEGER (Jumlah, default: 1)
price               - DECIMAL(15,2) (Harga saat digunakan)
subtotal            - DECIMAL(15,2) (quantity * price)
created_at          - TIMESTAMP
updated_at          - TIMESTAMP
```

---

## 🔄 Alur Kerja

```
┌──────────────────────────────────────────────────────────┐
│  1. SETUP: Admin Tambah Master Data Jasa                 │
└──────────────────────────────────────────────────────────┘
                        │
                        ▼
        ┌───────────────────────────────┐
        │ Menu: Master Jasa             │
        │ - Ganti Keyboard: Rp150.000   │
        │ - Install Windows 11: Rp100.000│
        │ - Upgrade RAM: Rp50.000       │
        └───────────────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────────────────┐
│  2. ANALISA: Teknisi Pilih Jasa dari Master              │
└──────────────────────────────────────────────────────────┘
                        │
                        ▼
        ┌───────────────────────────────┐
        │ Repeater "Jasa Service"       │
        │ - Pilih: Ganti Keyboard       │
        │ - Qty: 1                      │
        │ - Harga: Rp150.000 (editable) │
        │ - Subtotal: Rp150.000         │
        └───────────────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────────────────┐
│  3. KALKULASI: Total Cost Auto Update                    │
└──────────────────────────────────────────────────────────┘
                        │
                        ▼
        Total = Service Cost (manual)
              + Jasa dari Master
              + Sparepart
              - Diskon
                        │
                        ▼
┌──────────────────────────────────────────────────────────┐
│  4. WHATSAPP: Template Include Jasa dari Master          │
└──────────────────────────────────────────────────────────┘
```

---

## 🎯 Contoh Penggunaan

### **Skenario 1: Ganti Keyboard + Install OS**

#### **Step 1: Tambah Master Data** (Admin)
```
Nama: Ganti Keyboard Laptop
Kategori: Hardware
Harga: Rp 150.000
Status: Aktif

Nama: Install Windows 11
Kategori: Software  
Harga: Rp 100.000
Status: Aktif
```

#### **Step 2: Analisa Selesai** (Teknisi)
Form analisa:
```
Analisa: "Keyboard rusak, OS lambat"
Solusi: "Ganti keyboard + reinstall OS"

Jasa Service:
1. Ganti Keyboard Laptop
   Qty: 1
   Harga: Rp 150.000
   Subtotal: Rp 150.000

2. Install Windows 11
   Qty: 1
   Harga: Rp 100.000
   Subtotal: Rp 100.000

Sparepart:
- Keyboard Asus X441: Rp 350.000

Service Cost: Rp 0 (tidak pakai, karena sudah pakai master jasa)
Discount: Rp 50.000
```

#### **Step 3: Total Cost**
```
Service Cost (manual):   Rp       0
Jasa (Master):          Rp 250.000 (150k + 100k)
Sparepart:              Rp 350.000
─────────────────────────────────
Subtotal:               Rp 600.000
Diskon:                -Rp  50.000
═════════════════════════════════
TOTAL:                  Rp 550.000
```

#### **Step 4: WhatsApp Template**
```
Halo Kak Budi 👋

Tim teknisi kami sudah melakukan pengecekan pada *Laptop Asus X441* Kakak.

📋 *HASIL ANALISA:*
Keyboard rusak, OS lambat

🔧 *SOLUSI:*
Ganti keyboard + reinstall OS

💰 *RINCIAN BIAYA:*
• Ganti Keyboard Laptop (1x Rp150.000): Rp150.000
• Install Windows 11 (1x Rp100.000): Rp100.000
• Keyboard Asus X441 (1x Rp350.000): Rp350.000

_Subtotal: Rp600.000_
_Diskon: -Rp50.000_

*TOTAL BIAYA: Rp550.000*

Apabila Kakak setuju, kami akan segera melanjutkan proses perbaikan.

Mohon konfirmasinya ya Kak 🙏

Terima kasih,
*PWS Computer Service Center*
```

---

### **Skenario 2: Hanya Cleaning (Tanpa Sparepart)**

```
Jasa Service:
- Deep Cleaning Laptop
  Qty: 1
  Harga: Rp 75.000

Sparepart: (kosong)
Service Cost: Rp 0
Discount: Rp 0

TOTAL: Rp 75.000
```

WhatsApp:
```
💰 *RINCIAN BIAYA:*
• Deep Cleaning Laptop (1x Rp75.000): Rp75.000

_Subtotal: Rp75.000_

*TOTAL BIAYA: Rp75.000*
```

---

## 🧪 Testing Checklist

### ✅ Test 1: Tambah Master Data Jasa
- [ ] Buka menu "Master Jasa"
- [ ] Klik "Create"
- [ ] Isi:
  - Nama: "Ganti LCD Laptop"
  - Kategori: Hardware
  - Harga: Rp 300.000
  - Deskripsi: "Ganti LCD laptop rusak"
  - Status: Aktif
- [ ] Simpan
- [ ] Cek tabel: Data tersimpan dengan benar

### ✅ Test 2: Pilih Jasa di Analisa
- [ ] Buat pesanan baru
- [ ] Lanjut ke status "Analisa"
- [ ] Di form analisa, expand "Jasa Service yang Dilakukan"
- [ ] Klik "Tambah Jasa"
- [ ] Pilih dropdown → harus muncul "Ganti LCD Laptop (Hardware) - Rp300.000"
- [ ] Pilih jasa → harga otomatis terisi Rp 300.000
- [ ] Input qty: 1
- [ ] Cek subtotal: Rp 300.000

### ✅ Test 3: Multi Jasa + Sparepart
- [ ] Tambah 2 jasa:
  - Ganti LCD: Rp 300.000
  - Install OS: Rp 100.000
- [ ] Tambah 1 sparepart:
  - Thermal Paste: Rp 20.000
- [ ] Service Cost: Rp 50.000
- [ ] Discount: Rp 20.000
- [ ] Cek total cost:
  ```
  50k + 300k + 100k + 20k - 20k = Rp 450.000
  ```

### ✅ Test 4: Edit Harga Jasa Saat Digunakan
- [ ] Pilih jasa "Ganti LCD" (default Rp 300.000)
- [ ] Edit harga di form menjadi Rp 350.000
- [ ] Qty: 1
- [ ] Cek subtotal: Rp 350.000
- [ ] Simpan pesanan
- [ ] Cek database: harga di pivot = 350k, master tetap 300k

### ✅ Test 5: WhatsApp Template
- [ ] Lanjut ke "Selesai Analisa"
- [ ] Cek template WhatsApp
- [ ] Pastikan jasa dari master muncul di rincian:
  ```
  • Ganti LCD Laptop (1x Rp300.000): Rp300.000
  ```
- [ ] Pastikan subtotal benar
- [ ] Kirim ke WhatsApp → link berfungsi

### ✅ Test 6: Filter & Search Master Jasa
- [ ] Buka "Master Jasa"
- [ ] Filter kategori: Hardware
- [ ] Harus tampil hanya jasa kategori Hardware
- [ ] Search: "Install"
- [ ] Harus tampil jasa yang nama ada kata "Install"

### ✅ Test 7: Non-Aktifkan Jasa
- [ ] Edit jasa "Ganti LCD"
- [ ] Ubah status menjadi Non-Aktif
- [ ] Simpan
- [ ] Buka form analisa
- [ ] Pilih dropdown jasa → "Ganti LCD" tidak muncul lagi

---

## 📂 File yang Diubah/Dibuat

### **Migration**
```
database/migrations/2025_11_22_042429_create_services_table.php
```

### **Model**
```
app/Models/Service.php (NEW)
app/Models/Pesanan.php (UPDATE - tambah relationship)
```

### **Filament Resource**
```
app/Filament/Resources/Services/ServiceResource.php (NEW)
app/Filament/Resources/Services/Schemas/ServiceForm.php (NEW)
app/Filament/Resources/Services/Tables/ServicesTable.php (NEW)
app/Filament/Resources/Services/Pages/ListServices.php (NEW)
app/Filament/Resources/Services/Pages/CreateService.php (NEW)
app/Filament/Resources/Services/Pages/EditService.php (NEW)
```

### **Update Form Pesanan**
```
app/Filament/Resources/Pesanans/Tables/PesanansTable.php
- Tambah repeater "services" di form analisa
- Update logic simpan jasa
- Update WhatsApp template (2 tempat)
```

---

## 🚀 Deployment

### **Step 1: Upload Files**
Upload semua file yang baru dibuat/diubah ke server.

### **Step 2: Run Migration**
```bash
php artisan migrate --path=database/migrations/2025_11_22_042429_create_services_table.php
```

Output:
```
INFO  Running migrations.  
2025_11_22_042429_create_services_table .......... 74.12ms DONE
```

### **Step 3: Clear Cache**
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### **Step 4: Test di Browser**
1. Login ke admin panel
2. Cek menu baru: "Master Jasa"
3. Tambah 2-3 jasa untuk testing
4. Test di form analisa

---

## 💡 Tips Penggunaan

### **Kapan Pakai Service Cost vs Master Jasa?**

| Field | Kapan Dipakai | Contoh |
|-------|---------------|--------|
| **Service Cost** | Biaya jasa yang tidak ada di master, atau biaya umum | "Biaya check-up umum: Rp 50.000" |
| **Master Jasa** | Jasa spesifik yang sering digunakan | "Ganti Keyboard", "Install OS" |

**Best Practice:**
- Buat master jasa untuk layanan yang **sering dilakukan**
- Pakai Service Cost untuk biaya **administrasi** atau **tidak rutin**
- Bisa pakai keduanya sekaligus (dijumlahkan)

### **Harga Bisa Berbeda dari Master?**
Ya! Harga di master adalah **harga standar**. Saat digunakan di pesanan, harga bisa diedit sesuai kondisi:
- Negosiasi customer
- Promo khusus
- Tingkat kesulitan berbeda

### **Jasa Seasonal (Jarang Dipakai)?**
Non-aktifkan jasa yang jarang dipakai:
- Tidak muncul di dropdown (tidak menggangg)
- Data tetap tersimpan
- Bisa diaktifkan kembali kapan saja

---

## 📊 Badge Kategori (di Tabel Master Jasa)

| Kategori | Warna Badge |
|----------|-------------|
| Hardware | 🔴 Merah |
| Software | 🔵 Biru |
| Cleaning | 🟢 Hijau |
| Upgrade | 🟡 Kuning |
| Repair | 🟣 Ungu |
| Installation | ⚪ Abu-abu |
| Other | ⚫ Abu gelap |

---

## 🔮 Future Enhancement

Potensial fitur tambahan:
1. ✨ **Import/Export Master Jasa** via Excel
2. ✨ **Tracking Jasa Paling Laris** (analytics)
3. ✨ **Bundle Jasa** (Paket Combo)
4. ✨ **Harga Tier** (Bronze, Silver, Gold)
5. ✨ **Estimasi Waktu** per jasa
6. ✨ **SOP/Checklist** per jasa

---

## 🆚 Perbedaan: Service Cost vs Master Jasa

| Aspek | Service Cost (Manual) | Master Jasa |
|-------|----------------------|-------------|
| **Input** | Ketik manual setiap kali | Pilih dari dropdown |
| **Konsistensi Harga** | ❌ Bisa beda-beda | ✅ Standar (bisa diedit) |
| **Kecepatan** | ❌ Lambat (ketik manual) | ✅ Cepat (klik & pilih) |
| **Tracking** | ❌ Sulit analisa | ✅ Bisa di-report |
| **Use Case** | Biaya umum, one-time | Jasa rutin, berulang |
| **WhatsApp** | 1 baris "Jasa Servis" | Detail per item jasa |

---

**Dibuat:** 22 November 2025  
**Developer:** GitHub Copilot + Tim PWS Computer  
**Status:** ✅ Production Ready
