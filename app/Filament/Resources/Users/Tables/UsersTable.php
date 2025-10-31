<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable(),

              ])
            ->filters([
                SelectFilter::make('role')
                ->label('Pilih Role')
                ->options([
                    'customer' => 'Customer',
                    'admin' => 'Admin',
                    'teknisi' => 'Teknisi',
                    'marketing' => 'Marketing',
                    'supervisor' => 'Supervisor',
                ])
                ->native(false) // ini bikin tampilannya jadi dropdown Filament, bukan native browser

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
