<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\AccessLogResource\Pages;
use App\Filament\User\Resources\AccessLogResource\RelationManagers;
use App\Models\AccessLog;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;


class AccessLogResource extends Resource
{
    protected static ?string $model = AccessLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('log_id')
                //     ->label('Log ID'),

                TextColumn::make('rfidTag.tag_uid')->sortable()
                    ->label('Tag UID'),

                TextColumn::make('rfidTag.user.name')->sortable()
                    ->label('User Name'),

                TextColumn::make('rfidTag.vehicle.plate_number')->sortable()
                    ->label('Plate Number'),

                TextColumn::make('accessed_at')->sortable()->searchable()
                    ->label('Data/Time')
                    ->dateTime(),

                TextColumn::make('status')->sortable()->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Allowed' => 'success',
                        'Denied' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('note')->limit(20)->sortable()
                    ->label('Note'),
            ])
            ->poll('3s')
            ->defaultSort('accessed_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccessLogs::route('/'),
            'create' => Pages\CreateAccessLog::route('/create'),
            'edit' => Pages\EditAccessLog::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = Auth::id();

        return parent::getEloquentQuery()->whereHas('rfidTag', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()
    //         ->whereHas('rfidTag', function ($query) {
    //             $query->where('user_id', Auth::id());
    //         });
    // }
}
