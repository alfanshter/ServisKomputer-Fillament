<?php

namespace App\Filament\Resources\CreditCardTransactions\Schemas;

use App\Models\CreditCard;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class CreditCardTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Select::make('credit_card_id')
                        ->label('Kartu Kredit')
                        ->options(CreditCard::active()->pluck('card_name', 'id'))
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $card = CreditCard::find($state);
                                if ($card) {
                                    // Auto-set billing and due date based on card settings
                                    $set('billing_date', $card->next_billing_date);
                                    $set('due_date', $card->next_due_date);
                                }
                            }
                        }),

                    DatePicker::make('transaction_date')
                        ->label('Tanggal Transaksi')
                        ->required()
                        ->default(now()),

                    TextInput::make('description')
                        ->label('Deskripsi')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('amount')
                        ->label('Jumlah')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Lunas',
                            'overdue' => 'Terlambat',
                        ])
                        ->default('pending')
                        ->required(),

                    DatePicker::make('billing_date')
                        ->label('Tanggal Tagihan')
                        ->nullable(),

                    DatePicker::make('due_date')
                        ->label('Tanggal Jatuh Tempo')
                        ->nullable(),

                    DatePicker::make('paid_date')
                        ->label('Tanggal Bayar')
                        ->nullable()
                        ->visible(fn ($get) => $get('status') === 'paid'),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(3),
                ]),
            ]);
    }
}

