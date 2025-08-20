<?php

use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/horarios/disponibles', [HorarioController::class, 'disponibles']);
Route::post('/registrarusuario', [AuthController::class, 'registrarusuario']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/citas/paciente/{id}', [CitaController::class, 'buscarCitaPaciente']);
Route::get('/reportes/boleta/{id}', [ReporteController::class, 'boletaReserva']); 
Route::post('/reportes/citas', [ReporteController::class, 'reporteCitas']); 
Route::get('citas/medico/{id}', [CitaController::class, 'buscarCitaUsuario']);
Route::put('/citas/{id}/historial', [CitaController::class, 'modificarHistorialPaciente']);
Route::get('/usuarios/buscar', [UserController::class, 'buscarUser']);
Route::get('/especialidades/buscar', [EspecialidadController::class, 'buscarEspecialidad']);
Route::get('/horarios/buscar', [HorarioController::class, 'buscarHorario']);
Route::get('/citas/usuario/{id}/actual', [CitaController::class, 'buscarCitaActualUsuario']);

Route::apiResource('especialidades',EspecialidadController::class);
Route::apiResource('ausencias',AusenciaController::class);
Route::apiResource('horarios',HorarioController::class);
Route::apiResource('citas',CitaController::class);
Route::apiResource('usuarios',UserController::class);

