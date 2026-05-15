<?php

namespace App\Domain\Observers;

use App\Models\RegistroPeso;
use Illuminate\Support\Facades\Log;

class WebhookSenasa implements IRegistroPesoObserver
{
    public function onPesoRegistrado(RegistroPeso $registro): void
    {
        $arete = $registro->animal?->arete ?? 'desconocido';

        Log::info("Webhook SENASA disparado para animal {$arete}");
    }
}