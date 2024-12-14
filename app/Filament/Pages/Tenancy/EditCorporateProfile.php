<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditCorporateProfile extends EditTenantProfile
{
    protected static ?string $slug = 'tenant-profiles';

    public static function getLabel(): string
    {
        return 'Perfil da Corporação';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('phone'),
                TextInput::make('document'),
                FileUpload::make('avatar_url')
                          ->directory('logos')
                          ->image()
                          ->imageEditor()
                          ->imageEditorAspectRatios([
                              '1:1',
                          ])
                          ->label('Logo da Empresa')
                          ->openable()
                          ->previewable(true),
            ]);
    }
}
