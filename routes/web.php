<?php

use App\Http\Controllers\AnimalSeederController;
use App\Http\Controllers\EstimacionController;
use App\Http\Controllers\RazaTestController;
use App\Http\Controllers\RegistroPesoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/probar-razas', [RazaTestController::class, 'probar']);
Route::get('/sembrar-animales', [AnimalSeederController::class, 'sembrar']);
Route::get('/reporte-rancho/{ranchoId}', [AnimalSeederController::class, 'ver']);
Route::get('/registrar-peso', [RegistroPesoController::class, 'guardar']);
Route::get('/estimar/yolov8', [EstimacionController::class, 'probarYolov8']);
Route::get('/estimar/regresion', [EstimacionController::class, 'probarRegresion']);
Route::get('/estimar/tabla', [EstimacionController::class, 'probarTabla']);
Route::get('/estimar/fallback', [EstimacionController::class, 'probarFallback']);
