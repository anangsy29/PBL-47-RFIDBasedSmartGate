<?php

namespace App\Filament\User\Resources\AccessLogResource\Pages;

use App\Filament\User\Resources\AccessLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccessLogs extends ListRecords
{
    protected static string $resource = AccessLogResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
