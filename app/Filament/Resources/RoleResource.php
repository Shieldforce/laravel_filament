<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RoleResource extends Resource
{
    protected static ?string $model           = Role::class;
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $slug            = 'roles';
    protected static ?string $label           = "Função";
    protected static ?string $pluralLabel     = "Funções";
    protected static ?string $navigationLabel = "Funções";

    public static function canView(Model $record): bool
    {
        if(
            Auth::check() &&
            in_array("Admin", Auth::user()->roles->pluck("name")->toArray())
        ) {
            return true;
        }

        return false;
    }

    public static function canViewAny(): bool
    {
        if(
            Auth::check() &&
            in_array("Admin", Auth::user()->roles->pluck("name")->toArray())
        ) {
            return true;
        }

        return false;
    }

    public static function canCreate(): bool
    {
        if(
            Auth::check() &&
            in_array("Admin", Auth::user()->roles->pluck("name")->toArray())
        ) {
            return true;
        }

        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                         ->required()
                         ->maxLength(255),
                TextInput::make('description')
                         ->maxLength(255),
                Toggle::make('active')
                      ->required(),
                Toggle::make('system')
                      ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                          ->searchable(),
                TextColumn::make('description')
                          ->searchable(),
                IconColumn::make('active')
                          ->boolean(),
                IconColumn::make('system')
                          ->boolean(),
                TextColumn::make('created_at')
                          ->dateTime()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                          ->dateTime()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
