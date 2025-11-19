<?php

namespace App\Filament\Resources\SparepartPurchaseOrders\Pages;

use App\Filament\Resources\SparepartPurchaseOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSparepartPurchaseOrder extends CreateRecord
{
    protected static string $resource = SparepartPurchaseOrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Purchase Order berhasil dibuat';
    }
}
