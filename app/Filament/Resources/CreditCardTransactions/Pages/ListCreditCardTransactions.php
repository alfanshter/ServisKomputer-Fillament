<?php

namespace App\Filament\Resources\CreditCardTransactions\Pages;

use App\Filament\Resources\CreditCardTransactions\CreditCardTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCreditCardTransactions extends ListRecords
{
    protected static string $resource = CreditCardTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
