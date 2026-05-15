<?php

namespace App\Services;

use App\Domain\Strategies\IAlgoritmoEstimacion;
use App\Domain\ValueObjects\ResultadoEstimacion;

class EstimadorPesoService
{
    public function __construct(
        private IAlgoritmoEstimacion $algoritmo,
    ) {
    }

    public function estimar(array $datos): ResultadoEstimacion
    {
        return $this->algoritmo->ejecutar($datos);
    }
}