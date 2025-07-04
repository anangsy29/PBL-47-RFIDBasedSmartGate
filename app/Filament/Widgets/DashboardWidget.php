<?php

namespace App\Filament\Widgets;

use App\Models\RFIDtag;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use GrahamCampbell\ResultType\Success;

class DashboardWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            stat::make('USERS', user::count())
                ->description('Registered Users')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('success'),
            stat::make('RFID TAGS', RFIDtag::count())
                ->description('Registered RFID Tags')
                ->descriptionIcon('heroicon-o-tag', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('info'),
            stat::make('VEHICLES', Vehicle::count())
                ->description('Registered Vehicles')
                ->descriptionIcon('heroicon-o-truck', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('warning'),
        ];
    }
}
