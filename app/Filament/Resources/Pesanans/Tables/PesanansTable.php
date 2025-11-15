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
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderByRaw("
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
                ")->orderBy('start_date', 'desc');
            })
            ->columns([
                TextColumn::make('user.name')->label('Customer')->searchable(),
                TextColumn::make('device_type')->label('Perangkat'),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('service_cost')->money('IDR', true)->label('Biaya'),

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
                        // ->url(fn($record) => route('print.invoice', $record))
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

                            TextInput::make('service_cost')
                                ->label('Biaya Servis')
                                ->numeric()
                                ->nullable(),
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
                                ->label('Template Chat')
                                ->rows(10)
                                ->default(function ($record) {
                                    $nama = $record->user->name ?? 'Pelanggan';

                                    return <<<TEXT
Halo Kak {$nama} 👋

Tim teknisi kami sudah melakukan pengecekan pada laptop Kakak.

Ditemukan bahwa penyebab masalah berasal dari *SSD yang sudah tidak terbaca dengan baik*, sehingga perlu dilakukan *penggantian SSD baru*.

Estimasi biaya perbaikan:
💻 Ganti SSD: *Rp350.000*

Apabila Kakak setuju, kami akan segera melanjutkan proses penggantian.

Mohon konfirmasinya ya Kak 🙏

Terima kasih,
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

                        'selesai' => [
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
                                ->content('Pilih tindakan untuk servis ini:'),
                            Actions::make([
                                Action::make('batal')
                                    ->label('Batalkan Servis')
                                    ->color('danger')
                                    ->requiresConfirmation()
                                    ->action(function ($record) {
                                        $record->update(['status' => 'batal']);
                                    }),
                                Action::make('lanjut')
                                    ->label('Lanjut Proses')
                                    ->color('success')
                                    ->action(function ($record) {
                                        $record->update(['status' => 'dalam proses']);
                                    }),
                            ])->fullWidth(),
                        ],
                        'dalam proses' => [
                            Textarea::make('analisa')
                                ->label('Catatan hasil Pekerjaan')
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
                                ->label('Template Chat')
                                ->rows(10)
                                ->default(function ($record) {
                                    $nama = $record->user->name ?? 'Pelanggan';
                                    $biaya = number_format($record->service_cost ?? 0, 0, ',', '.');

                                    return <<<TEXT
                            Halo Kak {$nama} 👋

                            Kabar baik! 🙌
                            Laptop Kakak sudah *selesai diservis* dan siap digunakan kembali ✅

                            Total biaya servis: *Rp{$biaya}*

                            Kakak bisa:
                            📍 Ambil langsung di *PWS Computer Service Center*, atau
                            🚚 Kami bantu *antar ke alamat Kakak* (akan dikenakan biaya ongkir sesuai jarak).

                            Mohon konfirmasinya ya Kak, apakah ingin *diambil sendiri* atau *dikirim*? 🙏

                            Terima kasih atas kepercayaannya 💙
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
                        default => [],
                    })
                    ->action(function ($record, $data, $action) {

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
                            $record->update([
                                'status' => $nextStatus,
                                'solution' => $data['solution'] ?? null,
                                'analisa' => $data['analisa'] ?? null,
                                'service_cost' => $data['service_cost'] ?? null,
                            ]);

                            // Simpan foto ke tabel PesananOrderPhoto
                            foreach ($data['progress_photos'] as $path) {
                                $record->photos()->create([
                                    'type' => 'progress',
                                    'path' => $path,
                                ]);
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
                        } else {
                            $record->update(['status' => $nextStatus]);
                        }

                        // 🔥 SIMPAN HISTORY PERUBAHAN STATUS
                        $record->statusHistories()->create([
                            'old_status' => $currentStatus,
                            'new_status' => $nextStatus,
                            'changed_by' => auth()->id(),
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
