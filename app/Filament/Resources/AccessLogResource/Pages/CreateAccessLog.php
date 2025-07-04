<?php

namespace App\Filament\Resources\AccessLogResource\Pages;

use App\Filament\Resources\AccessLogResource;
use App\Models\AccessLog;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccessLog extends CreateRecord
{
    protected static string $resource = AccessLogResource::class;

    protected function getRedirectUrl(): string
    {
        return AccessLogResource::getUrl('index');
    }
}
