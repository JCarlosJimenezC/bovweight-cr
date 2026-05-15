<?php

namespace App\Domain\Strategies;

use App\Domain\ValueObjects\ResultadoEstimacion;

interface IAlgoritmoEstimacion
{
    public function ejecutar(array $datosEntrada): ResultadoEstimacion;
}