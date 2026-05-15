<?php

namespace App\Domain\Observers;

use App\Models\RegistroPeso;
use Illuminate\Support\Facades\Log;

class NotificadorPropietario implements IRegistroPesoObserver
{
    public function onPesoRegistrado(RegistroPeso $registro): void
    {
        $arete = $registro->animal?->arete ?? 'desconocido';

        Log::info("Email enviado al propietario del animal {$arete}");
    }
}