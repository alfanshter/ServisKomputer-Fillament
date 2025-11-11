<?php

namespace App\Filament\Resources\Pesanans\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Html;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 🟡 Pilihan jenis pelanggan
                Select::make('customer_type')
                    ->label('Jenis Pelanggan')
                    ->options([
                        'existing' => 'Pelanggan Lama',
                        'new' => 'Pelanggan Baru',
                    ])
                    ->placeholder('Pilih Jenis Pelanggan')
                    ->required()
                    ->live()
                    ->visibleOn('create'), // penting supaya real-time update UI

                // 🟢 Kalau Pelanggan Lama
                Select::make('user_id')
                    ->label('Pelanggan Lama')
                    ->options(function () {
                        return User::where('role', 'user')
                            ->get()
                            ->mapWithKeys(function ($user) {
                                $phone = $user->phone ?? 'No HP tidak tersedia';
                                return [
                                    $user->id => "{$user->name} ({$phone})"
                                ];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->visible(fn(Get $get) => $get('customer_type') === 'existing')
                    ->required(fn(Get $get) => $get('customer_type') === 'existing'),

                // 🆕 Kalau Pelanggan Baru
                Group::make([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required(),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required(),

                    TextInput::make('phone')
                        ->label('No. HP')
                        ->required(),

                    Textarea::make('address')
                        ->label('Alamat')
                        ->rows(2)
                        ->nullable(),
                ])
                    ->visible(fn(Get $get) => $get('customer_type') === 'new'),


                TextInput::make('device_type')
                    ->label('Jenis Perangkat')
                    ->required(),

                Textarea::make('damage_description')
                    ->label('Deskripsi Kerusakan')
                    ->rows(3)
                    ->required(),

                Textarea::make('solution')
                    ->label('Solusi')
                    ->rows(3)
                    ->nullable(),

                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),

                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->nullable(),

                Select::make('priority')
                    ->options([
                        'normal' => 'Normal',
                        'urgent' => 'Urgent',
                    ])
                    ->required(),


                TextInput::make('service_cost')
                    ->label('Biaya Servis')
                    ->numeric()
                    ->nullable(),

                TextInput::make('capital_cost')
                    ->label('Modal')
                    ->numeric()
                    ->nullable(),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->nullable(),


            ]);
    }
}
