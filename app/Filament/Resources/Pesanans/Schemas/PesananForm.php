<?php

namespace App\Filament\Resources\Pesanans\Schemas;

use App\Filament\Forms\Components\SelectUserWithModal;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
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
                    ->default('default')
                    ->required()
                    ->live()
                    ->visibleOn('create'), // penting supaya real-time update UI

                // 🟢 Kalau Pelanggan Lama
                SelectUserWithModal::make('user_id')
                    ->label('Pelanggan')
                    ->visibleOn('create')
                    ->visible(fn(Get $get) => $get('customer_type') === 'existing')
                    ->required(fn(Get $get) => $get('customer_type') === 'existing'),

                // Di halaman edit, tampilkan user_id tanpa kondisi customer_type
                SelectUserWithModal::make('user_id')
                    ->label('Pelanggan')
                    ->visibleOn('edit')
                    ->required(),

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

                Textarea::make('kelengkapan')
                    ->label('Kelengkapan')
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

                Select::make('status')
                    ->label('Status Saat Ini')
                    ->visibleOn('edit')
                    ->options([
                        'belum mulai' => 'Belum Mulai',
                        'analisa' => 'Analisa',
                        'selesai_analisa' => 'Selesai Analisa',
                        'konfirmasi' => 'Konfirmasi',
                        'dalam proses' => 'Dalam Proses',
                        'menunggu sparepart' => 'Menunggu Sparepart',
                        'on hold' => 'On Hold',
                        'revisi' => 'Revisi',
                        'selesai' => 'Selesai',
                        'dibayar' => 'Dibayar',
                        'batal' => 'Batal',
                    ])
                    ->disabled()
                    ->dehydrated()
                    ->helperText('⚠️ Status hanya bisa diubah melalui tombol action di tabel'),

                TextInput::make('total_cost')
                    ->label('Total Biaya')
                    ->visibleOn('edit')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Total otomatis dihitung (Jasa + Sparepart - Diskon)'),

                TextInput::make('discount')
                    ->label('Diskon')
                    ->visibleOn('edit')
                    ->numeric()
                    ->prefix('Rp')
                    ->nullable()
                    ->helperText('Masukkan nilai diskon jika ada'),

                // 🔧 Edit Sparepart - hanya visible jika status sudah selesai_analisa atau lebih
                Repeater::make('spareparts_edit')
                    ->label('Sparepart yang Digunakan')
                    ->visibleOn('edit')
                    ->visible(fn($record) => $record && in_array($record->status, [
                        'selesai_analisa', 'konfirmasi', 'dalam proses',
                        'menunggu sparepart', 'on hold', 'revisi', 'selesai', 'dibayar'
                    ]))
                    ->schema([
                        Select::make('sparepart_id')
                            ->label('Sparepart')
                            ->options(function () {
                                return \App\Models\Sparepart::query()
                                    ->get()
                                    ->mapWithKeys(function ($sparepart) {
                                        return [
                                            $sparepart->id => "{$sparepart->name} - Stok: {$sparepart->quantity} - Rp" . number_format($sparepart->price, 0, ',', '.')
                                        ];
                                    });
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $sparepart = \App\Models\Sparepart::find($state);
                                    if ($sparepart) {
                                        // Hanya set jika belum ada nilai (item baru)
                                        if (!$get('quantity')) {
                                            $set('quantity', 1);
                                        }
                                        if (!$get('price')) {
                                            $set('price', $sparepart->price);
                                        }

                                        // Hitung subtotal
                                        $quantity = $get('quantity') ?? 1;
                                        $price = $get('price') ?? $sparepart->price;
                                        $set('subtotal', $quantity * $price);
                                    }
                                }
                            })
                            ->columnSpan(2),

                        TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $price = $get('price') ?? 0;
                                $set('subtotal', $state * $price);
                            })
                            ->columnSpan(['default' => 2, 'sm' => 1]),

                        TextInput::make('price')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $quantity = $get('quantity') ?? 1;
                                $set('subtotal', $state * $quantity);
                            })
                            ->columnSpan(['default' => 3, 'sm' => 2]),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(['default' => 3, 'sm' => 2]),
                    ])
                    ->columns(['default' => 2, 'sm' => 5])
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Sparepart')
                    ->collapsible()
                    ->defaultItems(0)
                    ->helperText('⚠️ Hati-hati: Mengubah sparepart akan mempengaruhi stok!'),

                // 🔧 Edit Jasa (Services) - hanya visible jika status sudah selesai_analisa atau lebih
                Repeater::make('services_edit')
                    ->label('Jasa yang Digunakan')
                    ->visibleOn('edit')
                    ->visible(fn($record) => $record && in_array($record->status, [
                        'selesai_analisa', 'konfirmasi', 'dalam proses',
                        'menunggu sparepart', 'on hold', 'revisi', 'selesai', 'dibayar'
                    ]))
                    ->schema([
                        Select::make('service_id')
                            ->label('Jasa')
                            ->options(function () {
                                return \App\Models\Service::query()
                                    ->where('is_active', true)
                                    ->get()
                                    ->mapWithKeys(function ($service) {
                                        return [
                                            $service->id => "{$service->name} - Rp" . number_format($service->price, 0, ',', '.')
                                        ];
                                    });
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $service = \App\Models\Service::find($state);
                                    if ($service) {
                                        // Hanya set jika belum ada nilai (item baru)
                                        if (!$get('quantity')) {
                                            $set('quantity', 1);
                                        }
                                        if (!$get('price')) {
                                            $set('price', $service->price);
                                        }

                                        // Hitung subtotal
                                        $quantity = $get('quantity') ?? 1;
                                        $price = $get('price') ?? $service->price;
                                        $set('subtotal', $quantity * $price);
                                    }
                                }
                            })
                            ->columnSpan(2),

                        TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $price = $get('price') ?? 0;
                                $set('subtotal', $state * $price);
                            })
                            ->columnSpan(['default' => 2, 'sm' => 1]),

                        TextInput::make('price')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $quantity = $get('quantity') ?? 1;
                                $set('subtotal', $state * $quantity);
                            })
                            ->columnSpan(['default' => 3, 'sm' => 2]),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(['default' => 3, 'sm' => 2]),
                    ])
                    ->columns(['default' => 2, 'sm' => 5])
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Jasa')
                    ->collapsible()
                    ->defaultItems(0)
                    ->helperText('Tambahkan jasa yang digunakan untuk pesanan ini'),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->nullable(),


            ]);
    }
}
