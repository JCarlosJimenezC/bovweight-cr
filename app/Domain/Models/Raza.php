<?php

namespace App\Domain\Models;

abstract class Raza
{
    public function __construct(
        protected string $nombre,
        protected float $pesoPromedioAdultoKg,
        protected string $origen,
    ) {
    }

    abstract public function caracteristicas(): string;

    abstract public function factorCrecimiento(): float;

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getPesoPromedioAdultoKg(): float
    {
        return $this->pesoPromedioAdultoKg;
    }

    public function getOrigen(): string
    {
        return $this->origen;
    }
}