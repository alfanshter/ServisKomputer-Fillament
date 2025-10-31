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
                    ->placeholder('-'),
                TextEntry::make('capital_cost')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
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
