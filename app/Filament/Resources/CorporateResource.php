<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CorporateResource\Pages;
use App\Models\Corporate;
use App\Services\Traits\CanPermissionTrait;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CorporateResource extends Resource
{
    use CanPermissionTrait;

    protected static ?string  $model                           = Corporate::class;
    protected static ?string  $navigationIcon                  = 'heroicon-o-rectangle-stack';
    protected static bool     $shouldRegisterNavigation        = false;
    public static null|string $tenantOwnershipRelationshipName = "corporate";
    protected static ?string  $tenantRelationshipName          = "corporates";
    protected static ?string  $label                           = 'corporação';
    protected static ?string  $pluralLabel                     = 'corporações';
    protected static ?string  $slug                            = 'corporates';

    public static function formFields()
    {
        $domainSux = env("APP_AMBIENT") == "local" ? "projeto.sv" : "projeto.com.br";

        return [
            TextInput::make('name')
                     ->label("Nome Fantasia")
                     ->live()
                     ->unique()
                     ->afterStateUpdated(function ($state, callable $set) use ($domainSux) {
                         $slug = Str::slug($state);
                         $set('slug', $slug);
                         $set('domain', "{$slug}.{$domainSux}");
                     })
                     ->required()
                     ->maxLength(255),
            TextInput::make('slug')
                     ->unique()
                     ->label("Sub Domínio")
                     ->live()
                     ->afterStateUpdated(function ($state, callable $set) use ($domainSux) {
                         $slug = Str::slug($state);
                         $set('domain', "{$slug}.{$domainSux}");
                     })
                     ->required()
                     ->maxLength(255),
            TextInput::make('phone')
                     ->label("Telefone")
                     ->mask('(99) 99999-9999')
                     ->placeholder('(DDD) 99999-0000')
                     ->tel()
                     ->maxLength(255),
            TextInput::make('document')
                     ->label("CNPJ")
                     ->placeholder('99.999.999/9999-99')
                     ->mask(function (Get $get) {
                         return "99.999.999/9999-99";
                     })
                     ->maxLength(20),
            TextInput::make('domain')
                     ->unique()
                     ->live()
                     ->readOnly()
                     ->label("Domínio (sua-empresa.{$domainSux})")
                     ->maxLength(255),
            FileUpload::make('avatar_url')
                      ->columnSpanFull()
                      ->directory('logos')
                      ->image()
                      ->imageEditor()
                      ->imageEditorAspectRatios([
                          '1:1',
                      ])
                      ->label('Logo da Empresa')
                      ->openable()
                      ->previewable(true),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::formFields());
    }

    public static function table(Table $table): Table
    {
        $tenant = Filament::getTenant();

        return $table
            ->columns([
                TextColumn::make('name')
                          ->searchable(),
                TextColumn::make('phone')
                          ->searchable(),
                TextColumn::make('avatar_url')
                          ->searchable(),
                TextColumn::make('domain')
                          ->searchable()
                          ->copyable()
                          ->copyMessage('Domínio copiado!')
                          ->copyMessageDuration(1500),
                TextColumn::make('document')
                          ->searchable(),
                TextColumn::make('deleted_at')
                          ->dateTime()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),
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
                DeleteAction::make()
                            ->label("Lixeira"),
                RestoreAction::make()
                             ->label("Restaurar da Lixeira"),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                                    ->label("Lixeira"),
                    RestoreBulkAction::make()
                                     ->label("Restaurar da Lixeira"),
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
            'index'  => Pages\ListCorporates::route('/'),
            'create' => Pages\CreateCorporate::route('/create'),
            'edit'   => Pages\EditCorporate::route('/{record}/edit'),
            'view'   => Pages\ViewCorporate::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                     ->withoutGlobalScopes([
                         SoftDeletingScope::class,
                     ]);
    }
}
