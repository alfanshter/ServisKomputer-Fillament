<?php

namespace App\Filament\Resources\Pesanans\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PesananInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->numeric(),
                TextEntry::make('device_type'),
                TextEntry::make('damage_description')
                    ->columnSpanFull(),
                TextEntry::make('solution')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('priority')
                    ->badge(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('start_date')
                    ->dateTime(),
                TextEntry::make('end_date')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('service_cost')
                    ->numeric()
                    ->placeholder('-')
                    ->money('IDR', true)
                    ->label('Biaya Jasa Servis'),

                TextEntry::make('total_sparepart_cost')
                    ->label('Total Biaya Sparepart')
                    ->getStateUsing(fn($record) => $record->spareparts->sum('pivot.subtotal'))
                    ->money('IDR', true)
                    ->weight('bold')
                    ->color('warning')
                    ->visible(fn($record) => $record->spareparts->count() > 0),

                TextEntry::make('total_cost')
                    ->label('Total Keseluruhan')
                    ->placeholder(fn($record) => $record->total_cost ? null : 'Belum dihitung')
                    ->getStateUsing(function ($record) {
                        // Gunakan total_cost dari database jika ada
                        if ($record->total_cost) {
                            return $record->total_cost;
                        }
                        // Fallback ke perhitungan manual untuk data lama
                        $serviceCost = $record->service_cost ?? 0;
                        $sparepartCost = $record->spareparts->sum('pivot.subtotal');
                        return $serviceCost + $sparepartCost;
                    })
                    ->money('IDR', true)
                    ->weight('bold')
                    ->size('lg')
                    ->color('success'),

                TextEntry::make('capital_cost')
                    ->numeric()
                    ->placeholder('-')
                    ->money('IDR', true)
                    ->label('Modal'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),

                // 🔩 Sparepart yang Digunakan
                RepeatableEntry::make('spareparts')
                    ->label('Sparepart yang Digunakan')
                    ->getStateUsing(function ($record) {
                        if ($record->spareparts->count() === 0) {
                            return null;
                        }
                        return $record->spareparts->map(function ($sparepart) {
                            return [
                                'name' => $sparepart->name,
                                'quantity' => $sparepart->pivot->quantity,
                                'price' => $sparepart->pivot->price,
                                'subtotal' => $sparepart->pivot->subtotal,
                            ];
                        })->toArray();
                    })
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Sparepart')
                            ->weight('bold')
                            ->size('lg'),
                        TextEntry::make('quantity')
                            ->label('Jumlah')
                            ->suffix(' unit')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('price')
                            ->label('Harga Satuan')
                            ->money('IDR', true),
                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('IDR', true)
                            ->weight('bold')
                            ->color('success'),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->visible(fn($record) => $record->spareparts->count() > 0),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),

                // 🟡 Foto Sebelum
                RepeatableEntry::make('before_photos')
                    ->label('Foto Sebelum')
                    ->getStateUsing(
                        fn($record) =>
                        $record->photos
                            ->where('type', 'before')
                            ->map(fn($p) => ['path' => $p->path])
                            ->values()
                            ->toArray()
                    )
                    ->schema([
                        ImageEntry::make('path')->label('')->size(150),
                    ])
                    ->columnSpanFull(),

                    RepeatableEntry::make('foto-progress')
                    ->label('Foto Progress')
                    ->getStateUsing(
                        fn($record) =>
                        $record->photos
                            ->where('type', 'progress')
                            ->map(fn($p) => ['path' => $p->path])
                            ->values()
                            ->toArray()
                    )
                    ->schema([
                        ImageEntry::make('path')->label('')->size(150),
                    ])
                    ->columnSpanFull(),

                // 🟢 Foto Sesudah
                RepeatableEntry::make('after_photos')
                    ->label('Foto Sesudah')
                    ->getStateUsing(
                        fn($record) =>
                        $record->photos
                            ->where('type', 'after')
                            ->map(fn($p) => ['path' => $p->path])
                            ->values()
                            ->toArray()
                    )
                    ->schema([
                        ImageEntry::make('path')->label('')->size(150),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
