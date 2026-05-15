<?php

namespace App\Domain\Strategies;

use App\Domain\Exceptions\ServicioYolov8NoDisponibleException;
use App\Domain\ValueObjects\ResultadoEstimacion;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class AlgoritmoYolov8 implements IAlgoritmoEstimacion
{
    public function __construct(
        private string $endpoint = 'http://yolov8-service.local/predict',
        private int $timeoutSegundos = 5,
        private bool $simularFallo = false,
    ) {
    }

    public function ejecutar(array $datosEntrada): ResultadoEstimacion
    {
        $imagenUrl = $datosEntrada['imagen_url'] ?? null;

        if (! is_string($imagenUrl) || trim($imagenUrl) === '') {
            throw new InvalidArgumentException("Se requiere 'imagen_url' para ejecutar YOLOv8.");
        }

        if ($this->simularFallo) {
            throw new ServicioYolov8NoDisponibleException();
        }

        Log::info('Llamando servicio YOLOv8', [
            'endpoint' => $this->endpoint,
            'timeout_segundos' => $this->timeoutSegundos,
            'image_url' => $imagenUrl,
        ]);

        $inicio = microtime(true);
        usleep(150000);
        $latenciaMs = round((microtime(true) - $inicio) * 1000, 2);

        Log::info('Latencia YOLOv8 simulada', [
            'latencia_ms' => $latenciaMs,
        ]);

        $hash = sprintf('%u', crc32($imagenUrl));
        $base = (int) $hash;
        $pesoKg = 380.0 + (($base % 24001) / 100);
        $confianzaPorcentaje = 85.0 + ((($base >> 3) % 1251) / 100);
        $resultado = new ResultadoEstimacion(
            pesoKg: round($pesoKg, 2),
            confianzaPorcentaje: round($confianzaPorcentaje, 2),
            metodoUsado: 'yolov8',
        );

        Log::info('Resultado YOLOv8 simulado', $resultado->toArray());

        return $resultado;
    }
}