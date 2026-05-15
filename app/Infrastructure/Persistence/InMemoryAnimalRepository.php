<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Repositories\IAnimalRepository;
use App\Models\Animal;

class InMemoryAnimalRepository implements IAnimalRepository
{
    /**
     * @var array<int, Animal>
     */
    private array $animales = [];

    public function findByArete(string $arete): ?Animal
    {
        foreach ($this->animales as $animal) {
            if ($animal->arete === $arete) {
                return $animal;
            }
        }

        return null;
    }

    public function findAllByRancho(int $ranchoId): array
    {
        return array_values(array_filter(
            $this->animales,
            fn (Animal $animal): bool => (int) $animal->rancho_id === $ranchoId,
        ));
    }

    public function save(Animal $animal): void
    {
        foreach ($this->animales as $index => $actual) {
            if ($actual->arete === $animal->arete) {
                $this->animales[$index] = $animal;
                return;
            }
        }

        $this->animales[] = $animal;
    }

    public function delete(int $id): void
    {
        $this->animales = array_values(array_filter(
            $this->animales,
            fn (Animal $animal): bool => (int) $animal->id !== $id,
        ));
    }
}