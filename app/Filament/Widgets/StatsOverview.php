<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pesanan', 120)
                ->description('Pesanan bulan ini')
                ->icon('heroicon-o-shopping-bag')
                ->color('success'),

            Stat::make('Pelanggan Aktif', 56)
                ->description('Pelanggan terdaftar')
                ->icon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Pendapatan', 'Rp ' . number_format(3250000))
                ->description('Total bulan ini')
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),
        ];
    }
}
