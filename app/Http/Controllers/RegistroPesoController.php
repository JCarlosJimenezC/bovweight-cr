<?php

namespace App\Http\Controllers;

use App\Services\RegistroPesoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class RegistroPesoController extends Controller
{
    public function guardar(RegistroPesoService $service): JsonResponse
    {
        $registro = $service->registrar([
            'animal_id' => 1,
            'peso_kg' => 480,
            'confianza_porcentaje' => 92,
            'metodo_usado' => 'yolov8',
            'fecha_registro' => Carbon::today()->toDateString(),
        ]);

        return response()->json([
            'mensaje' => 'Registro de peso creado y observadores notificados.',
            'registro' => $registro,
        ], 201);
    }
}