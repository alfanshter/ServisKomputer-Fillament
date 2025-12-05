<?php

namespace App\Filament\Resources\SparepartPurchaseOrders\Tables;

use App\Models\SparepartPurchaseOrder;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('po_number')
                    ->label('No. PO')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('order_date')
                    ->label('Tgl Order')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('sparepart_name')
                    ->label('Sparepart')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->is_new_sparepart ? '🆕 Sparepart Baru' : null),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_cost')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->money('IDR')
                            ->label('Total Order'),
                    ]),

                TextColumn::make('supplier')
                    ->label('Supplier')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('estimated_arrival')
                    ->label('Est. Tiba')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => $record->getStatusColor())
                    ->formatStateUsing(fn ($record) => $record->getStatusLabel())
                    ->sortable(),

                TextColumn::make('received_date')
                    ->label('Tgl Terima')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order_date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'rekomendasi' => 'Rekomendasi',
                        'pending' => 'Pending',
                        'shipped' => 'Dikirim',
                        'received' => 'Diterima',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->native(false),

                TernaryFilter::make('is_new_sparepart')
                    ->label('Sparepart Baru')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya sparepart baru')
                    ->falseLabel('Hanya sparepart existing'),

                Filter::make('order_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('order_date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('order_date', '<=', $date));
                    }),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('✅ Dibelikan')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'rekomendasi')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Pembelian')
                    ->modalDescription(fn ($record) => "Customer menyetujui pembelian {$record->quantity} unit {$record->sparepart_name}. Lanjutkan order ke supplier?")
                    ->modalSubmitActionLabel('Ya, Belikan')
                    ->action(function ($record) {
                        $record->update(['status' => 'pending']);

                        Notification::make()
                            ->success()
                            ->title('Pembelian Disetujui')
                            ->body("PO {$record->po_number} diubah ke status 'Pending'. Silakan order ke supplier.")
                            ->send();
                    }),

                Action::make('reject')
                    ->label('❌ Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'rekomendasi')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Rekomendasi')
                    ->modalDescription(fn ($record) => "Customer menolak pembelian {$record->sparepart_name}. PO akan dibatalkan?")
                    ->modalSubmitActionLabel('Ya, Batalkan')
                    ->action(function ($record) {
                        $record->update(['status' => 'cancelled']);

                        Notification::make()
                            ->warning()
                            ->title('Rekomendasi Ditolak')
                            ->body("PO {$record->po_number} dibatalkan karena customer menolak.")
                            ->send();
                    }),

                Action::make('receive')
                    ->label('Terima Barang')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->canReceive())
                    ->requiresConfirmation()
                    ->modalHeading('Terima Barang')
                    ->modalDescription(fn ($record) => "Konfirmasi penerimaan {$record->quantity} unit {$record->sparepart_name}?")
                    ->modalSubmitActionLabel('Ya, Terima Barang')
                    ->action(function ($record) {
                        try {
                            $record->receiveGoods();

                            Notification::make()
                                ->success()
                                ->title('Barang Diterima')
                                ->body("PO {$record->po_number} berhasil diterima. Stok telah diperbarui.")
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Gagal Menerima Barang')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),

                Action::make('ship')
                    ->label('Tandai Dikirim')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->status = 'shipped';
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Status Diperbarui')
                            ->body("PO {$record->po_number} ditandai sebagai 'Dikirim'")
                            ->send();
                    }),

                EditAction::make(),

                Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status !== 'received')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->delete()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            $receivedCount = $records->filter(fn ($r) => $r->status === 'received')->count();

                            if ($receivedCount > 0) {
                                Notification::make()
                                    ->danger()
                                    ->title('Gagal Menghapus')
                                    ->body("Tidak bisa menghapus PO yang sudah diterima")
                                    ->send();
                                return;
                            }

                            $records->each->delete();

                            Notification::make()
                                ->success()
                                ->title('Berhasil Dihapus')
                                ->send();
                        }),
                ]),
            ]);
    }
}
