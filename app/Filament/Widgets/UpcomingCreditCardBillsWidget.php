<?php

namespace App\Filament\Widgets;

use App\Models\CreditCardTransaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;

class UpcomingCreditCardBillsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CreditCardTransaction::query()
                    ->where('status', 'pending')
                    ->whereNotNull('due_date')
                    ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(30)])
                    ->with(['creditCard', 'sparepartPurchase.sparepart'])
                    ->orderBy('due_date', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : (
                        Carbon::now()->diffInDays($record->due_date, false) <= 7 ? 'warning' : 'success'
                    ))
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('creditCard.card_name')
                    ->label('Kartu Kredit')
                    ->searchable()
                    ->icon('heroicon-o-credit-card'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('days_until_due')
                    ->label('Sisa Waktu')
                    ->getStateUsing(function ($record) {
                        $days = Carbon::now()->diffInDays($record->due_date, false);
                        if ($days < 0) {
                            return abs($days) . ' hari terlambat';
                        } elseif ($days == 0) {
                            return 'Jatuh tempo hari ini!';
                        } else {
                            return $days . ' hari lagi';
                        }
                    })
                    ->color(fn ($record) => Carbon::now()->diffInDays($record->due_date, false) <= 7 ? 'danger' : 'success'),
            ])
            ->heading('Tagihan Kartu Kredit yang Akan Jatuh Tempo (30 Hari Kedepan)')
            ->emptyStateHeading('Tidak ada tagihan yang akan jatuh tempo')
            ->emptyStateDescription('Semua tagihan kartu kredit sudah dibayar atau belum ada transaksi')
            ->emptyStateIcon('heroicon-o-check-circle');
    }

    public function getDisplayName(): string
    {
        return 'Upcoming Credit Card Bills';
    }
}

