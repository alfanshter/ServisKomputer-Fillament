<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TeamInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama'),
                TextEntry::make('email')
                    ->label('Email'),
                TextEntry::make('phone')
                    ->label('No. Telepon')
                    ->placeholder('-'),
                TextEntry::make('role')
                    ->label('Role / Jabatan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'supervisor' => 'warning',
                        'teknisi' => 'success',
                        'marketing' => 'info',
                        default => 'gray',
                    }),
                TextEntry::make('address')
                    ->label('Alamat')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('Bergabung Sejak')
                    ->dateTime('d M Y, H:i'),
            ]);
    }
}
