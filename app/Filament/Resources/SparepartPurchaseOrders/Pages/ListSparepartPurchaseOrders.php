<?php

namespace App\Filament\Resources\SparepartPurchaseOrders\Pages;

use App\Filament\Resources\SparepartPurchaseOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSparepartPurchaseOrders extends ListRecords
{
    protected static string $resource = SparepartPurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
