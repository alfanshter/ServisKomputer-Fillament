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
    }
}
