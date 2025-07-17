<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->required()
                    ->maxLength(255)
                    ->disabledOn('edit')
                    ->dehydrated(fn(Page $livewire) => $livewire instanceof Pages\CreateUser),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('address')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone_number')
                    ->label('Nomor HP')
                    ->required()
                    ->tel(),

                TextInput::make('password')
                    ->label('Password')
                    ->revealable()
                    ->password()
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->required(fn(Page $livewire) => $livewire instanceof Pages\CreateUser)
                    ->hiddenOn('edit'),

                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->revealable()
                    ->password()
                    ->required(fn(Page $livewire) => $livewire instanceof Pages\CreateUser)
                    ->same('password')
                    ->hiddenOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')->sortable()->label('ID'),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('address')->sortable()->searchable(),
                TextColumn::make('phone_number')->sortable()->label('Nomor HP'),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
