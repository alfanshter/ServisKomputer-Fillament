<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MyWorkResource\Pages\ListMyWork;
use App\Filament\Resources\Pesanans\Schemas\PesananForm;
use App\Filament\Resources\Pesanans\Tables\PesanansTable;
use App\Models\Pesanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyWorkResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Pekerjaan Saya';

    protected static ?string $modelLabel = 'Pekerjaan Saya';

    protected static ?string $pluralModelLabel = 'Pekerjaan Saya';

    protected static ?int $navigationSort = 0; // Paling atas

    // 🔒 Hanya teknisi yang bisa lihat menu ini
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->role === 'teknisi';
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'teknisi';
    }

    // Filter hanya pesanan yang dikerjakan teknisi
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('status', [
                'belum mulai',    // Pesanan baru yang perlu mulai analisa
                'analisa',        // Sedang analisa
                'dalam proses',   // Sedang dikerjakan
                'menunggu sparepart', // Tunggu sparepart
                'on hold',        // Ditunda sementara
                'revisi',         // Perlu revisi
                'selesai',        // Baru selesai (belum diambil customer)
            ])
            ->with(['user', 'spareparts']);
    }

    public static function form(Schema $schema): Schema
    {
        return PesananForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PesanansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyWork::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('status', [
            'belum mulai',
            'analisa',
            'dalam proses',
            'menunggu sparepart',
            'revisi',
        ])->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
