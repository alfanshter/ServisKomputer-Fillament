<?php

namespace App\Filament\Widgets;

use App\Models\CreditCard;
use App\Models\CreditCardTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CreditCardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Cache untuk menghindari query berat setiap reload
        $metrics = Cache::remember('credit_card_stats', now()->addMinutes(5), function () {
            $totalCards = CreditCard::active()->count();
            $totalLimit = CreditCard::active()->sum('credit_limit');
            $totalOutstanding = CreditCardTransaction::pending()->sum('amount');
            $availableCredit = $totalLimit - $totalOutstanding;

            // Transaksi jatuh tempo bulan ini
            $dueThisMonth = CreditCardTransaction::dueThisMonth()->sum('amount');

            // Transaksi yang sudah terlambat
            $overdueAmount = CreditCardTransaction::overdue()->sum('amount');
            $overdueCount = CreditCardTransaction::overdue()->count();

            // Transaksi yang jatuh tempo dalam 7 hari
            $dueSoon = CreditCardTransaction::pending()
                ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(7)])
                ->sum('amount');

            return [
                'totalCards' => $totalCards,
                'totalLimit' => $totalLimit,
                'totalOutstanding' => $totalOutstanding,
                'availableCredit' => $availableCredit,
                'dueThisMonth' => $dueThisMonth,
                'overdueAmount' => $overdueAmount,
                'overdueCount' => $overdueCount,
                'dueSoon' => $dueSoon,
            ];
        });

        return [
            Stat::make('Total Kartu Kredit Aktif', $metrics['totalCards'])
                ->description('Kartu yang sedang digunakan')
                ->icon('heroicon-o-credit-card')
                ->color('primary'),

            Stat::make('Total Outstanding', 'Rp ' . number_format($metrics['totalOutstanding'], 0, ',', '.'))
                ->description('Belum dibayar')
                ->icon('heroicon-o-banknotes')
                ->color('warning'),

            Stat::make('Jatuh Tempo Bulan Ini', 'Rp ' . number_format($metrics['dueThisMonth'], 0, ',', '.'))
                ->description('Harus dibayar bulan ini')
                ->icon('heroicon-o-calendar')
                ->color('info'),

            Stat::make('Terlambat', 'Rp ' . number_format($metrics['overdueAmount'], 0, ',', '.'))
                ->description($metrics['overdueCount'] . ' transaksi overdue')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),

            Stat::make('Jatuh Tempo 7 Hari', 'Rp ' . number_format($metrics['dueSoon'], 0, ',', '.'))
                ->description('Segera dibayar')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Kredit Tersedia', 'Rp ' . number_format($metrics['availableCredit'], 0, ',', '.'))
                ->description('dari total limit Rp ' . number_format($metrics['totalLimit'], 0, ',', '.'))
                ->icon('heroicon-o-wallet')
                ->color('success'),
        ];
    }

    protected function getColumns(): int
    {
        return 3; // 3 columns layout
    }

    public function getDisplayName(): string
    {
        return 'Statistik Kartu Kredit';
    }

    protected static ?int $sort = 2; // Display after main stats
}

