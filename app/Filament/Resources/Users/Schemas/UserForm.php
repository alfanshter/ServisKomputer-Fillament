<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(fn($context) => $context === 'create')
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state)) // hanya disimpan jika diisi
                    ->same('password_confirmation')
                    ->label('Password'),

                TextInput::make('password_confirmation')
                    ->password()
                    ->label('Confirm Password')
                    ->required(fn($context) => $context === 'create')
                    ->dehydrated(false), // tidak disimpan ke DB

                TextInput::make('phone')
                    ->tel(),
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'customer' => 'Customer',
                        'admin' => 'Admin',
                        'teknisi' => 'Teknisi',
                        'marketing' => 'Marketing',
                        'supervisor' => 'Supervisor',
                    ])->default('Customer')
                    ->required(),
                Textarea::make('address')
                    ->columnSpanFull(),
            ]);
    }
}
