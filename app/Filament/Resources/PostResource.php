<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model           = Post::class;
    protected static ?string $navigationLabel = "Artigos";
    protected static ?string $label           = "Artigo";
    protected static ?string $pluralLabel     = "Artigos";
    protected static ?string $slug            = "posts";
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                                          ->label("Título")
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                                           ->label("Conteúdo")
                                           ->fileAttachmentsDisk('public')
                                           ->fileAttachmentsVisibility('public')
                                           ->fileAttachmentsDirectory('attachments_images')
                                           ->toolbarButtons([
                                               'attachFiles',
                                               'blockquote',
                                               'bold',
                                               'bulletList',
                                               'codeBlock',
                                               'h2',
                                               'h3',
                                               'italic',
                                               'link',
                                               'orderedList',
                                               'redo',
                                               'strike',
                                               'underline',
                                               'undo',
                                           ])
                                           ->required()
                                           ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\FilesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
