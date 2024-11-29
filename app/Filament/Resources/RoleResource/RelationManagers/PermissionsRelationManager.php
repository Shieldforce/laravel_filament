<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use App\Models\Permission;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    public function form(Form $form): Form
    {
        $role   = $this->getOwnerRecord();
        $groups = Permission::whereNotIn("id", $role->permissions->pluck('id'))
                            ->select(["id", "group", "description"])
                            ->get()
                            ->groupBy("group");

        $list = [];
        foreach ($groups as $group => $permissions) {
            foreach ($permissions as $permission) {
                $list[$group][$permission->id] =
                    "--- " . $permission->description;
            }
        }

        return $form
            ->schema([
                Select::make('permissions')
                      ->label('Permissões')
                      ->options($list)
                      ->searchable()
                      ->columnSpanFull()
                      ->multiple()
                      ->columnSpan(4)
                      ->required()
                      ->reactive(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make('permissions')
                            ->modelLabel("(Escolher Permissões)")
                            ->modalSubmitActionLabel("Adicionar")
                            ->label('Escolher Permissões')
                            ->action(function (CreateAction $action) {
                                $action->process(static fn(Model $record) => $record->save());
                                $action->success();
                            })
                            ->using(function ($data) {
                                $model = $this->getOwnerRecord();
                                if (isset($model->id)) {
                                    $model->permissions()
                                          ->syncWithoutDetaching($data['permissions'] ?? []);
                                }
                            }),
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
}
