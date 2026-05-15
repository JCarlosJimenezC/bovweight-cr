<?php

namespace App\Http\Controllers;

use App\Domain\Exceptions\ServicioYolov8NoDisponibleException;
use App\Domain\Strategies\AlgoritmoRegresionLineal;
use App\Domain\Strategies\AlgoritmoTablaReferencia;
use App\Domain\Strategies\AlgoritmoYolov8;
use App\Services\EstimadorPesoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EstimacionController extends Controller
{
    public function probarYolov8(): JsonResponse
    {
        $service = new EstimadorPesoService(new AlgoritmoYolov8());
        $resultado = $service->estimar([
            'imagen_url' => 'https://demo.bovweight.cr/animales/501.jpg',
        ]);

        return response()->json($resultado->toArray());
    }

    public function probarRegresion(): JsonResponse
    {
        $service = new EstimadorPesoService(new AlgoritmoRegresionLineal());
        $resultado = $service->estimar([
            'altura_cm' => 145.0,
            'perimetro_cm' => 195.0,
        ]);

        return response()->json($resultado->toArray());
    }

    public function probarTabla(): JsonResponse
    {
        $service = new EstimadorPesoService(new AlgoritmoTablaReferencia());
        $resultado = $service->estimar([
            'raza' => 'brahman',
            'edad_meses' => 24,
        ]);

        return response()->json($resultado->toArray());
    }

    public function probarFallback(): JsonResponse
    {
        $fallbackAplicado = false;

        try {
            $service = new EstimadorPesoService(new AlgoritmoYolov8(
                simularFallo: true
            ));
            $resultado = $service->estimar([
                'imagen_url' => 'https://demo.bovweight.cr/animales/501.jpg',
            ]);
        } catch (ServicioYolov8NoDisponibleException) {
            Log::warning('YOLOv8 caido, usando tabla de referencia como fallback');

            $service = new EstimadorPesoService(new AlgoritmoTablaReferencia());
            $resultado = $service->estimar([
                'raza' => 'brahman',
                'edad_meses' => 24,
            ]);
            $fallbackAplicado = true;
        }

        return response()->json([
            'fallback_aplicado' => $fallbackAplicado,
            'resultado' => $resultado->toArray(),
        ]);
    }
}