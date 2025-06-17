<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\RfidTag;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RfidTagResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RfidTagResource\RelationManagers;
use PhpParser\Node\Stmt\Label;

class RfidTagResource extends Resource
{
    protected static ?string $model = RfidTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn(Set $set) => $set('vehicles_id', null)),

                Select::make('vehicles_id')
                    ->label('Vehicles Plate Number')
                    ->options(
                        fn(Get $get) =>
                        Vehicle::where('user_id', $get('user_id'))
                            ->pluck('plate_number', 'vehicles_id')
                    )
                    ->required()
                    ->disabled(fn(Get $get) => $get('user_id') === null)
                    ->searchable()
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $vehicleId = $get('vehicles_id');
                        $vehicle = Vehicle::find($vehicleId);

                        // Pastikan setelah memilih kendaraan, vehicle_id yang benar yang akan disimpan
                        if ($vehicle) {
                            $set('vehicles_id', $vehicle->vehicles_id);  // Menyimpan vehicles_id, bukan plate_number
                        }
                    }),

                TextInput::make('tag_uid')
                    ->label('RFID UID')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('vehicle.plate_number')->label('Plate Number')->sortable()->searchable(),
                TextColumn::make('tag_uid')->label('Tag UID')->sortable()->searchable(),
                TextColumn::make('status')
                    ->badge() 
                    ->color(fn(string $state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                        default => 'gray',
                    })->sortable()->searchable(),
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
            'index' => Pages\ListRfidTags::route('/'),
            'create' => Pages\CreateRfidTag::route('/create'),
            'edit' => Pages\EditRfidTag::route('/{record}/edit'),
        ];
    }
}
