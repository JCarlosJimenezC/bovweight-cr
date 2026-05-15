<?php

namespace App\Domain\Strategies;

use App\Domain\ValueObjects\ResultadoEstimacion;
use InvalidArgumentException;

class AlgoritmoRegresionLineal implements IAlgoritmoEstimacion
{
    public function ejecutar(array $datosEntrada): ResultadoEstimacion
    {
        if (! array_key_exists('altura_cm', $datosEntrada) || ! array_key_exists('perimetro_cm', $datosEntrada)) {
            throw new InvalidArgumentException("Se requieren 'altura_cm' y 'perimetro_cm' para la regresión lineal.");
        }

        $alturaCm = (float) $datosEntrada['altura_cm'];
        $perimetroCm = (float) $datosEntrada['perimetro_cm'];
        $pesoKg = ($alturaCm * 0.5) + ($perimetroCm * 2.1);

        return new ResultadoEstimacion(
            pesoKg: round($pesoKg, 2),
            confianzaPorcentaje: 78.0,
            metodoUsado: 'regresion_lineal',
        );
    }
}