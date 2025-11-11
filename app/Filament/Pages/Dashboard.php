<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use BackedEnum;

class Dashboard extends BaseDashboard
{
protected static BackedEnum|string|null $navigationIcon = null;

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
}
