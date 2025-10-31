<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                    ->required(),
                Select::make('tipe')
                    ->options(['pemasukan' => 'Pemasukan', 'pengeluaran' => 'Pengeluaran'])
                    ->required(),
                Select::make('kategori')
                    ->options([
            'pemasukan' => 'Pemasukan',
            'pengeluaran sparepart' => 'Pengeluaran sparepart',
            'pengeluaran operasional' => 'Pengeluaran operasional',
            'marketing' => 'Marketing',
            'sodaqoh' => 'Sodaqoh',
            'alat bahan' => 'Alat bahan',
            'gaji karyawan' => 'Gaji karyawan',
            'pengeluaran wajib' => 'Pengeluaran wajib',
        ])
                    ->required(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('nominal')
                    ->required()
                    ->numeric(),
                Select::make('metode_pembayaran')
                    ->options([
            'cash' => 'Cash',
            'paylater' => 'Paylater',
            'visa' => 'Visa',
            'mastercard' => 'Mastercard',
            'tokped visa' => 'Tokped visa',
            'gopay later' => 'Gopay later',
            'seabank' => 'Seabank',
            'BCA' => 'B c a',
            'Mandiri' => 'Mandiri',
        ])
                    ->required(),
                TextInput::make('referensi'),
            ]);
    }
}
