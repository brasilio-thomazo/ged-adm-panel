<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [\App\Http\Controllers\ProfileController::class, "login"])->name("api.login");
Route::get('me', [\App\Http\Controllers\ProfileController::class, "me"])
    ->middleware("auth:sanctum")->name("api.me");
Route::post('me', [\App\Http\Controllers\ProfileController::class, "changePassword"])
    ->middleware("auth:sanctum")->name("api.change-password");
Route::put('me', [\App\Http\Controllers\ProfileController::class, "update"])
    ->middleware("auth:sanctum")->name("api.profile.update");

/**
 * Routes for manipule all models
 */
Route::apiResource('user', \App\Http\Controllers\UserController::class)->middleware('auth:sanctum');
Route::apiResource('group', \App\Http\Controllers\GroupController::class)->middleware('auth:sanctum');
Route::apiResource('client', \App\Http\Controllers\ClientController::class)->middleware('auth:sanctum');
Route::apiResource('app', \App\Http\Controllers\AppController::class)->middleware('auth:sanctum');
Route::apiResource('database_config', \App\Http\Controllers\DatabaseConfigController::class)->middleware('auth:sanctum');
Route::apiResource('cache_config', \App\Http\Controllers\CacheConfigController::class)->middleware('auth:sanctum');

Route::get('app/{app}/kubernetes', [\App\Http\Controllers\AppController::class, 'kubernetes'])->name('app.kubernetes')->middleware('signed');
Route::get('app/{app}/subscribe', [\App\Http\Controllers\AppController::class, 'subscribe'])->name('app.subscribe')->middleware('signed');

// App Group Routes
Route::get('app/{app}/group', [\App\Http\Controllers\App\GroupController::class, 'index'])
    ->middleware('auth:sanctum')->name('app.group.index');
Route::post('app/{app}/group', [\App\Http\Controllers\App\GroupController::class, 'store'])
    ->middleware('auth:sanctum')->name('app.group.store');
Route::get('app/{app}/group/{id}', [\App\Http\Controllers\App\GroupController::class, 'show'])
    ->middleware('auth:sanctum')->name('app.group.show');
Route::put('app/{app}/group/{id}', [\App\Http\Controllers\App\GroupController::class, 'update'])
    ->middleware('auth:sanctum')->name('app.group.update');
Route::delete('app/{app}/group/{id}', [\App\Http\Controllers\App\GroupController::class, 'destroy'])
    ->middleware('auth:sanctum')->name('app.group.destroy');

// App User Routes
Route::get('app/{app}/user', [\App\Http\Controllers\App\UserController::class, 'index'])
    ->middleware('auth:sanctum')->name('app.user.index');
Route::post('app/{app}/user', [\App\Http\Controllers\App\UserController::class, 'store'])
    ->middleware('auth:sanctum')->name('app.user.store');
Route::get('app/{app}/user/{id}', [\App\Http\Controllers\App\UserController::class, 'show'])
    ->middleware('auth:sanctum')->name('app.user.show');
Route::put('app/{app}/user/{id}', [\App\Http\Controllers\App\UserController::class, 'update'])
    ->middleware('auth:sanctum')->name('app.user.update');
Route::delete('app/{app}/user/{id}', [\App\Http\Controllers\App\UserController::class, 'destroy'])
    ->middleware('auth:sanctum')->name('app.user.destroy');

// App Document Type Routes
Route::get('app/{app}/document_type', [\App\Http\Controllers\App\DocumentTypeController::class, 'index'])
    ->middleware('auth:sanctum')->name('app.document_type.index');
Route::post('app/{app}/document_type', [\App\Http\Controllers\App\DocumentTypeController::class, 'store'])
    ->middleware('auth:sanctum')->name('app.document_type.store');
Route::get('app/{app}/document_type/{id}', [\App\Http\Controllers\App\DocumentTypeController::class, 'show'])
    ->middleware('auth:sanctum')->name('app.document_type.show');
Route::put('app/{app}/document_type/{id}', [\App\Http\Controllers\App\DocumentTypeController::class, 'update'])
    ->middleware('auth:sanctum')->name('app.document_type.update');
Route::delete('app/{app}/document_type/{id}', [\App\Http\Controllers\App\DocumentTypeController::class, 'destroy'])
    ->middleware('auth:sanctum')->name('app.document_type.destroy');

// App Department Routes
Route::get('app/{app}/department', [\App\Http\Controllers\App\DepartmentController::class, 'index'])
    ->middleware('auth:sanctum')->name('app.department.index');
Route::post('app/{app}/department', [\App\Http\Controllers\App\DepartmentController::class, 'store'])
    ->middleware('auth:sanctum')->name('app.department.store');
Route::get('app/{app}/department/{id}', [\App\Http\Controllers\App\DepartmentController::class, 'show'])
    ->middleware('auth:sanctum')->name('app.department.show');
Route::put('app/{app}/department/{id}', [\App\Http\Controllers\App\DepartmentController::class, 'update'])
    ->middleware('auth:sanctum')->name('app.department.update');
Route::delete('app/{app}/department/{id}', [\App\Http\Controllers\App\DepartmentController::class, 'destroy'])
    ->middleware('auth:sanctum')->name('app.department.destroy');

// App Search Routes
Route::get('app/{app}/search', [\App\Http\Controllers\App\SearchController::class, 'index'])
    ->middleware('auth:sanctum')->name('app.search.index');
Route::post('app/{app}/search', [\App\Http\Controllers\App\SearchController::class, 'store'])
    ->middleware('auth:sanctum')->name('app.search.store');
Route::get('app/{app}/search/{id}', [\App\Http\Controllers\App\SearchController::class, 'show'])
    ->middleware('auth:sanctum')->name('app.search.show');
Route::put('app/{app}/search/{id}', [\App\Http\Controllers\App\SearchController::class, 'update'])
    ->middleware('auth:sanctum')->name('app.search.update');
Route::delete('app/{app}/search/{id}', [\App\Http\Controllers\App\SearchController::class, 'destroy'])
    ->middleware('auth:sanctum')->name('app.search.destroy');
