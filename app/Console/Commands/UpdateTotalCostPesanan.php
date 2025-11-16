<?php

namespace App\Console\Commands;

use App\Models\Pesanan;
use Illuminate\Console\Command;

class UpdateTotalCostPesanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pesanan:update-total-cost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update total_cost untuk pesanan yang masih NULL atau 0 (data lama)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Memulai update total_cost untuk pesanan lama...');
        $this->newLine();

        // Ambil semua pesanan yang total_cost nya NULL atau 0
        $pesanans = Pesanan::with('spareparts')
            ->where(function ($query) {
                $query->whereNull('total_cost')
                    ->orWhere('total_cost', 0);
            })
            ->get();

        if ($pesanans->count() === 0) {
            $this->info('✅ Tidak ada pesanan yang perlu diupdate.');
            return Command::SUCCESS;
        }

        $this->info("📊 Ditemukan {$pesanans->count()} pesanan yang perlu diupdate.");
        $this->newLine();

        $bar = $this->output->createProgressBar($pesanans->count());
        $bar->start();

        $updated = 0;
        $skipped = 0;

        foreach ($pesanans as $pesanan) {
            // Hitung total biaya
            $serviceCost = $pesanan->service_cost ?? 0;
            $sparepartCost = $pesanan->spareparts->sum('pivot.subtotal') ?? 0;
            $totalCost = $serviceCost + $sparepartCost;

            // Skip jika tetap 0 (tidak ada biaya sama sekali)
            if ($totalCost == 0) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // Update total_cost
            $pesanan->update(['total_cost' => $totalCost]);
            $updated++;

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Berhasil update {$updated} pesanan");
        if ($skipped > 0) {
            $this->warn("⚠️  {$skipped} pesanan diskip (total biaya = 0)");
        }

        $this->newLine();
        $this->info('🎉 Proses selesai!');

        return Command::SUCCESS;
    }
}
