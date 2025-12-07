<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class MyWorkStatsWidget extends BaseWidget
{
    // Widget ini hanya muncul di halaman "Pekerjaan Saya"
    protected ?string $pollingInterval = '30s'; // Auto refresh setiap 30 detik

    protected function getStats(): array
    {
        // Hitung pesanan berdasarkan status yang relevan untuk teknisi
        $belumMulai = Pesanan::where('status', 'belum mulai')->count();
        $analisa = Pesanan::where('status', 'analisa')->count();
        $dalamProses = Pesanan::where('status', 'dalam proses')->count();
        $menungguSparepart = Pesanan::where('status', 'menunggu sparepart')->count();
        $revisi = Pesanan::where('status', 'revisi')->count();
        $onHold = Pesanan::where('status', 'on hold')->count();
        $selesai = Pesanan::where('status', 'selesai')->count();

        $totalAktif = $belumMulai + $analisa + $dalamProses + $menungguSparepart + $revisi + $onHold;

        return [
            Stat::make('Perlu Dikerjakan', $totalAktif)
                ->description('Total pekerjaan aktif')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('warning'),

            Stat::make('Belum Mulai', $belumMulai)
                ->description('Pesanan baru')
                ->icon('heroicon-o-inbox')
                ->color($belumMulai > 0 ? 'danger' : 'success')
                ->extraAttributes([
                    'class' => $belumMulai > 0 ? 'animate-pulse' : '',
                ]),

            Stat::make('Sedang Analisa', $analisa)
                ->description('Dalam proses analisa')
                ->icon('heroicon-o-magnifying-glass')
                ->color('info'),

            Stat::make('Dalam Pengerjaan', $dalamProses)
                ->description('Sedang diperbaiki')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('primary'),

            Stat::make('Menunggu Sparepart', $menungguSparepart)
                ->description('Tunggu part datang')
                ->icon('heroicon-o-clock')
                ->color($menungguSparepart > 0 ? 'warning' : 'gray'),

            Stat::make('Perlu Revisi', $revisi)
                ->description('Customer minta revisi')
                ->icon('heroicon-o-arrow-path')
                ->color($revisi > 0 ? 'warning' : 'gray'),

            Stat::make('On Hold', $onHold)
                ->description('Ditunda sementara')
                ->icon('heroicon-o-pause-circle')
                ->color($onHold > 0 ? 'warning' : 'gray'),

            Stat::make('Selesai Dikerjakan', $selesai)
                ->description('Menunggu customer ambil')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
