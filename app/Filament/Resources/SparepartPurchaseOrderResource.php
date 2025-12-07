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
use Illuminate\Support\Facades\Auth;

class SparepartPurchaseOrderResource extends Resource
{
    protected static ?string $model = SparepartPurchaseOrder::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Order Sparepart';

    protected static ?string $recordTitleAttribute = 'po_number';

    // 🔒 Hanya Admin & Supervisor yang bisa order sparepart
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

    // 🔒 Teknisi tidak bisa akses menu ini sama sekali
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'supervisor']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return in_array(Auth::user()?->role, ['admin', 'supervisor']);
    }

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
