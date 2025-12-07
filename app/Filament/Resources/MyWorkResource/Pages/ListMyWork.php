<?php

namespace App\Filament\Resources\MyWorkResource\Pages;

use App\Filament\Resources\MyWorkResource;
use App\Filament\Widgets\MyWorkStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyWork extends ListRecords
{
    protected static string $resource = MyWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada tombol create di sini, karena pesanan dibuat lewat menu Pesanan utama
        ];
    }

    public function getTitle(): string
    {
        return 'Pekerjaan Saya';
    }

    public function getHeading(): string
    {
        return 'Pekerjaan Saya';
    }

    public function getSubheading(): ?string
    {
        return 'Daftar pesanan yang sedang/perlu dikerjakan oleh teknisi';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MyWorkStatsWidget::class,
        ];
    }
}
