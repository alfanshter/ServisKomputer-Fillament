<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
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
                    ->maxLength(20),

                Select::make('role')
                    ->label('Role / Jabatan')
                    ->options([
                        'admin' => 'Admin',
                        'teknisi' => 'Teknisi',
                        'marketing' => 'Marketing',
                        'supervisor' => 'Supervisor',
                    ])
                    ->default('teknisi')
                    ->required()
                    ->native(false),

                Textarea::make('address')
                    ->label('Alamat')
                    ->rows(3)
                    ->columnSpanFull(),

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
