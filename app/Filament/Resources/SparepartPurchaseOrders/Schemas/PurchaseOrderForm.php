<?php

namespace App\Filament\Resources\SparepartPurchaseOrders\Schemas;

use App\Models\Sparepart;
use App\Models\SparepartPurchaseOrder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    TextInput::make('po_number')
                            ->label('Nomor PO')
                            ->default(fn () => SparepartPurchaseOrder::generatePONumber())
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255),

                        DatePicker::make('order_date')
                            ->label('Tanggal Order')
                            ->required()
                            ->default(now())
                            ->maxDate(now()),

                        DatePicker::make('estimated_arrival')
                            ->label('Estimasi Tiba')
                            ->minDate(now()),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Menunggu',
                                'shipped' => 'Dikirim',
                                'received' => 'Diterima',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Group::make([
                    Toggle::make('is_new_sparepart')
                            ->label('Sparepart Baru (belum ada di inventory)')
                            ->reactive()
                            ->default(false)
                            ->columnSpanFull(),

                        Select::make('sparepart_id')
                            ->label('Pilih Sparepart')
                            ->relationship('sparepart', 'name')
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get) => !$get('is_new_sparepart'))
                            ->hidden(fn (Get $get) => $get('is_new_sparepart'))
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                if ($state) {
                                    $sparepart = Sparepart::find($state);
                                    if ($sparepart) {
                                        $set('sparepart_name', $sparepart->name);
                                        $set('sku', $sparepart->sku);
                                        $set('description', $sparepart->description);
                                        $set('margin_persen', $sparepart->margin_percent ?? 0);

                                        // Auto-populate harga modal dari average_cost atau cost_price
                                        $costPrice = $sparepart->average_cost ?? $sparepart->cost_price ?? 0;
                                        $set('cost_price', $costPrice);

                                        // Update calculations
                                        self::updateCalculations($set, $get);
                                    }
                                }
                            })
                            ->columnSpanFull(),

                        TextInput::make('sparepart_name')
                            ->label('Nama Sparepart')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn (Get $get) => !$get('is_new_sparepart') && $get('sparepart_id'))
                            ->dehydrated(),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(255)
                            ->disabled(fn (Get $get) => !$get('is_new_sparepart') && $get('sparepart_id'))
                            ->dehydrated(),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->disabled(fn (Get $get) => !$get('is_new_sparepart') && $get('sparepart_id'))
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Group::make([
                    TextInput::make('quantity')
                            ->label('Jumlah Order')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateCalculations($set, $get)),

                        TextInput::make('cost_price')
                            ->label('Harga Modal per Unit')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateCalculations($set, $get))
                            ->helperText(fn (Get $get) =>
                                !$get('is_new_sparepart') && $get('sparepart_id')
                                    ? 'Harga modal terakhir dari sparepart. Bisa diedit jika harga berubah.'
                                    : 'Harga beli per unit dari supplier'
                            ),

                        TextInput::make('margin_persen')
                            ->label('Margin (%)')
                            ->required()
                            ->numeric()
                            ->suffix('%')
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(1000)
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateCalculations($set, $get)),

                        TextInput::make('total_cost')
                            ->label('Total Biaya')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Otomatis dihitung: Jumlah × Harga Modal'),

                        TextInput::make('supplier')
                            ->label('Nama Supplier')
                            ->maxLength(255),

                        TextInput::make('supplier_contact')
                            ->label('Kontak Supplier')
                            ->maxLength(255)
                            ->placeholder('No. HP, link Shopee, Tokopedia, dll')
                            ->helperText('Bisa nomor HP, link toko online, atau kontak lainnya'),

                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'paylater' => 'Paylater',
                                'visa' => 'Visa',
                                'mastercard' => 'Mastercard',
                                'tokped visa' => 'Tokped Visa',
                                'gopay later' => 'Gopay Later',
                                'seabank' => 'Seabank',
                                'BCA' => 'BCA',
                                'Mandiri' => 'Mandiri',
                            ])
                            ->default('BCA')
                            ->required()
                            ->native(false),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    protected static function updateCalculations(Set $set, Get $get): void
    {
        $quantity = (float) ($get('quantity') ?? 0);
        $costPrice = (float) ($get('cost_price') ?? 0);

        // Hitung total cost
        $totalCost = $quantity * $costPrice;
        $set('total_cost', round($totalCost, 2));
    }
}
