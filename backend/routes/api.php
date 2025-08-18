<?php

use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\HorarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/registrarusuario', [AuthController::class, 'registrarusuario']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('especialidades',EspecialidadController::class);
Route::apiResource('ausencias',AusenciaController::class);
Route::apiResource('horarios',HorarioController::class);
Route::apiResource('citas',CitaController::class);
Route::get('/citas/paciente/{id}', [CitaController::class, 'buscarCitaPaciente']);