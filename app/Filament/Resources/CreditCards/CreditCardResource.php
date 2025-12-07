<?php

namespace App\Filament\Resources\CreditCards;

use App\Filament\Resources\CreditCards\Pages\CreateCreditCard;
use App\Filament\Resources\CreditCards\Pages\EditCreditCard;
use App\Filament\Resources\CreditCards\Pages\ListCreditCards;
use App\Filament\Resources\CreditCards\Schemas\CreditCardForm;
use App\Filament\Resources\CreditCards\Tables\CreditCardsTable;
use App\Models\CreditCard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditCardResource extends Resource
{
    protected static ?string $model = CreditCard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $navigationLabel = 'Kartu Kredit';

    protected static ?string $modelLabel = 'Kartu Kredit';

    protected static ?string $pluralModelLabel = 'Kartu Kredit';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'card_name';

    public static function form(Schema $schema): Schema
    {
        return CreditCardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreditCardsTable::configure($table);
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
            'index' => ListCreditCards::route('/'),
            'create' => CreateCreditCard::route('/create'),
            'edit' => EditCreditCard::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
