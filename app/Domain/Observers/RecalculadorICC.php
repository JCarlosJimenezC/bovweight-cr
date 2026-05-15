<?php

namespace App\Domain\Observers;

use App\Models\RegistroPeso;
use Illuminate\Support\Facades\Log;

class RecalculadorICC implements IRegistroPesoObserver
{
    public function onPesoRegistrado(RegistroPeso $registro): void
    {
        $arete = $registro->animal?->arete ?? 'desconocido';
        $pesoKg = (float) $registro->peso_kg;

        Log::info("ICC recalculado para animal {$arete}, peso {$pesoKg} kg");
    }
}