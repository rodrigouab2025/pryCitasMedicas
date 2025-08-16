<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EspecialidadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/registrarpaciente', [AuthController::class, 'registrarpaciente']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('especialidades',EspecialidadController::class);
