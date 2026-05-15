<?php

namespace App\Domain\Observers;

use App\Models\RegistroPeso;

class RegistroPesoSubject
{
    /**
     * @var array<int, IRegistroPesoObserver>
     */
    private array $observadores = [];

    public function suscribir(IRegistroPesoObserver $obs): void
    {
        $this->observadores[] = $obs;
    }

    public function desuscribir(IRegistroPesoObserver $obs): void
    {
        $this->observadores = array_values(array_filter(
            $this->observadores,
            fn (IRegistroPesoObserver $observador): bool => $observador !== $obs,
        ));
    }

    public function notificar(RegistroPeso $registro): void
    {
        foreach ($this->observadores as $observador) {
            $observador->onPesoRegistrado($registro);
        }
    }
}