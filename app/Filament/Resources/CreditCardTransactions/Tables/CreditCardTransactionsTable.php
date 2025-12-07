<?php

namespace App\Filament\Resources\CreditCardTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Carbon\Carbon;

class CreditCardTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('creditCard.card_name')
                    ->label('Kartu Kredit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('transaction_date')
                    ->label('Tgl Transaksi')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'overdue',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'paid',
                        'heroicon-o-exclamation-circle' => 'overdue',
                    ]),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : null),

                TextColumn::make('paid_date')
                    ->label('Tgl Bayar')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sparepartPurchaseOrder.po_number')
                    ->label('Purchase Order')
                    ->searchable()
                    ->placeholder('-')
                    ->url(fn ($record) => $record->sparepartPurchaseOrder
                        ? route('filament.admin.resources.sparepart-purchase-orders.edit', $record->sparepartPurchaseOrder)
                        : null)
                    ->color('primary')
                    ->icon('heroicon-o-shopping-bag'),

                TextColumn::make('sparepartPurchase.sparepart.name')
                    ->label('Pembelian Sparepart')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('credit_card_id')
                    ->label('Kartu Kredit')
                    ->relationship('creditCard', 'card_name'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Lunas',
                        'overdue' => 'Terlambat',
                    ]),
            ])
            ->recordActions([
                Action::make('pay_bill')
                    ->label('Bayar Tagihan')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => "Bayar Tagihan: {$record->description}")
                    ->modalDescription(fn ($record) => "Jumlah: Rp " . number_format($record->amount, 0, ',', '.'))
                    ->form([
                        DatePicker::make('paid_date')
                            ->label('Tanggal Bayar')
                            ->required()
                            ->default(now())
                            ->native(false),
                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->required()
                            ->options([
                                'transfer' => 'Transfer Bank',
                                'cash' => 'Tunai',
                                'e-wallet' => 'E-Wallet',
                            ])
                            ->default('transfer'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->payBill($data['paid_date'], $data['payment_method']);
                    })
                    ->successNotificationTitle('Tagihan berhasil dibayar'),
                Action::make('mark_as_paid')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->markAsPaid())
                    ->successNotificationTitle('Transaksi ditandai lunas'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('transaction_date', 'desc');
    }
}

