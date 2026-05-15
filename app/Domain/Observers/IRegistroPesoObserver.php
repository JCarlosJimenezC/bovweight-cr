<?php

namespace App\Domain\Observers;

use App\Models\RegistroPeso;

interface IRegistroPesoObserver
{
    public function onPesoRegistrado(RegistroPeso $registro): void;
}