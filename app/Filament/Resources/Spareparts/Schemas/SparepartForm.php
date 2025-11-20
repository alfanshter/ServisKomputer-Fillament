<?php

namespace App\Filament\Resources\Spareparts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class SparepartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Sparepart')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(100)
                    ->unique(ignoreRecord: true),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),

                Group::make([
                    TextInput::make('quantity')
                        ->label('Stok Saat Ini')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->suffix('unit'),

                    TextInput::make('min_stock')
                        ->label('Minimum Stok')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->suffix('unit')
                        ->helperText('Alert akan muncul jika stok di bawah angka ini'),
                ])
                ->columns(2),

                Group::make([
                    TextInput::make('cost_price')
                        ->label('Harga Modal Terakhir')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            // Auto-calculate average_cost jika belum ada
                            if (!$get('average_cost')) {
                                $set('average_cost', $state);
                            }

                            // Auto-calculate margin jika price dan cost_price ada
                            $price = $get('price');
                            if ($price && $state > 0) {
                                $margin = (($price - $state) / $state) * 100;
                                $set('margin_percent', round($margin, 2));
                            }
                        })
                        ->helperText('Harga beli terakhir dari supplier'),

                    TextInput::make('average_cost')
                        ->label('Harga Modal Rata-rata')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->helperText('Dihitung otomatis dari riwayat pembelian')
                        ->disabled()
                        ->dehydrated(),
                ])
                ->columns(2),

                Group::make([
                    TextInput::make('margin_percent')
                        ->label('Margin Keuntungan (%)')
                        ->numeric()
                        ->default(0)
                        ->suffix('%')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            // Auto-calculate price based on margin
                            $cost = $get('average_cost') ?: $get('cost_price');
                            if ($cost > 0 && $state >= 0) {
                                $sellingPrice = $cost + ($cost * $state / 100);
                                $set('price', round($sellingPrice, 2));
                            }
                        })
                        ->helperText('Margin keuntungan dari harga modal'),

                    TextInput::make('price')
                        ->label('Harga Jual')
                        ->required()
                        ->numeric()
                        ->default(0.0)
                        ->prefix('Rp')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            // Auto-calculate margin jika price berubah manual
                            $cost = $get('average_cost') ?: $get('cost_price');
                            if ($cost > 0 && $state > 0) {
                                $margin = (($state - $cost) / $cost) * 100;
                                $set('margin_percent', round($margin, 2));
                            }
                        })
                        ->helperText('Harga jual ke customer'),
                ])
                ->columns(2),

                TextInput::make('location')
                    ->label('Lokasi Penyimpanan')
                    ->maxLength(255)
                    ->helperText('Rak, gudang, atau lokasi fisik sparepart'),
            ]);
    }
}
