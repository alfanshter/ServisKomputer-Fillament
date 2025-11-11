<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListPesanans extends ListRecords
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('tambahPesanan')
                ->label('Tambah Pesanan')
                ->icon('heroicon-o-plus')
                ->button()
                ->color('success')
                ->url(route('filament.admin.resources.pesanans.select-customer')),
        ];
    }
}
