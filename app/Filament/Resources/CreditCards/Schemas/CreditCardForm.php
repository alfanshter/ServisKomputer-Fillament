<?php

namespace App\Filament\Resources\CreditCards\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class CreditCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    TextInput::make('card_name')
                        ->label('Nama Kartu')
                        ->placeholder('e.g., BCA Visa Platinum')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('bank_name')
                        ->label('Nama Bank')
                        ->placeholder('e.g., BCA, Mandiri, BNI')
                        ->required()
                        ->maxLength(255),

                    Select::make('card_type')
                        ->label('Tipe Kartu')
                        ->options([
                            'visa' => 'Visa',
                            'mastercard' => 'Mastercard',
                            'jcb' => 'JCB',
                            'amex' => 'American Express',
                            'other' => 'Lainnya',
                        ])
                        ->default('visa')
                        ->required(),

                    TextInput::make('card_number_last4')
                        ->label('4 Digit Terakhir')
                        ->placeholder('1234')
                        ->maxLength(4)
                        ->minLength(4)
                        ->numeric()
                        ->required(),

                    TextInput::make('credit_limit')
                        ->label('Limit Kredit')
                        ->numeric()
                        ->prefix('Rp')
                        ->required()
                        ->default(0),

                    TextInput::make('statement_day')
                        ->label('Tanggal Cetak Tagihan')
                        ->helperText('Tanggal cetak tagihan setiap bulan (1-31). Contoh: 26 = tagihan dicetak setiap tgl 26')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(31)
                        ->required()
                        ->placeholder('26'),

                    TextInput::make('due_day')
                        ->label('Tanggal Jatuh Tempo')
                        ->helperText('Tanggal jatuh tempo pembayaran setiap bulan (1-31). Contoh: 11 = bayar sebelum tgl 11')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(31)
                        ->required()
                        ->placeholder('11'),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(3),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true)
                        ->helperText('Nonaktifkan jika kartu sudah tidak digunakan'),
                ]),
            ]);
    }
}


