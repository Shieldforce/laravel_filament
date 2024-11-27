<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\Route;

class PermissionGenerateService
{
    public function handle()
    {
        $routesNames = Route::getRoutes()->getRoutesByName();

        $mapListRoutes = array_map(function ($routeName) {
            return str_contains($routeName, "filament.admin.") ? $routeName : null;
        }, array_keys($routesNames));

        $routesPermissions = array_filter($mapListRoutes);

        foreach ($routesPermissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission,
            ], [
                "description" => $this->extractDescription($permission),
                "group"       => $this->extractGroup($permission),
            ]);
        }
    }

    protected function extractDescription(string $routeName): string
    {
        $entity = $this->translate($routeName);

        if (str_contains($routeName, "index")) {
            return "Lista de {$entity}";
        }

        if (str_contains($routeName, "create")) {
            return "Criação de {$entity}";
        }

        if (str_contains($routeName, "edit")) {
            return "Edição de {$entity}";
        }

        if (str_contains($routeName, "delete")) {
            return "Exclusão de {$entity}";
        }

        return "No description";
    }

    protected function extractGroup(string $routeName): string
    {
        return "Grupo de " . $this->translate($routeName);
    }

    protected function translate(string $routeName)
    {
        $separate = explode(".", $routeName);
        $entity   = $separate[3];

        if (str_contains($separate[3], "users")) {
            $entity = "Usuários";
        }

        if (str_contains($separate[3], "roles")) {
            $entity = "Funções";
        }

        if (str_contains($separate[3], "permissions")) {
            $entity = "Permissões";
        }

        return ucfirst($entity);
    }
}
