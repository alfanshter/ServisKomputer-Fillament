<?php

namespace App\Filament\Resources\CreditCards\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CreditCardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('card_name')
                    ->label('Nama Kartu')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bank_name')
                    ->label('Bank')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('card_type')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'visa',
                        'warning' => 'mastercard',
                        'danger' => 'jcb',
                        'primary' => 'amex',
                        'secondary' => 'other',
                    ]),

                TextColumn::make('card_number_last4')
                    ->label('4 Digit Terakhir')
                    ->formatStateUsing(fn($state) => "****{$state}"),

                TextColumn::make('credit_limit')
                    ->label('Limit')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('total_outstanding')
                    ->label('Outstanding')
                    ->money('IDR')
                    ->getStateUsing(fn($record) => $record->total_outstanding)
                    ->color('danger'),

                TextColumn::make('available_credit')
                    ->label('Available')
                    ->money('IDR')
                    ->getStateUsing(fn($record) => $record->available_credit)
                    ->color('success'),

                TextColumn::make('statement_day')
                    ->label('Tgl Cetak Tagihan')
                    ->sortable()
                    ->suffix(fn($state) => " (tgl {$state} tiap bulan)"),

                TextColumn::make('due_day')
                    ->label('Tgl Jatuh Tempo')
                    ->sortable()
                    ->suffix(fn($state) => " (tgl {$state} tiap bulan)"),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('card_type')
                    ->label('Tipe Kartu')
                    ->options([
                        'visa' => 'Visa',
                        'mastercard' => 'Mastercard',
                        'jcb' => 'JCB',
                        'amex' => 'American Express',
                        'other' => 'Lainnya',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Nonaktif',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

