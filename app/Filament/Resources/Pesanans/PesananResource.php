<?php

namespace App\Filament\Resources\Pesanans;

use App\Filament\Resources\Pesanans\Pages\CreatePesanan;
use App\Filament\Resources\Pesanans\Pages\EditPesanan;
use App\Filament\Resources\Pesanans\Pages\ListPesanans;
use App\Filament\Resources\Pesanans\Pages\ViewPesanan;
use App\Filament\Resources\Pesanans\RelationManagers\StatusHistoriesRelationManager;
use App\Filament\Resources\Pesanans\Schemas\PesananForm;
use App\Filament\Resources\Pesanans\Schemas\PesananInfolist;
use App\Filament\Resources\Pesanans\Tables\PesanansTable;
use App\Models\Pesanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Semua Pesanan';

    protected static ?string $recordTitleAttribute = 'Pesanan';

    protected static ?int $navigationSort = 1;

    // 🔒 Ubah label navigasi berdasarkan role
    public static function getNavigationLabel(): string
    {
        $userRole = Auth::user()?->role;

        if ($userRole === 'teknisi') {
            return 'Semua Pesanan'; // Teknisi lihat semua untuk referensi
        }

        return 'Pesanan'; // Admin/Supervisor
    }

    // Filter query berdasarkan role (opsional - sekarang semua bisa lihat semua)
    // Kalau mau teknisi hanya lihat yang relevan, uncomment ini:
    // public static function getEloquentQuery(): Builder
    // {
    //     $query = parent::getEloquentQuery();
    //
    //     if (Auth::user()?->role === 'teknisi') {
    //         // Teknisi hanya lihat yang belum dibayar/batal
    //         return $query->whereNotIn('status', ['dibayar', 'batal']);
    //     }
    //
    //     return $query;
    // }

    public static function form(Schema $schema): Schema
    {
        return PesananForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PesananInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PesanansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StatusHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPesanans::route('/'),
            'create' => CreatePesanan::route('/create'),
            'view' => ViewPesanan::route('/{record}'),
            'edit' => EditPesanan::route('/{record}/edit'),
        ];
    }
}
