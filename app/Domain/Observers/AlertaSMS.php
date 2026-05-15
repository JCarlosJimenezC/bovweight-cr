<?php

namespace App\Domain\Observers;

use App\Models\RegistroPeso;
use Illuminate\Support\Facades\Log;

class AlertaSMS implements IRegistroPesoObserver
{
    public function onPesoRegistrado(RegistroPeso $registro): void
    {
        $pesoKg = (float) $registro->peso_kg;

        Log::info("SMS de alerta enviado por peso de {$pesoKg} kg");
    }
}