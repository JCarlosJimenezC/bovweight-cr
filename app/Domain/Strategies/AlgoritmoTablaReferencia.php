<?php

namespace App\Domain\Strategies;

use App\Domain\ValueObjects\ResultadoEstimacion;
use InvalidArgumentException;

class AlgoritmoTablaReferencia implements IAlgoritmoEstimacion
{
    /**
     * @var array<string, array<int, float>>
     */
    private array $tablaPesos = [
        'brahman' => [
            12 => 250.0,
            24 => 450.0,
            36 => 600.0,
            48 => 720.0,
        ],
        'nelore' => [
            12 => 230.0,
            24 => 420.0,
            36 => 570.0,
            48 => 690.0,
        ],
    ];

    public function ejecutar(array $datosEntrada): ResultadoEstimacion
    {
        if (! array_key_exists('raza', $datosEntrada) || ! array_key_exists('edad_meses', $datosEntrada)) {
            throw new InvalidArgumentException("Se requieren 'raza' y 'edad_meses' para la tabla de referencia.");
        }

        $raza = mb_strtolower(trim((string) $datosEntrada['raza']));
        $edadMeses = (int) $datosEntrada['edad_meses'];
        $rangos = $this->tablaPesos[$raza] ?? null;

        if ($rangos === null) {
            throw new InvalidArgumentException("No existe tabla de referencia para la raza '{$raza}'.");
        }

        $edadMasCercana = array_reduce(
            array_keys($rangos),
            fn (?int $cercana, int $actual): int => $cercana === null
                ? $actual
                : (abs($actual - $edadMeses) < abs($cercana - $edadMeses) ? $actual : $cercana),
            null,
        );

        return new ResultadoEstimacion(
            pesoKg: $rangos[$edadMasCercana],
            confianzaPorcentaje: 60.0,
            metodoUsado: 'tabla_referencia',
        );
    }
}