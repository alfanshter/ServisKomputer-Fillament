<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Sparepart;
use App\Models\SparepartPurchaseOrder;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // small cache to avoid heavy queries on every dashboard load
        $metrics = Cache::remember('dashboard_stats_overview', now()->addMinutes(5), function () {
            // Statistik Pesanan
            $totalPesanan = Pesanan::count();
            $pesananBelumMulai = Pesanan::where('status', 'belum mulai')->count();
            $pesananAnalisa = Pesanan::where('status', 'analisa')->count();
            $pesananKonfirmasi = Pesanan::where('status', 'konfirmasi')->count();
            $pesananDalamProses = Pesanan::whereIn('status', ['dalam proses', 'menunggu sparepart', 'on hold', 'revisi'])->count();
            $pesananSelesai = Pesanan::where('status', 'selesai')->count();
            $pesananDibayar = Pesanan::where('status', 'dibayar')->count();
            $pesananBatal = Pesanan::where('status', 'batal')->count();

            // Statistik Pelanggan
            $totalPelanggan = User::count();

            // Statistik Keuangan
            $pendapatan = Transaction::pemasukan()->sum('nominal') ?? 0;
            $pengeluaran = Transaction::pengeluaran()->sum('nominal') ?? 0;
            $labaBersih = $pendapatan - $pengeluaran;

            // Statistik Sparepart
            $totalSparepart = Sparepart::count();
            $sparepartLowStock = Sparepart::whereColumn('quantity', '<=', 'min_stock')->count();

            // Statistik PO
            $poPending = SparepartPurchaseOrder::whereIn('status', ['pending', 'shipped'])->count();

            return [
                'totalPesanan' => $totalPesanan,
                'pesananBelumMulai' => $pesananBelumMulai,
                'pesananAnalisa' => $pesananAnalisa,
                'pesananKonfirmasi' => $pesananKonfirmasi,
                'pesananDalamProses' => $pesananDalamProses,
                'pesananSelesai' => $pesananSelesai,
                'pesananDibayar' => $pesananDibayar,
                'pesananBatal' => $pesananBatal,
                'totalPelanggan' => $totalPelanggan,
                'pendapatan' => $pendapatan,
                'pengeluaran' => $pengeluaran,
                'labaBersih' => $labaBersih,
                'totalSparepart' => $totalSparepart,
                'sparepartLowStock' => $sparepartLowStock,
                'poPending' => $poPending,
            ];
        });

        return [
            // Row 1: Status Pesanan
            Stat::make('Belum Mulai', $metrics['pesananBelumMulai'])
                ->description('Pesanan baru masuk')
                ->icon('heroicon-o-inbox')
                ->color('gray'),

            Stat::make('Analisa/Konfirmasi', $metrics['pesananAnalisa'] + $metrics['pesananKonfirmasi'])
                ->description('Sedang analisa & menunggu konfirmasi')
                ->icon('heroicon-o-magnifying-glass')
                ->color('warning'),

            Stat::make('Dalam Pengerjaan', $metrics['pesananDalamProses'])
                ->description('Proses/Menunggu/On Hold/Revisi')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('info'),

            Stat::make('Selesai', $metrics['pesananSelesai'])
                ->description('Menunggu diambil & dibayar')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Sudah Dibayar', $metrics['pesananDibayar'])
                ->description('Transaksi selesai')
                ->icon('heroicon-o-banknotes')
                ->color('primary'),

            // Row 2: Keuangan
            Stat::make('Pendapatan', 'Rp ' . number_format($metrics['pendapatan'], 0, ',', '.'))
                ->description('Total pemasukan')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Pengeluaran', 'Rp ' . number_format($metrics['pengeluaran'], 0, ',', '.'))
                ->description('Total pengeluaran')
                ->icon('heroicon-o-arrow-trending-down')
                ->color('danger'),

            Stat::make('Laba Bersih', 'Rp ' . number_format($metrics['labaBersih'], 0, ',', '.'))
                ->description('Pendapatan - Pengeluaran')
                ->icon('heroicon-o-currency-dollar')
                ->color($metrics['labaBersih'] >= 0 ? 'success' : 'danger'),

            // Row 3: Inventory & Lainnya
            Stat::make('Total Pelanggan', $metrics['totalPelanggan'])
                ->description('Pelanggan terdaftar')
                ->icon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Sparepart', $metrics['totalSparepart'])
                ->description($metrics['sparepartLowStock'] > 0
                    ? "{$metrics['sparepartLowStock']} stok menipis!"
                    : 'Stok aman')
                ->icon('heroicon-o-cog')
                ->color($metrics['sparepartLowStock'] > 0 ? 'warning' : 'success'),

            Stat::make('PO Pending', $metrics['poPending'])
                ->description('Menunggu/Dalam pengiriman')
                ->icon('heroicon-o-shopping-cart')
                ->color($metrics['poPending'] > 0 ? 'warning' : 'gray'),
        ];
    }
}
