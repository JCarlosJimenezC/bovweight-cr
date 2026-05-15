<?php

namespace App\Domain\ValueObjects;

final class ResultadoEstimacion
{
    public function __construct(
        public readonly float $pesoKg,
        public readonly float $confianzaPorcentaje,
        public readonly string $metodoUsado,
    ) {
    }

    public function toArray(): array
    {
        return [
            'pesoKg' => $this->pesoKg,
            'confianzaPorcentaje' => $this->confianzaPorcentaje,
            'metodoUsado' => $this->metodoUsado,
        ];
    }
}