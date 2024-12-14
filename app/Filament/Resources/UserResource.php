<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Services\Traits\CanPermissionTrait;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use CanPermissionTrait;

    protected static ?string $model                           = User::class;
    protected static ?string $navigationIcon                  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup                 = 'Configurações';
    protected static ?string $slug                            = 'users';
    protected static ?string $label                           = "Usuário";
    protected static ?string $pluralLabel                     = "Usuários";
    protected static ?string $navigationLabel                 = "Usuários";
    protected static ?string $tenantOwnershipRelationshipName = 'users';
    protected static ?string $tenantRelationshipName          = 'corporates';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                         ->required(),
                TextInput::make('email')
                         ->required()
                         ->email(),
                TextInput::make('password')
                         ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                         ->dehydrated(fn(?string $state): bool => filled($state))
                         ->required(fn(string $operation): bool => $operation === 'create')
                         ->password(),
                Select::make('roles')
                      ->relationship('roles', 'name')
                      ->multiple()
                      ->preload()
                      ->optionsLimit(5)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email')
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
            'delete' => Pages\DeleteUser::route('/{record}/delete'),
        ];
    }
}
