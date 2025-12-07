<?php

namespace App\Filament\Resources\SparepartPurchaseOrders\Pages;

use App\Filament\Resources\SparepartPurchaseOrderResource;
use App\Models\CreditCard;
use App\Models\CreditCardTransaction;
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

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Auto-create credit card transaction if payment method is credit_card
        if ($record->payment_method === 'credit_card' && $record->credit_card_id) {
            $creditCard = CreditCard::find($record->credit_card_id);

            if ($creditCard) {
                // Calculate due date based on transaction date
                $transactionDate = \Carbon\Carbon::parse($record->order_date);
                $dueDate = $creditCard->calculateDueDate($transactionDate);

                // Calculate billing/statement date (statement date of the period)
                $statementDate = $dueDate->copy()->subMonth()->day($creditCard->statement_day);

                CreditCardTransaction::create([
                    'credit_card_id' => $record->credit_card_id,
                    'sparepart_purchase_order_id' => $record->id,
                    'transaction_date' => $record->order_date,
                    'description' => "Purchase Order: {$record->po_number} - {$record->sparepart_name}",
                    'amount' => $record->total_cost,
                    'status' => 'pending',
                    'billing_date' => $statementDate,
                    'due_date' => $dueDate,
                    'notes' => "Auto-created from PO {$record->po_number}. Statement: {$statementDate->format('d M Y')}, Due: {$dueDate->format('d M Y')}",
                ]);
            }
        }
    }
}

