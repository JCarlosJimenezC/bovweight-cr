<?php

namespace App\Services;

use App\Domain\Repositories\IAnimalRepository;
use App\Models\Animal;

class ReporteService
{
    public function __construct(
        private IAnimalRepository $repo,
    ) {
    }

    public function generarReporteRancho(int $ranchoId): array
    {
        $animales = $this->repo->findAllByRancho($ranchoId);
        $pesosUltimoRegistro = array_values(array_filter(
            array_map(
                fn (Animal $animal): ?float => $this->obtenerUltimoPeso($animal),
                $animales,
            ),
            fn (?float $peso): bool => $peso !== null,
        ));

        $pesoPromedio = count($pesosUltimoRegistro) > 0
            ? round(array_sum($pesosUltimoRegistro) / count($pesosUltimoRegistro), 2)
            : 0.0;

        return [
            'rancho_id' => $ranchoId,
            'cantidad_animales' => count($animales),
            'peso_promedio_ultimo_registro_kg' => $pesoPromedio,
        ];
    }

    private function obtenerUltimoPeso(Animal $animal): ?float
    {
        $ultimoRegistro = $animal->registrosPeso
            ->sortByDesc('fecha_registro')
            ->first();

        return $ultimoRegistro !== null ? (float) $ultimoRegistro->peso_kg : null;
    }
}