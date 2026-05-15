<?php

namespace App\Domain\Factories;

use App\Domain\Models\Brahman;
use App\Domain\Models\Nelore;
use App\Domain\Models\Raza;
use InvalidArgumentException;
use ReflectionClass;

class RazaFactory implements IRazaFactory
{
    /**
     * @var array<string, class-string<Raza>>
     */
    private array $razas = [
        'brahman' => Brahman::class,
        'nelore' => Nelore::class,
    ];

    public function create(string $nombreRaza): Raza
    {
        $nombreNormalizado = mb_strtolower(trim($nombreRaza));
        $razaClass = $this->razas[$nombreNormalizado]
            ?? throw new InvalidArgumentException(
                "La raza '{$nombreRaza}' no está soportada por la factory."
            );

        /** @var Raza $raza */
        $raza = (new ReflectionClass($razaClass))->newInstance();

        return $raza;
    }
}

// Para agregar Angus no se modifica la lógica de la factory:
// 1. Crear la clase Angus que extienda Raza.
// 2. Añadir una línea al arreglo $razas: 'angus' => Angus::class,