<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama Jasa')
                    ->placeholder('Contoh: Ganti Keyboard, Install Windows 11, dll')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),

                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'Hardware' => 'Hardware',
                        'Software' => 'Software',
                        'Cleaning' => 'Cleaning & Maintenance',
                        'Upgrade' => 'Upgrade',
                        'Repair' => 'Repair',
                        'Installation' => 'Installation',
                        'Other' => 'Lainnya',
                    ])
                    ->searchable()
                    ->placeholder('Pilih kategori jasa')
                    ->columnSpan(1),

                TextInput::make('price')
                    ->label('Harga Standar')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp')
                    ->helperText('Harga dapat diubah saat digunakan di pesanan')
                    ->columnSpan(1),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->inline(false)
                    ->helperText('Jasa yang non-aktif tidak akan muncul di pilihan')
                    ->columnSpan(1),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Jelaskan detail jasa ini...')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
