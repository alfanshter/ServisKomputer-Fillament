<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SparepartPurchaseOrders\Pages\CreateSparepartPurchaseOrder;
use App\Filament\Resources\SparepartPurchaseOrders\Pages\EditSparepartPurchaseOrder;
use App\Filament\Resources\SparepartPurchaseOrders\Pages\ListSparepartPurchaseOrders;
use App\Filament\Resources\SparepartPurchaseOrders\Schemas\PurchaseOrderForm;
use App\Filament\Resources\SparepartPurchaseOrders\Tables\PurchaseOrdersTable;
use App\Models\SparepartPurchaseOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SparepartPurchaseOrderResource extends Resource
{
    protected static ?string $model = SparepartPurchaseOrder::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Order Sparepart';

    protected static ?string $recordTitleAttribute = 'po_number';

    public static function form(Schema $schema): Schema
    {
        return PurchaseOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseOrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSparepartPurchaseOrders::route('/'),
            'create' => CreateSparepartPurchaseOrder::route('/create'),
            'edit' => EditSparepartPurchaseOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['pending', 'shipped'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
