<?php

namespace App\Domain\Models;

class Nelore extends Raza
{
    public function __construct()
    {
        parent::__construct('Nelore', 700.0, 'India');
    }

    public function caracteristicas(): string
    {
        return 'Pelaje blanco, alta fertilidad, adaptable a climas tropicales';
    }

    public function factorCrecimiento(): float
    {
        return 1.10;
    }
}