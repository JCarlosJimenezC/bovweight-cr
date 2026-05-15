<?php

namespace App\Domain\Factories;

use App\Domain\Models\Raza;

interface IRazaFactory
{
    public function create(string $nombreRaza): Raza;
}