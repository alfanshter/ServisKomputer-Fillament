<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // small cache to avoid heavy queries on every dashboard load
        $metrics = Cache::remember('dashboard_stats_overview', now()->addMinutes(5), function () {
            $totalPesanan = Pesanan::count();
            $totalPelanggan = User::count();

            // pendapatan = semua transaksi dengan tipe 'pemasukan'
            $pendapatan = Transaction::pemasukan()->sum('nominal') ?? 0;
            // pengeluaran untuk perhitungan laba
            $pengeluaran = Transaction::pengeluaran()->sum('nominal') ?? 0;

            $labaBersih = $pendapatan - $pengeluaran;

            return [
                'totalPesanan' => $totalPesanan,
                'totalPelanggan' => $totalPelanggan,
                'pendapatan' => $pendapatan,
                'pengeluaran' => $pengeluaran,
                'labaBersih' => $labaBersih,
            ];
        });

        return [
            Stat::make('Total Pesanan', $metrics['totalPesanan'])
                ->description('Jumlah pesanan terdaftar')
                ->icon('heroicon-o-shopping-bag')
                ->color('success'),

            Stat::make('Pelanggan', $metrics['totalPelanggan'])
                ->description('Total pengguna terdaftar')
                ->icon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Pendapatan', 'Rp ' . number_format($metrics['pendapatan'], 0, ',', '.'))
                ->description('Total pemasukan ')
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),

            Stat::make('Laba Bersih', 'Rp ' . number_format($metrics['labaBersih'], 0, ',', '.'))
                ->description('Pendapatan - Pengeluaran')
                ->icon('heroicon-o-currency-dollar')
                ->color($metrics['labaBersih'] >= 0 ? 'success' : 'danger'),
        ];
    }
}
