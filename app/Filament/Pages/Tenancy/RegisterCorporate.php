<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Resources\CorporateResource;
use App\Models\Corporate;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterCorporate extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Registrar Corporação';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(array_merge(CorporateResource::formFields(), []));
    }

    protected function handleRegistration(array $data): Corporate
    {
        $corporate = Corporate::create($data);

        $user = User::find(auth()->id());

        $corporate->users()->attach([$user->id]);

        $user->update([
            "system"              => 1,
            "corporate_latest_id" => $corporate->id
        ]);

        return $corporate;
    }
}
