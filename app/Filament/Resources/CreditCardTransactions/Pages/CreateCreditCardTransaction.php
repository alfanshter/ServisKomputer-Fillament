<?php

namespace App\Filament\Resources\CreditCardTransactions\Pages;

use App\Filament\Resources\CreditCardTransactions\CreditCardTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCreditCardTransaction extends CreateRecord
{
    protected static string $resource = CreditCardTransactionResource::class;
}
