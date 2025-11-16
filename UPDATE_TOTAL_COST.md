# 🔧 Script Update Total Cost untuk Data Lama

## Deskripsi
Script ini untuk mengupdate kolom `total_cost` pada data pesanan yang sudah ada sebelumnya (data lama yang belum memiliki nilai total_cost).

## Kapan Perlu Dijalankan?
- Setelah deploy ke VPS
- Jika ada data lama yang kolom `total_cost` nya masih NULL
- Untuk memastikan semua data memiliki total_cost yang benar

## Cara Menjalankan

### Opsi 1: Via Tinker (Manual)
```bash
php artisan tinker
```

Kemudian jalankan script berikut:

```php
// Update semua pesanan yang total_cost nya NULL
$pesanans = \App\Models\Pesanan::whereNull('total_cost')->get();

foreach ($pesanans as $pesanan) {
    $serviceCost = $pesanan->service_cost ?? 0;
    $sparepartCost = $pesanan->spareparts->sum('pivot.subtotal');
    $totalCost = $serviceCost + $sparepartCost;
    
    $pesanan->update(['total_cost' => $totalCost]);
    
    echo "Updated Pesanan ID {$pesanan->id}: Rp " . number_format($totalCost, 0, ',', '.') . "\n";
}

echo "Total updated: " . $pesanans->count() . " records\n";
```

### Opsi 2: Via Command (Buat Command)
Buat file command baru:
```bash
php artisan make:command UpdateTotalCost
```

Edit file `app/Console/Commands/UpdateTotalCost.php`:

```php
<?php

namespace App\Console\Commands;

use App\Models\Pesanan;
use Illuminate\Console\Command;

class UpdateTotalCost extends Command
{
    protected $signature = 'pesanan:update-total-cost';
    protected $description = 'Update total_cost untuk data pesanan yang belum memiliki nilai';

    public function handle()
    {
        $this->info('Memulai update total_cost...');
        
        $pesanans = Pesanan::whereNull('total_cost')->get();
        
        if ($pesanans->count() === 0) {
            $this->info('Tidak ada data yang perlu diupdate.');
            return 0;
        }
        
        $bar = $this->output->createProgressBar($pesanans->count());
        $bar->start();
        
        foreach ($pesanans as $pesanan) {
            $serviceCost = $pesanan->service_cost ?? 0;
            $sparepartCost = $pesanan->spareparts->sum('pivot.subtotal');
            $totalCost = $serviceCost + $sparepartCost;
            
            $pesanan->update(['total_cost' => $totalCost]);
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Berhasil update {$pesanans->count()} data pesanan.");
        
        return 0;
    }
}
```

Kemudian jalankan:
```bash
php artisan pesanan:update-total-cost
```

### Opsi 3: Via Raw SQL (Tercepat)
**⚠️ HATI-HATI: Pastikan backup database dulu!**

```sql
-- Update pesanan tanpa sparepart
UPDATE pesanans 
SET total_cost = COALESCE(service_cost, 0)
WHERE total_cost IS NULL 
  AND id NOT IN (SELECT DISTINCT pesanan_id FROM pesanan_sparepart);

-- Update pesanan dengan sparepart
UPDATE pesanans p
SET total_cost = (
    COALESCE(p.service_cost, 0) + 
    COALESCE((
        SELECT SUM(subtotal) 
        FROM pesanan_sparepart 
        WHERE pesanan_id = p.id
    ), 0)
)
WHERE p.total_cost IS NULL;
```

## Verifikasi Hasil

Cek apakah masih ada data yang NULL:
```bash
php artisan tinker
```

```php
\App\Models\Pesanan::whereNull('total_cost')->count()
// Harusnya return 0

// Cek beberapa sample
\App\Models\Pesanan::latest()->take(5)->get(['id', 'service_cost', 'total_cost'])
```

## Catatan Penting

1. **Backup Database Dulu!** - Sebelum jalankan script apapun di production
2. **Test di Local** - Coba dulu di local sebelum deploy ke VPS
3. **Eager Loading** - Script sudah optimized dengan eager loading spareparts
4. **Nullable Safe** - Script sudah handle jika service_cost atau sparepart NULL

## Troubleshooting

**Q: Total cost tidak sesuai?**
A: Pastikan eager load relasi spareparts: `$pesanan->load('spareparts')`

**Q: Script timeout?**
A: Jika data banyak (>10.000), jalankan per batch:
```php
Pesanan::whereNull('total_cost')->chunk(100, function ($pesanans) {
    foreach ($pesanans as $pesanan) {
        // ... update logic
    }
});
```

**Q: Error di VPS?**
A: Pastikan migration sudah dijalankan dulu dengan `php artisan migrate`
