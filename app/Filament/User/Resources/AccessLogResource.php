<?php

namespace App\Filament\User\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\AccessLog;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\AccessLogResource\Pages;
use Filament\Facades\Filament;
use App\Models\RFIDTag;


class AccessLogResource extends Resource
{
    protected static ?string $model = AccessLog::class;
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tags_id')
                    ->relationship('rfidTag', 'tag_uid')
                    ->placeholder('Cari berdasarkan UID RFID...')
                    ->searchable()
                    ->required()
                    ->preload(),
                DateTimePicker::make('accessed_at')
                    ->required(),
                Select::make('status')
                    ->options([
                        'allowed' => 'Allowed',
                        'denied' => 'Denied',
                    ])
                    ->required(),
                TextInput::make('note')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rfidTag.tag_uid')->label('Tag UID')->sortable()->searchable(),
                TextColumn::make('rfidTag.user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('rfidTag.vehicle.plate_number')->label('Plate Number')->sortable()->searchable(),
                TextColumn::make('accessed_at')->dateTime()->label('Data/Time')->sortable()->searchable(),
                TextColumn::make('status')->sortable()->searchable(),
                TextColumn::make('note')->limit(20)->sortable()->searchable(),
            ])
            ->poll('3s')
            ->defaultSort('accessed_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function getAuthIdentifierName()
    {
        return 'user_id';
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
        $userId = Filament::getPanel()->auth()->user()->user_id;

        return parent::getEloquentQuery()
            ->whereHas('rfidTag', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
    }
}
