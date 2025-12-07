<?php

namespace App\Filament\Resources\CreditCardTransactions\Pages;

use App\Filament\Resources\CreditCardTransactions\CreditCardTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCreditCardTransaction extends EditRecord
{
    protected static string $resource = CreditCardTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
