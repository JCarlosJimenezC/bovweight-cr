<?php

namespace App\Domain\Models;

class Brahman extends Raza
{
    public function __construct()
    {
        parent::__construct('Brahman', 750.0, 'India');
    }

    public function caracteristicas(): string
    {
        return 'Resistente al calor, joroba prominente, orejas largas';
    }

    public function factorCrecimiento(): float
    {
        return 1.15;
    }
}