<?php

namespace App\Filament\Resources\CreditCardTransactions;

use App\Filament\Resources\CreditCardTransactions\Pages\CreateCreditCardTransaction;
use App\Filament\Resources\CreditCardTransactions\Pages\EditCreditCardTransaction;
use App\Filament\Resources\CreditCardTransactions\Pages\ListCreditCardTransactions;
use App\Filament\Resources\CreditCardTransactions\Schemas\CreditCardTransactionForm;
use App\Filament\Resources\CreditCardTransactions\Tables\CreditCardTransactionsTable;
use App\Models\CreditCardTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditCardTransactionResource extends Resource
{
    protected static ?string $model = CreditCardTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Transaksi Kartu Kredit';

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Transaksi Kartu Kredit';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Schema $schema): Schema
    {
        return CreditCardTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreditCardTransactionsTable::configure($table);
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
            'index' => ListCreditCardTransactions::route('/'),
            'create' => CreateCreditCardTransaction::route('/create'),
            'edit' => EditCreditCardTransaction::route('/{record}/edit'),
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
