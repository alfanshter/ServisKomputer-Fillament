<?php

namespace App\Filament\Resources\Spareparts\RelationManagers;

use App\Models\CreditCard;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'purchases';

    protected static ?string $title = 'Riwayat Pembelian';

    protected static ?string $recordTitleAttribute = 'purchase_date';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('purchase_date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric(),

                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Harga Modal')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('margin_persen')
                    ->label('Margin')
                    ->suffix('%')
                    ->numeric(2),

                Tables\Columns\TextColumn::make('harga_jual')
                    ->label('Harga Jual')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('total_cost')
                    ->label('Total')
                    ->money('IDR')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR')
                            ->label('Total'),
                    ]),

                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->colors([
                        'success' => 'cash',
                        'primary' => 'transfer',
                        'warning' => 'credit_card',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                        'credit_card' => 'Kartu Kredit',
                        default => '-',
                    }),

                Tables\Columns\TextColumn::make('creditCard.card_name')
                    ->label('Kartu Kredit')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('supplier')
                    ->label('Supplier')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(50)
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->defaultSort('purchase_date', 'desc')
            ->filters([])
            ->headerActions([
                // User dapat menambahkan purchase manual via menu utama
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
