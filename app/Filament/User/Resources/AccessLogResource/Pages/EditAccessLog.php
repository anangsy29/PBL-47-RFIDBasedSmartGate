<?php

namespace App\Filament\User\Resources\AccessLogResource\Pages;

use App\Filament\User\Resources\AccessLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccessLog extends EditRecord
{
    protected static string $resource = AccessLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
