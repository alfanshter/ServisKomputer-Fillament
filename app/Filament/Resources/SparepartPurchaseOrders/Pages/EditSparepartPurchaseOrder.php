<?php

namespace App\Filament\Resources\SparepartPurchaseOrders\Pages;

use App\Filament\Resources\SparepartPurchaseOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditSparepartPurchaseOrder extends EditRecord
{
    protected static string $resource = SparepartPurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('receive')
                ->label('Terima Barang')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->canReceive())
                ->requiresConfirmation()
                ->modalHeading('Terima Barang')
                ->modalDescription(fn () => "Konfirmasi penerimaan {$this->record->quantity} unit {$this->record->sparepart_name}?")
                ->modalSubmitActionLabel('Ya, Terima Barang')
                ->action(function () {
                    try {
                        $this->record->receiveGoods();

                        Notification::make()
                            ->success()
                            ->title('Barang Diterima')
                            ->body("PO {$this->record->po_number} berhasil diterima. Stok telah diperbarui.")
                            ->send();

                        return redirect($this->getResource()::getUrl('index'));
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal Menerima Barang')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),

            Actions\DeleteAction::make()
                ->visible(fn () => $this->record->status !== 'received'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Purchase Order berhasil diperbarui';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Jika status diubah ke received secara manual, jalankan receiveGoods()
        if ($data['status'] === 'received' && $this->record->status !== 'received') {
            try {
                $this->record->receiveGoods();
            } catch (\Exception $e) {
                Notification::make()
                    ->danger()
                    ->title('Gagal Menerima Barang')
                    ->body($e->getMessage())
                    ->send();

                $data['status'] = $this->record->status;
            }
        }

        return $data;
    }
}
