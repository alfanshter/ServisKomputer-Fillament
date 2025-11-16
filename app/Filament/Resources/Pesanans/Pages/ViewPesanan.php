<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;

class ViewPesanan extends ViewRecord
{
    protected static string $resource = PesananResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Eager load relasi spareparts untuk performa lebih baik
        $this->record->load('spareparts');

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }


}
