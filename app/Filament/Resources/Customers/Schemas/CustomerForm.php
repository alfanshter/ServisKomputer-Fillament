<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Pelanggan')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('No. Telepon')
                    ->tel()
                    ->required()
                    ->maxLength(20),

                Textarea::make('address')
                    ->label('Alamat')
                    ->rows(3)
                    ->columnSpanFull(),

                // Hidden field untuk set role otomatis
                Hidden::make('role')
                    ->default('customer'),

                // Password hanya untuk create
                TextInput::make('password')
                    ->password()
                    ->label('Password')
                    ->required(fn($context) => $context === 'create')
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->same('password_confirmation')
                    ->helperText('Kosongkan jika tidak ingin mengubah password'),

                TextInput::make('password_confirmation')
                    ->password()
                    ->label('Konfirmasi Password')
                    ->required(fn($context) => $context === 'create')
                    ->dehydrated(false),
            ]);
    }
}
