<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesanan;
use App\Models\PesananInvoiceItem;

class MigrateExistingDataToInvoiceItems extends Seeder
{
    /**
     * Migrate existing pesanan data to invoice items table
     */
    public function run(): void
    {
        $this->command->info('🔄 Migrating existing data to invoice items...');

        // Get all pesanan yang sudah selesai analisa
        $pesanans = Pesanan::whereIn('status', [
            'selesai_analisa',
            'konfirmasi',
            'dalam proses',
            'menunggu sparepart',
            'on hold',
            'revisi',
            'selesai',
            'dibayar'
        ])->with(['services', 'spareparts'])->get();

        $migratedCount = 0;
        $skippedCount = 0;

        foreach ($pesanans as $pesanan) {
            // Skip jika sudah ada invoice items
            if ($pesanan->invoiceItems()->count() > 0) {
                $skippedCount++;
                continue;
            }

            // Migrate services
            foreach ($pesanan->services as $service) {
                PesananInvoiceItem::create([
                    'pesanan_id' => $pesanan->id,
                    'item_type' => 'service',
                    'item_name' => $service->name,
                    'item_description' => $service->category ?? null,
                    'quantity' => $service->pivot->quantity,
                    'price' => $service->pivot->price,
                    'subtotal' => $service->pivot->subtotal,
                    'source' => 'master',
                    'source_id' => $service->id,
                ]);
            }

            // Migrate spareparts
            foreach ($pesanan->spareparts as $sparepart) {
                PesananInvoiceItem::create([
                    'pesanan_id' => $pesanan->id,
                    'item_type' => 'sparepart',
                    'item_name' => $sparepart->name,
                    'item_description' => $sparepart->sku ?? null,
                    'quantity' => $sparepart->pivot->quantity,
                    'price' => $sparepart->pivot->price,
                    'subtotal' => $sparepart->pivot->subtotal,
                    'source' => 'stock', // Assume dari stock untuk data lama
                    'source_id' => $sparepart->id,
                ]);
            }

            $migratedCount++;
        }

        $this->command->info("✅ Migration completed!");
        $this->command->info("   - Migrated: {$migratedCount} pesanan");
        $this->command->info("   - Skipped: {$skippedCount} pesanan (already has invoice items)");
    }
}
