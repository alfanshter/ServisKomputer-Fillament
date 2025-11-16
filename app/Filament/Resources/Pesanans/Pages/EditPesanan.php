<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPesanan extends EditRecord
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Ini dipanggil saat form edit dibuka, untuk mengisi ulang data ke field.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        // Ambil foto before dari relasi
        $data['before_photos'] = $record->photos()
            ->where('type', 'before')
            ->pluck('path')
            ->toArray();

        // Ambil foto after dari relasi
        $data['after_photos'] = $record->photos()
            ->where('type', 'after')
            ->pluck('path')
            ->toArray();

        return $data;
    }

    /**
     * Ini untuk menyimpan ulang foto ke tabel relasi
     */
    protected function afterSave(): void
    {
        $record = $this->record;

        // Hapus foto lama
        $record->photos()->where('type', 'before')->delete();
        $record->photos()->where('type', 'after')->delete();

        // Simpan foto baru
        if (!empty($this->data['before_photos'])) {
            foreach ($this->data['before_photos'] as $path) {
                $record->photos()->create([
                    'type' => 'before',
                    'path' => $path,
                ]);
            }
        }

        if (!empty($this->data['after_photos'])) {
            foreach ($this->data['after_photos'] as $path) {
                $record->photos()->create([
                    'type' => 'after',
                    'path' => $path,
                ]);
            }
        }

        if (!empty($this->data['progress_photos'])) {
            foreach ($this->data['progress_photos'] as $path) {
                $record->photos()->create([
                    'type' => 'progress',
                    'path' => $path,
                ]);
            }
        }

        // 🔥 HANDLE SPAREPART CHANGES
        // Sparepart relationship akan otomatis di-sync oleh Filament
        // Tapi kita perlu update stok manual

        // Refresh untuk ambil data sparepart terbaru
        $record->refresh();

        // 🔥 HITUNG ULANG TOTAL COST setelah edit
        $serviceCost = $record->service_cost ?? 0;
        $sparepartCost = $record->spareparts->sum('pivot.subtotal') ?? 0;
        $totalCost = $serviceCost + $sparepartCost;

        // Update total_cost
        $record->update(['total_cost' => $totalCost]);

        // 🔥 Refresh record supaya data terbaru langsung muncul
        $record->refresh();
    }

    /**
     * Redirect setelah save agar data langsung terupdate
     */
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data pesanan berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        // Redirect ke halaman edit yang sama agar data fresh
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
