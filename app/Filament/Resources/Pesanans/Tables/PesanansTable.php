<?php

namespace App\Filament\Resources\Pesanans\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->with(['user', 'spareparts']) // Eager load relasi
                    ->orderByRaw("
                    CASE status
                        WHEN 'belum mulai' THEN 1
                        WHEN 'analisa' THEN 2
                        WHEN 'selesai_analisa' THEN 3
                        WHEN 'konfirmasi' THEN 4
                        WHEN 'dalam proses' THEN 5
                        WHEN 'menunggu sparepart' THEN 6
                        WHEN 'on hold' THEN 7
                        WHEN 'revisi' THEN 8
                        WHEN 'selesai' THEN 9
                        WHEN 'dibayar' THEN 10
                        WHEN 'batal' THEN 11
                        ELSE 12
                    END
                ")
                    ->orderBy('updated_at', 'desc'); // Urutkan berdasarkan waktu edit terbaru dalam status yang sama
            })
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn ($record) => $record->user->phone ?? '-')
                    ->icon('heroicon-o-user'),
                TextColumn::make('user.phone')
                    ->label('WhatsApp')
                    ->formatStateUsing(fn ($state) => $state ? '💬 Chat' : '-')
                    ->url(fn ($record) => $record->user->phone
                        ? 'https://wa.me/' . preg_replace('/^0/', '62', $record->user->phone)
                        : null)
                    ->openUrlInNewTab()
                    ->color('success')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->tooltip(fn ($record) => $record->user->phone ? 'Klik untuk chat via WhatsApp' : 'No. HP tidak tersedia'),
                TextColumn::make('device_type')->label('Perangkat'),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'belum mulai' => 'gray',
                        'analisa' => 'info',
                        'selesai_analisa' => 'primary',
                        'konfirmasi' => 'warning',
                        'dalam proses' => 'purple',
                        'menunggu sparepart' => 'orange',
                        'on hold' => 'gray',
                        'revisi' => 'warning',
                        'selesai' => 'success',
                        'dibayar' => 'emerald',
                        'batal' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('total_cost')
                    ->money('IDR', true)
                    ->label('Total Biaya')
                    ->placeholder('-')
                    ->sortable(),

            ])
            ->filters([
                SelectFilter::make('priority')
                    ->options([
                        'normal' => 'Normal',
                        'urgent' => 'Urgent',
                    ]),
                SelectFilter::make('status')
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
                    ]),
                Filter::make('start_date')
                    ->form([
                        DatePicker::make('start_date_from')
                            ->label('Tanggal Mulai (Dari)'),
                        DatePicker::make('start_date_until')
                            ->label('Tanggal Mulai (Hingga)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['start_date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['start_date_from'] ?? null) {
                            $indicators['start_date_from'] = 'Dari ' . \Carbon\Carbon::parse($data['start_date_from'])->format('d/m/Y');
                        }
                        if ($data['start_date_until'] ?? null) {
                            $indicators['start_date_until'] = 'Hingga ' . \Carbon\Carbon::parse($data['start_date_until'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),
            ])

            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->icon('heroicon-o-cog') // opsional, bisa ganti ikon
                    ->button() // tampil sebagai tombol
                    ->label('Aksi') // teks tombol,
                    ->color('warning')
                    ->size('md') // biar sejajar
                    ->outlined() // opsional: biar gaya sama tombol lain
                    ->dropdownPlacement('bottom-end'), // posisi dropdown

                ActionGroup::make([
                    Action::make('tanda_terima')
                        ->label('Tanda Terima')
                        ->icon('heroicon-o-printer')
                        ->url(fn($record) => route('print.tanda-terima', $record->id))
                        ->openUrlInNewTab(), // biar langsung download / buka tab baru

                    Action::make('invoice')
                        ->label('Invoice')
                        ->icon('heroicon-o-document')
                        ->url(fn($record) => route('print.invoice', $record->id))
                        ->openUrlInNewTab(),

                    Action::make('laporan')
                        ->label('Laporan')
                        ->icon('heroicon-o-document-text')
                        // ->url(fn($record) => route('print.laporan', $record))
                        ->openUrlInNewTab(),
                ])
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->button()
                    ->color('success')
                    ->outlined()
                    ->dropdownPlacement('bottom-end'),

                Action::make('cancel')
                    ->label('Cancel')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->size('md')
                    ->visible(fn($record) => !in_array($record->status, ['batal', 'dibayar']))
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Pesanan?')
                    ->modalDescription('Apakah Anda yakin ingin cancel pesanan ini?')
                    ->modalSubmitActionLabel('Ya, Cancel')
                    ->form([
                        Textarea::make('cancel_notes')
                            ->label('Alasan Pembatalan')
                            ->placeholder('Masukkan alasan pembatalan pesanan...')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $oldStatus = $record->status;

                        // Jika ada sparepart yang sudah digunakan, kembalikan stoknya
                        if ($record->spareparts && $record->spareparts->count() > 0) {
                            foreach ($record->spareparts as $sparepart) {
                                $qty = $sparepart->pivot->quantity;
                                $sparepart->increment('quantity', $qty);
                            }
                        }

                        $record->update(['status' => 'batal']);

                        // Simpan history perubahan status
                        $record->statusHistories()->create([
                            'old_status' => $oldStatus,
                            'new_status' => 'batal',
                            'changed_by' => Auth::id(),
                            'notes' => $data['cancel_notes'] ?? 'Pesanan dibatalkan',
                        ]);

                        Notification::make()
                            ->title('Pesanan berhasil dibatalkan')
                            ->body('Status diubah menjadi batal dan stok sparepart dikembalikan.')
                            ->success()
                            ->send();
                    }),

                Action::make('next_status')
                    ->label(fn($record) => match ($record->status) {
                        'belum mulai' => 'Mulai Analisa',
                        'analisa' => 'Analisa Selesai',
                        'selesai_analisa' => 'Konfirmasi',
                        'konfirmasi' => 'Next Step',
                        'dalam proses' => 'Selesai',
                        'menunggu sparepart' => 'Lanjut Proses',
                        'selesai' => 'Tandai Dibayar',
                        'dibayar' => 'Revisi',
                        'batal' => 'Batalkan',
                        'revisi' => 'Revisi Selesai',
                        'on hold' => 'Lanjutkan',
                        default => 'Lanjutkan',
                    })
                    ->color('primary')
                    ->icon('heroicon-o-arrow-right')
                    ->size('md')
                    ->visible(fn($record) => in_array($record->status, [
                        'belum mulai',
                        'analisa',
                        'selesai_analisa',
                        'konfirmasi',
                        'dalam proses',
                        'menunggu sparepart',
                        'selesai',
                        'dibayar',
                        'batal',
                        'revisi',
                        'on hold'
                    ]))
                    ->modalHeading(fn($record) => match ($record->status) {
                        'konfirmasi' => 'Konfirmasi Tindakan Servis',
                        default => null,
                    })
                    ->modalSubmitAction(fn($record) => $record->status === 'konfirmasi' ? false : null)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth(fn($record) => $record->status === 'konfirmasi' ? 'md' : '2xl')

                    ->form(fn($record) => match ($record->status) {
                        'belum mulai' => [
                            FileUpload::make('before_photos')
                                ->label('Foto Sebelum Pengerjaan')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->directory('foto-before')
                                ->maxFiles(10)
                                ->required(),
                        ],
                        'analisa' => [
                            Textarea::make('analisa')
                                ->label('Catatan hasil analisa')
                                ->rows(4)
                                ->required(),
                            Textarea::make('solution')
                                ->label('Catatan Solusi')
                                ->rows(4)
                                ->required(),

                            TextInput::make('discount')
                                ->label('Diskon')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0)
                                ->helperText('Masukkan nilai diskon jika ada')
                                ->nullable(),

                            Repeater::make('spareparts')
                                ->label('Sparepart yang Digunakan')
                                ->schema([
                                    Select::make('sparepart_id')
                                        ->label('Sparepart')
                                        ->options(function () {
                                            $options = [];

                                            // 1️⃣ Sparepart yang ada stok di gudang
                                            $sparepartsInStock = \App\Models\Sparepart::query()
                                                ->where('quantity', '>', 0)
                                                ->get();

                                            foreach ($sparepartsInStock as $sp) {
                                                $options["stock_{$sp->id}"] = "📦 {$sp->name} - Stok: {$sp->quantity} - Rp" . number_format($sp->price, 0, ',', '.');
                                            }

                                            // 2️⃣ Sparepart dari Purchase Order
                                            // SEMUA status termasuk rekomendasi (hasil analisa teknisi)
                                            $sparepartsInPO = \App\Models\SparepartPurchaseOrder::query()
                                                ->whereIn('status', ['rekomendasi', 'pending', 'shipped'])
                                                ->where('quantity', '>', 0)
                                                ->with('sparepart')
                                                ->get();

                                            foreach ($sparepartsInPO as $po) {
                                                // Ambil nama dari relasi sparepart ATAU dari field sparepart_name
                                                $name = $po->sparepart?->name ?? $po->sparepart_name ?? 'Unknown';

                                                // 🔥 Hitung harga jual (cost_price + margin)
                                                if ($po->sparepart?->price) {
                                                    // Jika sparepart sudah ada, gunakan price dari master
                                                    $price = $po->sparepart->price;
                                                } else {
                                                    // Jika sparepart baru (dari PO), hitung dari cost_price + margin
                                                    $costPrice = $po->cost_price ?? 0;
                                                    $marginPersen = $po->margin_persen ?? 0;
                                                    $price = $costPrice + ($costPrice * $marginPersen / 100);
                                                }

                                                $statusLabel = match($po->status) {
                                                    'rekomendasi' => '📋 Rekomendasi',
                                                    'pending' => '⏳ Pending',
                                                    'shipped' => '🚚 Dikirim',
                                                    default => $po->status,
                                                };
                                                $options["po_{$po->id}"] = "🛒 PO: {$name} - {$statusLabel} - Qty: {$po->quantity} - Rp" . number_format($price, 0, ',', '.');
                                            }

                                            return $options;
                                        })
                                        ->searchable()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            if ($state) {
                                                // Cek apakah dari stock atau PO
                                                if (strpos($state, 'stock_') === 0) {
                                                    // Dari stok gudang
                                                    $sparepartId = str_replace('stock_', '', $state);
                                                    $sparepart = \App\Models\Sparepart::find($sparepartId);
                                                    if ($sparepart) {
                                                        $set('price', $sparepart->price);
                                                        $set('max_quantity', $sparepart->quantity);
                                                        $set('source_type', 'stock');
                                                        $set('source_id', $sparepart->id);

                                                        // Hitung subtotal langsung
                                                        $quantity = $get('quantity') ?? 1;
                                                        $set('subtotal', $quantity * $sparepart->price);
                                                    }
                                                } elseif (strpos($state, 'po_') === 0) {
                                                    // Dari Purchase Order
                                                    $poId = str_replace('po_', '', $state);
                                                    $po = \App\Models\SparepartPurchaseOrder::with('sparepart')->find($poId);
                                                    if ($po) {
                                                        // 🔥 Ambil harga jual (sudah termasuk margin)
                                                        if ($po->sparepart?->price) {
                                                            // Jika sparepart sudah ada di master, gunakan price dari master
                                                            $price = $po->sparepart->price;
                                                        } else {
                                                            // Jika sparepart baru (dari PO), hitung dari cost_price + margin
                                                            $costPrice = $po->cost_price ?? 0;
                                                            $marginPersen = $po->margin_persen ?? 0;
                                                            $price = $costPrice + ($costPrice * $marginPersen / 100);
                                                        }

                                                        $set('price', $price);
                                                        $set('max_quantity', $po->quantity);
                                                        $set('source_type', 'po');
                                                        $set('source_id', $po->id);
                                                        $set('po_id', $po->id);

                                                        // Hitung subtotal langsung
                                                        $quantity = $get('quantity') ?? 1;
                                                        $set('subtotal', $quantity * $price);
                                                    }
                                                }
                                            }
                                        })
                                        ->columnSpan(2),

                                    Hidden::make('source_type'), // 'stock' atau 'po'
                                    Hidden::make('source_id'),
                                    Hidden::make('po_id'),

                                    TextInput::make('quantity')
                                        ->label('Jumlah')
                                        ->numeric()
                                        ->default(1)
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
                                ->defaultItems(0),

                            Repeater::make('services')
                                ->label('Jasa Service yang Dilakukan')
                                ->schema([
                                    Select::make('service_id')
                                        ->label('Jasa')
                                        ->options(function () {
                                            return \App\Models\Service::query()
                                                ->where('is_active', true)
                                                ->get()
                                                ->mapWithKeys(function ($service) {
                                                    return [
                                                        $service->id => "{$service->name} ({$service->category}) - Rp" . number_format($service->price, 0, ',', '.')
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
                                                    $set('price', $service->price);

                                                    // Hitung subtotal langsung
                                                    $quantity = $get('quantity') ?? 1;
                                                    $set('subtotal', $quantity * $service->price);
                                                }
                                            }
                                        })
                                        ->columnSpan(2),

                                    TextInput::make('quantity')
                                        ->label('Jumlah')
                                        ->numeric()
                                        ->default(1)
                                        ->minValue(1)
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                            $price = $get('price') ?? 0;
                                            $set('subtotal', $state * $price);
                                        })
                                        ->columnSpan(['default' => 2, 'sm' => 1]),

                                    TextInput::make('price')
                                        ->label('Harga')
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
                                ->defaultItems(0),

                            FileUpload::make('progress_photos')
                                ->label('Foto Analisa')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->directory('progress_photos')
                                ->maxFiles(10)
                                ->required(),


                        ],
                        'selesai_analisa' => [
                            Textarea::make('template')
                                ->label('Template Chat WhatsApp')
                                ->rows(22)
                                ->helperText('Template sudah otomatis terisi. Anda bisa mengedit sebelum mengirim.')
                                ->default(function ($record) {
                                    $nama = $record->user->name ?? 'Pelanggan';
                                    $device = $record->device_type ?? 'Perangkat';
                                    $analisa = $record->analisa ?? 'Hasil analisa sedang diproses';
                                    $solusi = $record->solution ?? 'Solusi sedang diproses';

                                    // Build message
                                    $message = "Halo Kak {$nama} 👋\n\n";
                                    $message .= "Tim teknisi kami sudah melakukan pengecekan pada *{$device}* Kakak.\n\n";
                                    $message .= "📋 *HASIL ANALISA:*\n";
                                    $message .= "{$analisa}\n\n";
                                    $message .= "🔧 *SOLUSI:*\n";
                                    $message .= "{$solusi}\n\n";
                                    $message .= "💰 *RINCIAN BIAYA:*\n";

                                    // Jasa Service dari master data
                                    $totalJasaCost = 0;
                                    if ($record->services && $record->services->count() > 0) {
                                        foreach ($record->services as $service) {
                                            $qty = $service->pivot->quantity;
                                            $price = $service->pivot->price;
                                            $subtotal = $service->pivot->subtotal;
                                            $totalJasaCost += $subtotal;

                                            $priceFormat = 'Rp' . number_format($price, 0, ',', '.');
                                            $subtotalFormat = 'Rp' . number_format($subtotal, 0, ',', '.');

                                            $message .= "• {$service->name} ({$qty}x {$priceFormat}): {$subtotalFormat}\n";
                                        }
                                    }

                                    // Sparepart yang digunakan
                                    $totalSparepart = 0;
                                    if ($record->spareparts && $record->spareparts->count() > 0) {
                                        foreach ($record->spareparts as $sparepart) {
                                            $qty = $sparepart->pivot->quantity;
                                            $price = $sparepart->pivot->price;
                                            $subtotal = $sparepart->pivot->subtotal;
                                            $totalSparepart += $subtotal;

                                            $priceFormat = 'Rp' . number_format($price, 0, ',', '.');
                                            $subtotalFormat = 'Rp' . number_format($subtotal, 0, ',', '.');

                                            $message .= "• {$sparepart->name} ({$qty}x {$priceFormat}): {$subtotalFormat}\n";
                                        }
                                    }

                                    // Subtotal
                                    $subtotalAll = $totalJasaCost + $totalSparepart;
                                    $subtotalAllFormat = 'Rp' . number_format($subtotalAll, 0, ',', '.');
                                    $message .= "\n_Subtotal: {$subtotalAllFormat}_\n";

                                    // Diskon (jika ada)
                                    $diskon = $record->discount ?? 0;
                                    if ($diskon > 0) {
                                        $diskonFormat = 'Rp' . number_format($diskon, 0, ',', '.');
                                        $message .= "_Diskon: -{$diskonFormat}_\n";
                                    }

                                    // Total keseluruhan
                                    $totalBiaya = $record->total_cost ?? ($subtotalAll - $diskon);
                                    $totalBiayaFormat = 'Rp' . number_format($totalBiaya, 0, ',', '.');

                                    $message .= "\n*TOTAL BIAYA: {$totalBiayaFormat}*\n\n";
                                    $message .= "Apabila Kakak setuju, kami akan segera melanjutkan proses perbaikan.\n\n";
                                    $message .= "Mohon konfirmasinya ya Kak 🙏\n\n";
                                    $message .= "Terima kasih,\n";
                                    $message .= "*PWS Computer Service Center*";

                                    return $message;
                                })
                                ->columnSpanFull(),


                            Actions::make([
                                Action::make('send_whatsapp')
                                    ->label('Kirim ke WhatsApp')
                                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                                    ->color('success')
                                    ->url(function ($record, $get) {
                                        $phone = preg_replace('/^0/', '62', $record->user->phone ?? '');
                                        $message = urlencode($get('template'));
                                        return "https://wa.me/{$phone}?text={$message}";
                                    })
                                    ->openUrlInNewTab()
                            ])

                        ],

                        'selesai' => [
                            Select::make('metode_pembayaran')
                                ->label('Metode Pembayaran')
                                ->options([
                                    'cash' => 'Cash/Tunai',
                                    'paylater' => 'Paylater',
                                    'visa' => 'Visa',
                                    'mastercard' => 'Mastercard',
                                    'tokped visa' => 'Tokopedia Visa',
                                    'gopay later' => 'GoPay Later',
                                    'seabank' => 'SeaBank',
                                    'BCA' => 'BCA',
                                    'Mandiri' => 'Mandiri',
                                ])
                                ->required()
                                ->default('cash'),

                            Textarea::make('template')
                                ->label('Template Chat')
                                ->rows(10)
                                ->default(function ($record) {
                                    $nama = $record->user->name ?? 'Pelanggan';

                                    return <<<TEXT
                        Halo Kak {$nama} 👋

                        Terima kasih telah mempercayakan servis perangkat Kakak di *PWS Computer Service Center*.

                        Pekerjaan perbaikan sudah selesai dan perangkat telah diserahkan kembali kepada Kakak ✅

                        Semoga perangkatnya dapat kembali digunakan dengan baik dan lancar.
                        Jika di kemudian hari ada kendala atau membutuhkan bantuan lainnya, silakan hubungi kami kapan saja. Kami siap membantu 😊

                        Terima kasih atas kepercayaannya 🙏
                        Sampai jumpa di layanan servis berikutnya!

                        Salam,
                        *PWS Computer Service Center*
                        TEXT;
                                }),



                            Actions::make([
                                Action::make('send_whatsapp')
                                    ->label('Kirim ke WhatsApp')
                                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                                    ->color('success')
                                    ->color('success')
                                    ->url(function ($record, $get) {
                                        $phone = preg_replace('/^0/', '62', $record->user->phone ?? '');
                                        $message = urlencode($get('template'));
                                        return "https://wa.me/{$phone}?text={$message}";
                                    })
                                    ->openUrlInNewTab() // 💥 ini yang bikin buka di tab baru
                            ])
                        ],

                        // status konfirmasi → tampilkan pilihan aksi
                        'konfirmasi' => [
                            Placeholder::make('info')
                                ->content('Customer sudah menerima informasi biaya dan solusi perbaikan. Silakan pilih tindakan selanjutnya:')
                                ->columnSpanFull(),

                            Actions::make([
                                Action::make('batal')
                                    ->label('❌ Batalkan Servis')
                                    ->color('danger')
                                    ->icon('heroicon-o-x-circle')
                                    ->requiresConfirmation()
                                    ->modalHeading('Batalkan Servis?')
                                    ->modalDescription('Apakah Anda yakin ingin membatalkan servis ini? Customer menolak biaya atau perbaikan.')
                                    ->modalSubmitActionLabel('Ya, Batalkan')
                                    ->action(function ($record, $livewire) {
                                        $record->update(['status' => 'batal']);

                                        $record->statusHistories()->create([
                                            'old_status' => 'konfirmasi',
                                            'new_status' => 'batal',
                                            'changed_by' => Auth::id(),
                                            'notes' => 'Customer membatalkan servis',
                                        ]);

                                        Notification::make()
                                            ->title('Servis dibatalkan')
                                            ->success()
                                            ->send();

                                        // Dispatch event untuk close modal dan refresh
                                        $livewire->dispatch('close-modal', id: 'database-next_status-action');
                                    })
                                    ->closeModalByClickingAway(false),

                                Action::make('lanjut')
                                    ->label('✅ Lanjut Proses')
                                    ->color('success')
                                    ->icon('heroicon-o-check-circle')
                                    ->requiresConfirmation()
                                    ->modalHeading('Lanjut Proses Perbaikan?')
                                    ->modalDescription('Customer menyetujui biaya dan perbaikan. Lanjutkan ke proses pengerjaan?')
                                    ->modalSubmitActionLabel('Ya, Lanjutkan')
                                    ->action(function ($record, $livewire) {
                                        $record->update(['status' => 'dalam proses']);

                                        $record->statusHistories()->create([
                                            'old_status' => 'konfirmasi',
                                            'new_status' => 'dalam proses',
                                            'changed_by' => Auth::id(),
                                            'notes' => 'Customer menyetujui perbaikan',
                                        ]);

                                        Notification::make()
                                            ->title('Status diubah ke "Dalam Proses"')
                                            ->success()
                                            ->send();

                                        // Dispatch event untuk close modal dan refresh
                                        $livewire->dispatch('close-modal', id: 'database-next_status-action');
                                    })
                                    ->closeModalByClickingAway(false),
                            ])
                            ->fullWidth()
                            ->columnSpanFull(),
                        ],
                        'dalam proses' => [
                            Textarea::make('notes')
                                ->label('Catatan Hasil Pekerjaan')
                                ->rows(4)
                                ->required(),

                            FileUpload::make('after')
                                ->label('Foto Hasil')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->directory('after')
                                ->maxFiles(10)
                                ->required(),

                            Textarea::make('template')
                                ->label('Template Chat WhatsApp')
                                ->rows(20)
                                ->helperText('Template sudah otomatis terisi. Anda bisa mengedit sebelum mengirim.')
                                ->default(function ($record) {
                                    $nama = $record->user->name ?? 'Pelanggan';
                                    $device = $record->device_type ?? 'Perangkat';

                                    $message = "Halo Kak {$nama} 👋\n\n";
                                    $message .= "Kabar baik! 🎉\n\n";
                                    $message .= "*{$device}* Kakak sudah *selesai diservis* dan siap digunakan kembali ✅\n\n";
                                    $message .= "💰 *RINCIAN BIAYA:*\n";

                                    // Jasa Service dari master data
                                    $totalJasaCost = 0;
                                    if ($record->services && $record->services->count() > 0) {
                                        foreach ($record->services as $service) {
                                            $qty = $service->pivot->quantity;
                                            $price = $service->pivot->price;
                                            $subtotal = $service->pivot->subtotal;
                                            $totalJasaCost += $subtotal;

                                            $priceFormat = 'Rp' . number_format($price, 0, ',', '.');
                                            $subtotalFormat = 'Rp' . number_format($subtotal, 0, ',', '.');

                                            $message .= "• {$service->name} ({$qty}x {$priceFormat}): {$subtotalFormat}\n";
                                        }
                                    }

                                    // Sparepart yang digunakan
                                    $totalSparepart = 0;
                                    if ($record->spareparts && $record->spareparts->count() > 0) {
                                        foreach ($record->spareparts as $sparepart) {
                                            $qty = $sparepart->pivot->quantity;
                                            $price = $sparepart->pivot->price;
                                            $subtotal = $sparepart->pivot->subtotal;
                                            $totalSparepart += $subtotal;

                                            $priceFormat = 'Rp' . number_format($price, 0, ',', '.');
                                            $subtotalFormat = 'Rp' . number_format($subtotal, 0, ',', '.');

                                            $message .= "• {$sparepart->name} ({$qty}x {$priceFormat}): {$subtotalFormat}\n";
                                        }
                                    }

                                    // Subtotal
                                    $subtotalAll = $totalJasaCost + $totalSparepart;
                                    $subtotalAllFormat = 'Rp' . number_format($subtotalAll, 0, ',', '.');
                                    $message .= "\n_Subtotal: {$subtotalAllFormat}_\n";

                                    // Diskon (jika ada)
                                    $diskon = $record->discount ?? 0;
                                    if ($diskon > 0) {
                                        $diskonFormat = 'Rp' . number_format($diskon, 0, ',', '.');
                                        $message .= "_Diskon: -{$diskonFormat}_\n";
                                    }

                                    // Total keseluruhan
                                    $totalBiaya = $record->total_cost ?? ($subtotalAll - $diskon);
                                    $totalBiayaFormat = 'Rp' . number_format($totalBiaya, 0, ',', '.');

                                    $message .= "\n*TOTAL BIAYA: {$totalBiayaFormat}*\n\n";
                                    $message .= "Kakak bisa:\n";
                                    $message .= "📍 Ambil langsung di *PWS Computer Service Center*, atau\n";
                                    $message .= "🚚 Kami bantu *antar ke alamat Kakak* (biaya ongkir sesuai jarak)\n\n";
                                    $message .= "Mohon konfirmasinya ya Kak, apakah ingin *diambil sendiri* atau *dikirim*? 🙏\n\n";
                                    $message .= "Terima kasih atas kepercayaannya 💙\n";
                                    $message .= "*PWS Computer Service Center*";

                                    return $message;
                                })
                                ->columnSpanFull(),

                            Actions::make([
                                Action::make('send_whatsapp')
                                    ->label('Kirim ke WhatsApp')
                                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                                    ->color('success')
                                    ->url(function ($record, $get) {
                                        $phone = preg_replace('/^0/', '62', $record->user->phone ?? '');
                                        $message = urlencode($get('template'));
                                        return "https://wa.me/{$phone}?text={$message}";
                                    })
                                    ->openUrlInNewTab()
                            ])
                        ],
                        default => [],
                    })
                    ->action(function ($record, $data, $action) {

                        // Skip action handler untuk status konfirmasi
                        // karena sudah ditangani oleh button action sendiri
                        if ($record->status === 'konfirmasi') {
                            return;
                        }

                        $currentStatus = $record->status;

                        $nextStatus = match ($record->status) {
                            'belum mulai' => 'analisa',
                            'analisa' => 'selesai_analisa',
                            'selesai_analisa' => 'konfirmasi',
                            'konfirmasi' => 'dalam proses',
                            'dalam proses' => 'selesai',
                            'menunggu sparepart' => 'dalam proses',
                            'selesai' => 'dibayar',
                            'dibayar' => 'revisi',        // sudah final
                            'batal' => 'batal',
                            'revisi' => 'dibayar',
                            'on hold' => 'dalam proses',   // lanjut dari hold
                            default => $currentStatus,
                        };

                        // 🔥 VALIDASI STOK SPAREPART DI AWAL SEBELUM UPDATE APAPUN
                        if ($record->status === 'analisa' && !empty($data['spareparts'])) {
                            foreach ($data['spareparts'] as $sparepartData) {
                                $sparepart = \App\Models\Sparepart::find($sparepartData['sparepart_id']);

                                if ($sparepart && $sparepart->quantity < $sparepartData['quantity']) {
                                    Notification::make()
                                        ->title("Stok {$sparepart->name} tidak mencukupi!")
                                        ->body("Stok tersedia: {$sparepart->quantity}, diminta: {$sparepartData['quantity']}")
                                        ->danger()
                                        ->send();

                                    // Halt action - tetap di modal, tidak simpan apapun
                                    $action->halt();
                                }
                            }
                        }

                        if ($record->status === 'belum mulai') {
                            if (empty($data['before_photos']) || count($data['before_photos']) === 0) {
                                Notification::make()
                                    ->title('Minimal satu foto  sebelum pengerjaan wajib diunggah!')
                                    ->danger()
                                    ->send();
                                return; // hentikan proses jika belum upload
                            }

                            // Update status dulu
                            $record->update(['status' => $nextStatus]);

                            // Simpan foto ke tabel PesananOrderPhoto
                            foreach ($data['before_photos'] as $path) {
                                $record->photos()->create([
                                    'type' => 'before',
                                    'path' => $path,
                                ]);
                            }
                        }
                        // Simpan hasil analisa jika di tahap analisa
                        elseif ($record->status === 'analisa') {
                            // Hitung total biaya sparepart
                            $totalSparepartCost = 0;
                            if (!empty($data['spareparts'])) {
                                foreach ($data['spareparts'] as $sparepartData) {
                                    // Hitung subtotal jika null (fallback)
                                    $subtotal = $sparepartData['subtotal'] ?? ($sparepartData['quantity'] * $sparepartData['price']);
                                    $totalSparepartCost += $subtotal;
                                }
                            }

                            // Hitung total biaya jasa service
                            $totalServiceCost = 0;
                            if (!empty($data['services'])) {
                                foreach ($data['services'] as $serviceData) {
                                    // Hitung subtotal jika null (fallback)
                                    $subtotal = $serviceData['subtotal'] ?? ($serviceData['quantity'] * $serviceData['price']);
                                    $totalServiceCost += $subtotal;
                                }
                            }

                            // Hitung total cost (jasa + sparepart - diskon)
                            $discount = $data['discount'] ?? 0;
                            $subtotal = $totalServiceCost + $totalSparepartCost;
                            $totalCost = $subtotal - $discount;

                            $record->update([
                                'status' => $nextStatus,
                                'solution' => $data['solution'] ?? null,
                                'analisa' => $data['analisa'] ?? null,
                                'service_cost' => 0, // Set 0 karena sudah pakai master jasa
                                'discount' => $discount,
                                'total_cost' => $totalCost,
                            ]);

                            // Simpan foto ke tabel PesananOrderPhoto
                            foreach ($data['progress_photos'] as $path) {
                                $record->photos()->create([
                                    'type' => 'progress',
                                    'path' => $path,
                                ]);
                            }

                            // 🔥 Simpan jasa service
                            if (!empty($data['services'])) {
                                foreach ($data['services'] as $serviceData) {
                                    $subtotal = $serviceData['subtotal'] ?? ($serviceData['quantity'] * $serviceData['price']);

                                    // Simpan ke pivot table
                                    $record->services()->attach($serviceData['service_id'], [
                                        'quantity' => $serviceData['quantity'],
                                        'price' => $serviceData['price'],
                                        'subtotal' => $subtotal,
                                    ]);
                                }
                            }

                            // 🔥 Simpan sparepart jika ada
                            if (!empty($data['spareparts'])) {
                                foreach ($data['spareparts'] as $sparepartData) {
                                    $sourceType = $sparepartData['source_type'] ?? 'stock';
                                    $quantity = $sparepartData['quantity'];
                                    $subtotal = $sparepartData['subtotal'] ?? ($quantity * $sparepartData['price']);

                                    if ($sourceType === 'stock') {
                                        // 📦 Dari stok gudang - lakukan seperti biasa
                                        $sparepartId = str_replace('stock_', '', $sparepartData['sparepart_id']);
                                        $sparepart = \App\Models\Sparepart::find($sparepartId);

                                        if ($sparepart && $sparepart->quantity >= $quantity) {
                                            // Simpan ke pivot table
                                            $record->spareparts()->attach($sparepart->id, [
                                                'quantity' => $quantity,
                                                'price' => $sparepartData['price'],
                                                'subtotal' => $subtotal,
                                            ]);

                                            // Kurangi stok sparepart
                                            $sparepart->decrement('quantity', $quantity);
                                        }
                                    } elseif ($sourceType === 'po') {
                                        // 🛒 Dari Purchase Order
                                        $poId = $sparepartData['po_id'] ?? str_replace('po_', '', $sparepartData['sparepart_id']);
                                        $po = \App\Models\SparepartPurchaseOrder::with('sparepart')->find($poId);

                                        if ($po && $po->quantity >= $quantity) {
                                            // 1️⃣ Buat record link PO ke pesanan
                                            \App\Models\PesananPurchaseOrderItem::create([
                                                'pesanan_id' => $record->id,
                                                'purchase_order_id' => $po->id,
                                                'sparepart_id' => $po->sparepart_id ?? null,
                                                'quantity' => $quantity,
                                                'status' => 'pending',
                                            ]);

                                            // 2️⃣ JUGA simpan ke pesanan_sparepart agar muncul di invoice/WhatsApp
                                            // Jika PO punya sparepart_id, gunakan itu
                                            // Jika belum (sparepart baru), kita harus buat sparepart dulu atau skip
                                            if ($po->sparepart_id) {
                                                $record->spareparts()->attach($po->sparepart_id, [
                                                    'quantity' => $quantity,
                                                    'price' => $sparepartData['price'],
                                                    'subtotal' => $subtotal,
                                                ]);
                                            } else {
                                                // PO untuk sparepart baru yang belum ada di master
                                                // Buat sparepart baru dulu
                                                $newSparepart = \App\Models\Sparepart::create([
                                                    'name' => $po->sparepart_name,
                                                    'sku' => $po->sku,
                                                    'description' => $po->description,
                                                    'quantity' => 0, // Stok masih 0 karena belum terima barang
                                                    'min_stock' => 1,
                                                    'cost_price' => $po->cost_price,
                                                    'price' => $sparepartData['price'],
                                                    'margin_percent' => $po->margin_persen ?? 0,
                                                ]);

                                                // Update PO dengan sparepart_id baru
                                                $po->update(['sparepart_id' => $newSparepart->id]);

                                                // Attach ke pesanan
                                                $record->spareparts()->attach($newSparepart->id, [
                                                    'quantity' => $quantity,
                                                    'price' => $sparepartData['price'],
                                                    'subtotal' => $subtotal,
                                                ]);
                                            }

                                            // 3️⃣ Kurangi quantity di PO
                                            $remainingQty = $po->quantity - $quantity;
                                            $po->update(['quantity' => $remainingQty]);

                                            // JANGAN update status PO - biarkan manual via "Terima Barang"
                                            // JANGAN kurangi sparepart.quantity - barang belum datang
                                        }
                                    }
                                }
                            }

                            // 📝 SNAPSHOT: Simpan data invoice items (immutable record)
                            // Hapus invoice items lama jika ada (untuk handle re-submit)
                            $record->invoiceItems()->delete();

                            // Simpan jasa ke invoice items
                            if (!empty($data['services'])) {
                                foreach ($data['services'] as $serviceData) {
                                    $service = \App\Models\Service::find($serviceData['service_id']);
                                    $subtotal = $serviceData['subtotal'] ?? ($serviceData['quantity'] * $serviceData['price']);

                                    \App\Models\PesananInvoiceItem::create([
                                        'pesanan_id' => $record->id,
                                        'item_type' => 'service',
                                        'item_name' => $service->name ?? 'Unknown Service',
                                        'item_description' => $service->category ?? null,
                                        'quantity' => $serviceData['quantity'],
                                        'price' => $serviceData['price'],
                                        'subtotal' => $subtotal,
                                        'source' => 'master',
                                        'source_id' => $serviceData['service_id'],
                                    ]);
                                }
                            }

                            // Simpan sparepart ke invoice items
                            if (!empty($data['spareparts'])) {
                                foreach ($data['spareparts'] as $sparepartData) {
                                    $sourceType = $sparepartData['source_type'] ?? 'stock';
                                    $quantity = $sparepartData['quantity'];
                                    $subtotal = $sparepartData['subtotal'] ?? ($quantity * $sparepartData['price']);

                                    if ($sourceType === 'stock') {
                                        $sparepartId = str_replace('stock_', '', $sparepartData['sparepart_id']);
                                        $sparepart = \App\Models\Sparepart::find($sparepartId);

                                        if ($sparepart) {
                                            \App\Models\PesananInvoiceItem::create([
                                                'pesanan_id' => $record->id,
                                                'item_type' => 'sparepart',
                                                'item_name' => $sparepart->name,
                                                'item_description' => $sparepart->sku ?? null,
                                                'quantity' => $quantity,
                                                'price' => $sparepartData['price'],
                                                'subtotal' => $subtotal,
                                                'source' => 'stock',
                                                'source_id' => $sparepart->id,
                                            ]);
                                        }
                                    } elseif ($sourceType === 'po') {
                                        $poId = $sparepartData['po_id'] ?? str_replace('po_', '', $sparepartData['sparepart_id']);
                                        $po = \App\Models\SparepartPurchaseOrder::with('sparepart')->find($poId);

                                        if ($po) {
                                            $sparepartName = $po->sparepart?->name ?? $po->sparepart_name ?? 'Unknown Sparepart';
                                            $sku = $po->sparepart?->sku ?? $po->sku ?? null;

                                            \App\Models\PesananInvoiceItem::create([
                                                'pesanan_id' => $record->id,
                                                'item_type' => 'sparepart',
                                                'item_name' => $sparepartName,
                                                'item_description' => $sku,
                                                'quantity' => $quantity,
                                                'price' => $sparepartData['price'],
                                                'subtotal' => $subtotal,
                                                'source' => 'po',
                                                'source_id' => $po->id,
                                            ]);
                                        }
                                    }
                                }
                            }
                        } elseif ($record->status === 'dalam proses') {
                            $record->update([
                                'status' => $nextStatus,
                                'notes' => $data['notes'] ?? null,
                            ]);

                            // Simpan foto ke tabel PesananOrderPhoto
                            foreach ($data['after'] as $path) {
                                $record->photos()->create([
                                    'type' => 'after',
                                    'path' => $path,
                                ]);
                            }
                        } elseif ($record->status === 'selesai') {
                            // Update status ke dibayar
                            $record->update(['status' => $nextStatus]);

                            // 🔥 Hitung total biaya (untuk data lama yang total_cost masih NULL/0)
                            $totalBiaya = $record->total_cost;

                            // Jika total_cost kosong, hitung manual
                            if (!$totalBiaya || $totalBiaya == 0) {
                                $serviceCost = $record->service_cost ?? 0;
                                $sparepartCost = $record->spareparts->sum('pivot.subtotal') ?? 0;
                                $totalBiaya = $serviceCost + $sparepartCost;

                                // Update total_cost di pesanan untuk data consistency
                                $record->update(['total_cost' => $totalBiaya]);
                            }

                            // 🔥 AUTO CREATE TRANSACTION - PEMASUKAN
                            \App\Models\Transaction::create([
                                'tanggal' => now(),
                                'tipe' => 'pemasukan',
                                'kategori' => 'pemasukan', // sesuai ENUM di database
                                'deskripsi' => "Pembayaran servis {$record->device_type} - {$record->user->name}",
                                'nominal' => $totalBiaya,
                                'metode_pembayaran' => $data['metode_pembayaran'] ?? 'cash',
                                'referensi' => $record->id,
                            ]);

                            Notification::make()
                                ->title('Transaksi berhasil dicatat!')
                                ->body("Pembayaran Rp" . number_format($totalBiaya, 0, ',', '.') . " telah masuk ke catatan keuangan.")
                                ->success()
                                ->send();
                        } else {
                            $record->update(['status' => $nextStatus]);
                        }

                        // 🔥 SIMPAN HISTORY PERUBAHAN STATUS
                        $record->statusHistories()->create([
                            'old_status' => $currentStatus,
                            'new_status' => $nextStatus,
                            'changed_by' => Auth::id(),
                            'notes' => $data['analisa'] ?? $data['notes'] ?? null,
                        ]);

                        Notification::make()
                            ->title("Status diperbarui menjadi: {$nextStatus}")
                            ->success()
                            ->send();

                        // // Optional: tampilkan toast notif
                        // $this->notify('success', "Status pesanan berhasil diubah menjadi {$nextStatus}");
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
