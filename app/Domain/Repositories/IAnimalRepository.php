<?php

namespace App\Domain\Repositories;

use App\Models\Animal;

interface IAnimalRepository
{
    public function findByArete(string $arete): ?Animal;

    /**
     * @return array<int, Animal>
     */
    public function findAllByRancho(int $ranchoId): array;

    public function save(Animal $animal): void;

    public function delete(int $id): void;
}