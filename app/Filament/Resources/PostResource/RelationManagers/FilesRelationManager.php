<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('uploads')
                      ->label("Subir Arquivos")
                      ->form([
                          FileUpload::make('files')
                                    ->multiple()
                                    ->directory('articles_files')
                                    ->label("Arquivos desse Artigo!")
                      ])
                      ->action(function ($data) {
                          $record    = $this->getOwnerRecord();
                          $filesHtml = "<ul>";
                          foreach ($data["files"] as $filePath) {
                              $pathStorage   = "articles_files";
                              $fileName      = basename($filePath);
                              $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                              $folderExist = Storage::disk('public')->makeDirectory($pathStorage);

                              if ($folderExist) {
                                  $this->getRelationship()->create([
                                      "name"         => $fileName,
                                      "extension"    => $fileExtension,
                                      "path"         => $pathStorage,
                                      "filable_id"   => $record->id,
                                      "filable_type" => get_class($record),
                                  ]);

                                  $filesHtml .= "<li>{$fileName}</li>";
                              }
                          }
                          $filesHtml .= "<ul/>";
                          Notification::make()
                                      ->success()
                                      ->title("Arquivos upados com sucesso!")
                                      ->body($filesHtml)
                                      ->send();
                      })
            ])
            ->actions([
                DeleteAction::make(),
                Action::make('download')
                      ->label('Download!')
                      ->icon('heroicon-o-arrow-down-tray')
                      ->color('success')
                      ->action(function ($record) {
                          return Storage::disk('public')
                                        ->download("{$record->path}/{$record->name}");
                      }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
