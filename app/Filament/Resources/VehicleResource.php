<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Vehicle;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VehicleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleResource\RelationManagers;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->label('User')
                ->searchable()
                ->placeholder('Search user name ...')
                ->getSearchResultsUsing(function (string $search) {
                    return User::query()
                        ->where('name', 'like', "%{$search}%")
                        ->limit(10)  // Batasi hasil pencarian
                        ->pluck('name', 'user_id');  // Ambil nama dan ID user
                })
                ->getOptionLabelUsing(function ($value): ?string {
                    return User::where('user_id', $value)->value('name');
                })
                ->required(),

            TextInput::make('plate_number')
                ->unique(ignoreRecord: true)
                ->required(),

            Select::make('vehicle_type')
                ->options([
                    'Mobil' => 'Mobil',
                    'Motor' => 'Motor',
                ])
                ->required(),

            TextInput::make('brand')
                ->nullable(),

            TextInput::make('color')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('user.name')->sortable()->searchable()->label('User'),
            TextColumn::make('plate_number')->sortable()->searchable(),
            TextColumn::make('vehicle_type')->sortable()->searchable(),
            TextColumn::make('brand')->sortable()->searchable(),
            TextColumn::make('color')->sortable()->searchable(),
        ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
