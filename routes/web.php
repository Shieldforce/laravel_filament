<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $permissionGenerate = new \App\Services\PermissionGenerateService();
    $permissionGenerate->handle();

    return view('welcome');
});
