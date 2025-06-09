<?php

namespace App\Filament\User\Resources\AccessLogResource\Pages;

use App\Filament\User\Resources\AccessLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use App\Models\RFIDTag;
use App\Models\AccessLog;

class ListAccessLogs extends ListRecords
{
    protected static string $resource = AccessLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = Filament::auth()->user()->id;

        $userTagIds = RFIDTag::where('user_id', $userId)->pluck('id');

        return parent::getEloquentQuery()->whereIn('tags_id', $userTagIds);
    }
}
