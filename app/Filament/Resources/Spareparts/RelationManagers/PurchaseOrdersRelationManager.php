<?php

namespace App\Filament\Resources\Spareparts\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'purchaseOrders';

    protected static ?string $title = 'Riwayat Purchase Orders';

    protected static ?string $recordTitleAttribute = 'po_number';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('po_number')
                    ->label('No. PO')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('order_date')
                    ->label('Tgl Order')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric(),

                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Harga Modal')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('total_cost')
                    ->label('Total')
                    ->money('IDR')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR'),
                    ]),

                Tables\Columns\TextColumn::make('supplier')
                    ->label('Supplier')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => match($record->status) {
                        'pending' => 'warning',
                        'shipped' => 'info',
                        'received' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($record) => match($record->status) {
                        'pending' => 'Menunggu',
                        'shipped' => 'Dikirim',
                        'received' => 'Diterima',
                        'cancelled' => 'Dibatalkan',
                        default => $record->status,
                    }),

                Tables\Columns\TextColumn::make('received_date')
                    ->label('Tgl Terima')
                    ->date('d/m/Y')
                    ->placeholder('-'),
            ])
            ->defaultSort('order_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'shipped' => 'Dikirim',
                        'received' => 'Diterima',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->native(false),
            ])
            ->headerActions([
                // Bisa tambahkan create action jika perlu
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
