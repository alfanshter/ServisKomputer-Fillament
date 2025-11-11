# 📋 Dokumentasi Modal Pemilihan Pelanggan Lama

## 🎯 Fitur Utama

Modal pemilihan pelanggan telah diperbarui dengan tampilan yang lebih modern dan user-friendly.

### ✨ Improvement Visual

1. **Header dengan Gradient**
   - Background gradient biru ke indigo
   - Deskripsi singkat untuk memandu user
   - Icon close button yang jelas

2. **Search Bar dengan Icon**
   - Icon magnifying glass di sebelah kiri
   - Focus state dengan border biru
   - Ring effect untuk visual feedback

3. **User List Card**
   - Avatar dengan initial nama user
   - Gradient background pada avatar
   - Hover effect yang smooth
   - Info: nama, email, dan nomor HP
   - Arrow icon di sebelah kanan

4. **Empty State**
   - Icon illustration yang jelas
   - Pesan helpful
   - Suggestion untuk user

5. **Display Pelanggan Terpilih**
   - Card dengan gradient background
   - Avatar dengan gradient
   - Badge "✓ Pelanggan Terpilih"
   - Button hapus untuk reset pilihan
   - Auto-update text button ("Pilih" → "Ganti")

### 🎨 Styling Features

- **Animations**: Fade-in dengan zoom saat modal dibuka
- **Custom Scrollbar**: Styling scrollbar yang match design
- **Hover Effects**: Smooth transition pada semua interactive elements
- **Colors**: Blue gradient color scheme (Blue 600 → Indigo 600)
- **Spacing**: Consistent padding dan gap menggunakan Tailwind

### 📱 Responsive Design

- Modal menyesuaikan ukuran di device kecil
- Padding ditambahkan untuk mobile
- Backdrop blur untuk better focus
- Scrollable list dengan max-height

## 🔧 Komponen yang Diubah

### 1. `SelectUserModal.php` (Livewire Component)
- Fitur search realtime
- Pagination support
- Event dispatching

### 2. `select-user-modal.blade.php` (Modal View)
- Design modern dengan gradient header
- Search input dengan icon
- List dengan avatar dan hover effects
- Custom scrollbar styling
- Empty state illustration

### 3. `select-user-with-modal.blade.php` (Filament Component)
- Display card untuk selected user
- Button untuk buka/ganti pelanggan
- Auto-update UI based on selection
- Delete button untuk clear selection

## 🚀 Cara Menggunakan

1. Buka halaman Create Pesanan
2. Pilih "Pelanggan Lama" di dropdown "Jenis Pelanggan"
3. Klik button "Pilih Pelanggan Lama"
4. Cari user dengan nama, email, atau no. HP
5. Klik user yang diinginkan
6. Nama user akan muncul di display card
7. Jika ingin mengganti, klik button lagi atau klik X untuk reset

## 🎨 Color Palette

- **Primary**: Blue 600 (#2563eb)
- **Secondary**: Indigo 600 (#4f46e5)
- **Gradient**: Blue → Indigo
- **Hover**: Brighter shades
- **Success**: ✓ check mark
- **Danger**: Red 500 untuk delete

## 📦 Dependencies

- Livewire 3.x
- Filament 3.x
- Tailwind CSS
- Alpine.js (built-in dengan Filament)

## ⚙️ Notes

- Search case-insensitive
- Pagination 10 items per page
- Event-driven architecture untuk maintainability
- Fully compatible dengan Filament form validation
