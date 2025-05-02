<?php

namespace App\Filament\Widgets;

use App\Models\RFIDtag;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            stat::make('USERS', user::count())
                ->description('Registered Users'),
            stat::make('RFID TAGS', RFIDtag::count())
                ->description('Registered RFID Tags'),
            stat::make('VEHICLES', Vehicle::count())
                ->description('Registered Vehicles')
        ];
    }
}
