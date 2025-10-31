<?php

namespace App\Filament\Resources\Pesanans\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Customer')->searchable(),
                TextColumn::make('device_type')->label('Perangkat'),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('start_date')->date(),
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
                        'konfirmasi' => 'Konfirmasi',
                        'dalam_proses' => 'Dalam Proses',
                        'selesai' => 'Selesai',
                        'dibayar' => 'Dibayar',
                    ]),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),

                Action::make('next_status')
                    ->label(fn($record) => match ($record->status) {
                        'belum mulai' => 'Mulai Analisa',
                        'analisa' => 'Analisa Selesai',
                        'selesai_analisa' => 'Konfirmasi',
                        'konfirmasi' => 'Mulai Proses',
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
                            Textarea::make('sparepart')
                                ->label('Sparepart yang perlu diganti')
                                ->rows(2),
                            FileUpload::make('progress_photos')
                                ->label('Foto Analisa')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->directory('progress_photos')
                                ->maxFiles(10)
                                ->required(),

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
                            ]);

                              // Simpan foto ke tabel PesananOrderPhoto
                              foreach ($data['progress_photos'] as $path) {
                                $record->photos()->create([
                                    'type' => 'progress',
                                    'path' => $path,
                                ]);
                            }
                        } else {
                            $record->update(['status' => $nextStatus]);
                        }

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
