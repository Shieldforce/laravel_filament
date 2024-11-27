<?php

namespace App\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

trait CanPermissionTrait
{
    public static function canView(Model $record): bool
    {
        $slug = self::$slug;

        if (
            Gate::allows("filament.admin.resources.{$slug}.edit") &&
            Gate::allows("filament.admin.resources.{$slug}.view")
        ) {
            return false;
        }

        if (
            Gate::denies("filament.admin.resources.{$slug}.edit") &&
            Gate::allows("filament.admin.resources.{$slug}.view")
        ) {
            return true;
        }

        return false;
    }

    public static function canCreate(): bool
    {
        $slug = self::$slug;
        return Gate::allows(
            "filament.admin.resources.{$slug}.create"
        );
    }

    public static function canEdit(Model $record): bool
    {
        $slug = self::$slug;
        return Gate::allows(
            "filament.admin.resources.{$slug}.edit"
        );
    }

    public static function canViewAny(): bool
    {
        $slug = self::$slug;
        return Gate::allows(
            "filament.admin.resources.{$slug}.index"
        );
    }

    public static function canDelete(Model $record): bool
    {
        if(isset($record->system) && $record->system) {
            return false;
        }

        $slug = self::$slug;
        return Gate::allows(
            "filament.admin.resources.{$slug}.delete"
        );
    }

    public static function canDeleteAny(): bool
    {
        $slug = self::$slug;
        return Gate::allows(
            "filament.admin.resources.{$slug}.delete"
        );
    }

    public static function canForceDelete(Model $record): bool
    {
        return false;
    }

    public static function canRestore(Model $record): bool
    {
        $slug = self::$slug;
        return Gate::allows(
            "filament.admin.resources.{$slug}.delete"
        );
    }

    public static function canForceDeleteAny(): bool
    {
        return false;
    }

    public static function canRestoreAny(): bool
    {
        $slug = self::$slug;
        return Gate::allows(
            "filament.admin.resources.{$slug}.delete"
        );
    }

    public static function canCopy(Model $record): bool {
        return true;
    }
}
