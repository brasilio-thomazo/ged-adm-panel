<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\URL;

Route::post('login', [\App\Http\Controllers\ProfileController::class, "login"]);
Route::get('me', [\App\Http\Controllers\ProfileController::class, "me"])->middleware("auth:sanctum");


Route::apiResource('users', UserController::class);
Route::apiResource('groups', GroupController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('apps', AppController::class);

Route::post('app/{app}/subscribe', [AppController::class, 'subscribe'])->name('app.subscribe')->middleware('signed');
Route::get('/test', function () {
    $url = URL::signedRoute('app.subscribe', ['app' => '123456abcdef']);
    return response()->json(['message' => 'Hello World!', 'url' => $url]);
});

Route::get('app/{app}/document_types', [\App\Http\Controllers\App\DocumentTypeController::class, 'index']);
Route::get('app/{app}/users', [\App\Http\Controllers\App\UserController::class, 'index']);
Route::get('app/{app}/groups', [\App\Http\Controllers\App\GroupController::class, 'index']);
Route::get('app/{app}/departments', [\App\Http\Controllers\App\DepartmentController::class, 'index']);

Route::get("k8s/{path}", [\App\Http\Controllers\FileController::class, "show"])->where('path', '^k8s/[a-z0-9-]{36}\.yaml')->name("k8s.show");
Route::post("k8s/{app}", [\App\Http\Controllers\FileController::class, "create"])->name("k8s.create");
