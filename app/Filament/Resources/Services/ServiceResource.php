<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Master Jasa';

    protected static ?string $modelLabel = 'Jasa Service';

    protected static ?string $pluralModelLabel = 'Master Jasa Service';

    protected static ?int $navigationSort = 3;

    // 🔒 Teknisi bisa lihat tapi tidak bisa create/edit/delete
    public static function canCreate(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'supervisor']);
    }

    public static function canEdit($record): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'supervisor']);
    }

    public static function canDelete($record): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'supervisor']);
    }

    public static function canDeleteAny(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'supervisor']);
    }

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
