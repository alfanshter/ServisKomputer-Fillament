<?php

namespace App\Filament\Resources\Pesanans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatusHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistories';

    protected static ?string $title = 'Timeline Riwayat Status';

    protected static ?string $recordTitleAttribute = 'new_status';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal & Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->size('sm'),
                
                TextColumn::make('old_status')
                    ->label('Status Lama')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? $this->getStatusLabel($state) : '-')
                    ->color(fn ($state) => $this->getStatusColor($state))
                    ->size('sm'),
                
                TextColumn::make('new_status')
                    ->label('Status Baru')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $this->getStatusLabel($state))
                    ->color(fn ($state) => $this->getStatusColor($state))
                    ->size('sm'),
                
                TextColumn::make('user.name')
                    ->label('Diubah Oleh')
                    ->default('-')
                    ->icon('heroicon-o-user')
                    ->size('sm'),
                
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(50)
                    ->default('-')
                    ->wrap()
                    ->size('sm'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false); // tampilkan semua history tanpa pagination
    }

    private function getStatusLabel($status): string
    {
        return match ($status) {
            'belum mulai' => 'Belum Mulai',
            'analisa' => 'Analisa',
            'selesai_analisa' => 'Selesai Analisa',
            'konfirmasi' => 'Konfirmasi',
            'dalam proses' => 'Dalam Proses',
            'menunggu sparepart' => 'Menunggu Sparepart',
            'on hold' => 'On Hold',
            'revisi' => 'Revisi',
            'selesai' => 'Selesai',
            'dibayar' => 'Dibayar',
            'batal' => 'Batal',
            default => ucwords(str_replace('_', ' ', $status ?? '')),
        };
    }

    private function getStatusColor($status): string
    {
        return match ($status) {
            'belum mulai' => 'gray',
            'analisa' => 'info',
            'selesai_analisa' => 'primary',
            'konfirmasi' => 'warning',
            'dalam proses' => 'primary',
            'menunggu sparepart' => 'warning',
            'on hold' => 'gray',
            'revisi' => 'danger',
            'selesai' => 'success',
            'dibayar' => 'success',
            'batal' => 'danger',
            default => 'gray',
        };
    }
}
