<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [\App\Http\Controllers\ProfileController::class, "login"])->name("api.login");
Route::get('me', [\App\Http\Controllers\ProfileController::class, "me"])->middleware("auth:sanctum");
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
Route::get('app/{app}/document_types', [\App\Http\Controllers\App\DocumentTypeController::class, 'index']);
/**
 * Routes for manipule users in app
 */
Route::get('app/{app}/users', [\App\Http\Controllers\App\UserController::class, 'index']);
Route::post('app/{app}/users', [\App\Http\Controllers\App\UserController::class, 'store']);
Route::put('app/{app}/users/{id}', [\App\Http\Controllers\App\UserController::class, 'update']);

Route::get('app/{app}/groups', [\App\Http\Controllers\App\GroupController::class, 'index']);
Route::get('app/{app}/departments', [\App\Http\Controllers\App\DepartmentController::class, 'index']);
