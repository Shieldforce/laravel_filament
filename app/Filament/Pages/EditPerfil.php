<?php

namespace App\Filament\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditPerfil extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                         ->disabled(fn(?Model $record = null): bool => isset($record->system) && $record->system)
                         ->label('Nome')
                         ->required()
                         ->maxLength(255)
                         ->autofocus(),
                TextInput::make('email')
                         ->disabled(fn(?Model $record = null): bool => isset($record->system) && $record->system)
                         ->label('E-mail')
                         ->email()
                         ->required()
                         ->maxLength(255)
                         ->unique(ignoreRecord: true),
                TextInput::make('password')
                         ->disabled(fn(?Model $record = null): bool => isset($record->system) && $record->system)
                         ->label(__('filament-panels::pages/auth/edit-profile.form.password.label'))
                         ->password()
                         ->rule(Password::default())
                         ->autocomplete('new-password')
                         ->dehydrated(fn($state): bool => filled($state))
                         ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                         ->live(debounce: 500)
                         ->same('passwordConfirmation'),
                TextInput::make('passwordConfirmation')
                         ->disabled(fn(?Model $record = null): bool => isset($record->system) && $record->system)
                         ->label(__('filament-panels::pages/auth/edit-profile.form.password_confirmation.label'))
                         ->password()
                         ->required()
                         ->visible(fn(Forms\Get $get): bool => filled($get('password')))
                         ->dehydrated(false),
                FileUpload::make('avatar_url')
                          //->disabled(fn(?Model $record = null): bool => isset($record->system) && $record->system)
                          ->directory('avatares')
                          ->image()
                          ->imageEditor()
                          ->imageEditorAspectRatios([
                              '1:1',
                          ])
                          ->label('Foto do Perfil')
                          ->openable()
                          ->previewable(true),
            ]);
    }
}
